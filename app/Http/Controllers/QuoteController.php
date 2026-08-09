<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Prefecture;
use App\Models\BuildingType;
use App\Models\ServiceCategory;
use App\Models\ServiceMethod;
use App\Models\QuoteRequest;
use App\Models\QuoteRecipient;
use App\Models\EmailSettings;
use App\Models\ConsultationSubmission;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class QuoteController extends Controller
{
    public function create(Request $request)
    {
        $prefectures = Prefecture::all();
        $buildingTypes = BuildingType::all();
        $serviceCategories = ServiceCategory::all();
        $serviceMethods = ServiceMethod::all();

        // Get target companies
        $compareIds = session('compare_company_ids', []);
        $targetCompanies = [];

        if (!empty($compareIds)) {
            // From compare
            $targetCompanies = Company::published()->whereIn('id', $compareIds)->get();
        }
        // Remove automatic company selection - only show user-selected companies

        return view('quote.create', compact(
            'prefectures', 
            'buildingTypes', 
            'serviceCategories', 
            'serviceMethods',
            'targetCompanies'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_kind' => 'required|in:corp,personal',
            'company_name' => 'required_if:client_kind,corp|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'prefecture_id' => 'required|exists:prefectures,id',
            'city_text' => 'nullable|string|max:255',
            'building_type_id' => 'required|exists:building_types,id',
            'floors' => 'required|integer|min:1',
            'glass_area_type' => 'required|in:small,medium,large',
            'service_category_id' => 'required|exists:service_categories,id',
            'preferred_service_method_id' => 'nullable|exists:service_methods,id',
            'preferred_timing' => 'required|in:urgent,this_week,this_month,undecided',
            'building_name' => 'nullable|string|max:255',
            'priorities' => 'nullable|array',
            'priorities.*' => 'string|in:低価格,安全対策,高所実績,迅速対応,大型ビル対応,相談重視',
            'note' => 'nullable|string',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'wishlist_companies' => 'nullable|string', // JSON string of selected companies
        ]);

        // Rate limiting check
        $rateLimitKey = 'quote_request:' . $validated['email'] . ':' . 
                       $validated['prefecture_id'] . ':' . 
                       $validated['floors'] . ':' . 
                       $validated['building_type_id'] . ':' . 
                       $validated['service_category_id'];

        if (Cache::has($rateLimitKey)) {
            return back()->withErrors(['email' => '同じ条件での見積もり依頼は30分間に1回までです。']);
        }

        // Create quote request
        $quoteRequest = QuoteRequest::create($validated);

        // Determine target companies
        $compareIds = session('compare_company_ids', []);
        $targetCompanies = [];
        $wishlistCompanies = [];

        // Parse wishlist companies from form data
        if (!empty($validated['wishlist_companies'])) {
            $wishlistData = json_decode($validated['wishlist_companies'], true);
            if (is_array($wishlistData)) {
                $wishlistCompanyIds = collect($wishlistData)->pluck('id')->filter();
                $wishlistCompanies = Company::published()->whereIn('id', $wishlistCompanyIds)->get();
            }
        }

        if (!empty($wishlistCompanies)) {
            // Use wishlist companies
            $targetCompanies = $wishlistCompanies;
            $quoteRequest->update(['type' => 'wishlist']);
        } elseif (!empty($compareIds)) {
            // Use compare companies
            $targetCompanies = Company::published()->whereIn('id', $compareIds)->get();
            $quoteRequest->update(['type' => 'bulk']);
        } else {
            // Use filter-based matching
            $filters = [
                'prefecture_id' => $validated['prefecture_id'],
                'building_type_id' => $validated['building_type_id'],
                'floors' => $validated['floors'],
                'service_category_id' => $validated['service_category_id'],
                'preferred_service_method_id' => $validated['preferred_service_method_id'] ?? null,
                'emergency' => $validated['preferred_timing'] === 'urgent',
            ];

            $targetCompanies = Company::forQuote($filters)
                ->orderByDesc('rank_score')
                ->take(5)
                ->get();
        }

        // Create recipients and send emails
        foreach ($targetCompanies as $company) {
            $recipient = QuoteRecipient::create([
                'quote_request_id' => $quoteRequest->id,
                'company_id' => $company->id,
                'delivery_status' => 'queued',
            ]);

            $this->sendQuoteEmail($quoteRequest, $company, $recipient);
        }

        // Set rate limit
        Cache::put($rateLimitKey, true, now()->addMinutes(30));

        // Clear compare session
        session()->forget('compare_company_ids');

        // Record consultation submission
        ConsultationSubmission::create([
            'session_id' => session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'form_data' => json_encode([
                'client_kind' => $validated['client_kind'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'prefecture_id' => $validated['prefecture_id'],
                'service_category_id' => $validated['service_category_id'],
                'glass_area_type' => $validated['glass_area_type'],
                'preferred_timing' => $validated['preferred_timing'],
                'target_companies_count' => $targetCompanies->count(),
            ]),
            'submitted_at' => now(),
        ]);

        // Update status
        $quoteRequest->update(['status' => 'sent']);

        return redirect()->route('quote.complete')->with('quote_request', $quoteRequest);
    }

    public function complete()
    {
        $quoteRequest = session('quote_request');
        
        return view('quote.complete', compact('quoteRequest'));
    }

    private function sendQuoteEmail($quoteRequest, $company, $recipient)
    {
        try {
            // Get email settings
            $fromEmail = EmailSettings::getValue('quote_from_email', 'noreply@oyazuna.com');
            $fromName = EmailSettings::getValue('quote_from_name', 'オヤズナ');
            $subjectTemplate = EmailSettings::getValue('quote_subject_template', '【オヤズナ】新しい見積もり依頼が届きました');
            $bodyTemplate = EmailSettings::getValue('quote_email_template', 'デフォルトテンプレート');

            // Prepare template variables
            $variables = [
                'company_name' => $company->name,
                'client_company' => $quoteRequest->company_name ?? '個人',
                'client_name' => $quoteRequest->name,
                'client_email' => $quoteRequest->email,
                'client_phone' => $quoteRequest->phone ?? '未記入',
                'prefecture' => $quoteRequest->prefecture->name ?? '',
                'city' => $quoteRequest->city_text ?? '',
                'building_type' => $quoteRequest->buildingType->name ?? '',
                'floors' => $quoteRequest->floors,
                'glass_area' => match($quoteRequest->glass_area_type) {
                    'small' => '小規模（～100㎡）',
                    'medium' => '中規模（100～500㎡）',
                    'large' => '大規模（500㎡～）',
                    default => $quoteRequest->glass_area_type
                },
                'service_category' => $quoteRequest->serviceCategory->name ?? '',
                'preferred_timing' => match($quoteRequest->preferred_timing) {
                    'urgent' => '緊急（1週間以内）',
                    'this_week' => '今週中',
                    'this_month' => '今月中',
                    'undecided' => '未定',
                    default => $quoteRequest->preferred_timing
                },
                'building_name' => $quoteRequest->building_name ?? '未記入',
                'priorities' => is_array($quoteRequest->priorities) ? implode('、', $quoteRequest->priorities) : '未選択',
                'note' => $quoteRequest->note ?? '未記入',
            ];

            // Replace template variables
            $subject = $subjectTemplate;
            $body = $bodyTemplate;
            
            foreach ($variables as $key => $value) {
                $body = str_replace("{{{$key}}}", $value, $body);
            }

            // Send email to company
            Mail::raw($body, function ($message) use ($company, $fromEmail, $fromName, $subject) {
                $message->from($fromEmail, $fromName)
                        ->to($company->email)
                        ->subject($subject);
            });

            // Send notification to admin
            $adminEmail = EmailSettings::getValue('quote_notification_email');
            if ($adminEmail) {
                $adminBody = "新しい見積もり依頼が " . $company->name . " に送信されました。\n\n" . $body;
                Mail::raw($adminBody, function ($message) use ($adminEmail, $fromEmail, $fromName, $subject) {
                    $message->from($fromEmail, $fromName)
                            ->to($adminEmail)
                            ->subject("[管理者通知] " . $subject);
                });
            }

            $recipient->update([
                'delivery_status' => 'sent',
                'sent_at' => now(),
            ]);

        } catch (\Exception $e) {
            $recipient->update([
                'delivery_status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            \Log::error('Quote email failed', [
                'quote_request_id' => $quoteRequest->id,
                'company_id' => $company->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
