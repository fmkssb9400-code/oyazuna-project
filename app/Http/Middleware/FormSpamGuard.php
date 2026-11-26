<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 問い合わせ系フォームのボット対策（ハニーポット＋表示から送信までの最短秒数）。
 * ルート指定: ->middleware('form.guard:contact')  ※GET(表示)とPOST(送信)の両方に付ける。
 * ボットと判定した場合はエラーを返さず完了ページへ流す（ボットに学習させない）。
 */
class FormSpamGuard
{
    private const MIN_SECONDS_TO_SUBMIT = 3;

    public function handle(Request $request, Closure $next, string $form): Response
    {
        $key = "{$form}_form_rendered_at";

        if ($request->isMethod('GET')) {
            $request->session()->put($key, now()->timestamp);
            return $next($request);
        }

        $renderedAt = $request->session()->get($key);
        $isBot = $request->filled('website')
            || ! $renderedAt
            || (now()->timestamp - $renderedAt) < self::MIN_SECONDS_TO_SUBMIT;

        if ($isBot) {
            logger()->info("FormSpamGuard blocked {$form}", ['ip' => $request->ip(), 'ua' => $request->userAgent()]);
            return redirect()->route("{$form}.complete");
        }

        return $next($request);
    }
}
