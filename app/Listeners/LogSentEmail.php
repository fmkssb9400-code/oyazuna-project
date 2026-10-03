<?php

namespace App\Listeners;

use App\Models\SentEmail;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class LogSentEmail
{
    public function handle(MessageSent $event): void
    {
        // 履歴の記録に失敗しても、メール送信自体は成功扱いのままにする
        try {
            $message = $event->message;
            $from = $message->getFrom()[0] ?? null;
            $subject = (string) $message->getSubject();
            $body = $message->getTextBody() ?? strip_tags((string) $message->getHtmlBody());

            foreach ($message->getTo() as $to) {
                SentEmail::create([
                    'type' => SentEmail::detectType($subject),
                    'from_address' => $from?->getAddress(),
                    'from_name' => $from?->getName() ?: null,
                    'to_address' => $to->getAddress(),
                    'subject' => $subject,
                    'body' => $body,
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Sent email log failed', ['error' => $e->getMessage()]);
        }
    }
}
