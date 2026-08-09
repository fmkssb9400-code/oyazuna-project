<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailSettings;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        try {
            // Send email notification
            $adminEmail = EmailSettings::getValue('admin_email', 'admin@oyazuna.com');
            
            Mail::raw($this->formatContactEmail($validated), function ($message) use ($validated, $adminEmail) {
                $message->from($validated['email'], $validated['name'])
                        ->to($adminEmail)
                        ->subject('[オヤズナお問い合わせ] ' . $validated['subject']);
            });

            return redirect()->route('contact.complete');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'メールの送信に失敗しました。しばらく時間をおいて再度お試しください。']);
        }
    }

    public function complete()
    {
        return view('contact.complete');
    }

    private function formatContactEmail($data)
    {
        return "
オヤズナお問い合わせフォームより新しいメッセージが届きました。

【お名前】
{$data['name']}

【メールアドレス】
{$data['email']}

【電話番号】
" . ($data['phone'] ?? '未記入') . "

【件名】
{$data['subject']}

【メッセージ内容】
{$data['message']}

送信日時: " . now()->format('Y年m月d日 H:i:s') . "
";
    }
}