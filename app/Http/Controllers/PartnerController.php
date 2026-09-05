<?php

namespace App\Http\Controllers;

use App\Models\EmailSettings;
use App\Models\PartnerInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PartnerController extends Controller
{
    private const MIN_SECONDS_TO_SUBMIT = 3;

    public function create(Request $request)
    {
        $request->session()->put('partner_form_rendered_at', now()->timestamp);

        return view('partner.create');
    }

    public function store(Request $request)
    {
        // ハニーポット：ボットが埋めがちな隠しフィールド。人間には見えない
        if ($request->filled('website')) {
            return redirect()->route('partner.complete');
        }

        // フォーム表示から一定秒数未満での送信はボットとみなす
        $renderedAt = $request->session()->get('partner_form_rendered_at');
        if ($renderedAt && (now()->timestamp - $renderedAt) < self::MIN_SECONDS_TO_SUBMIT) {
            return redirect()->route('partner.complete');
        }

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'message' => 'required|string|max:2000',
        ]);

        $inquiry = PartnerInquiry::create($validated + [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        try {
            $adminEmail = EmailSettings::getValue('admin_email', 'admin@oyazuna.com');

            Mail::raw($this->formatInquiryEmail($validated), function ($message) use ($validated, $adminEmail) {
                $message->from($validated['email'], $validated['company_name'])
                        ->to($adminEmail)
                        ->subject('[オヤズナ 提携相談] ' . $validated['company_name']);
            });
        } catch (\Exception $e) {
            // メール送信に失敗してもDBには保存済みのため、相談自体は受け付けたこととする
        }

        $request->session()->forget('partner_form_rendered_at');

        return redirect()->route('partner.complete');
    }

    public function complete()
    {
        return view('partner.complete');
    }

    private function formatInquiryEmail(array $data): string
    {
        return "
オヤズナ 提携に関するご相談フォームより新しいメッセージが届きました。

【会社名】
{$data['company_name']}

【ご担当者名】
{$data['contact_name']}

【メールアドレス】
{$data['email']}

【電話番号】
{$data['phone']}

【ご相談内容】
{$data['message']}

送信日時: " . now()->format('Y年m月d日 H:i:s') . "
";
    }
}
