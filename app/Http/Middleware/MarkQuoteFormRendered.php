<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 見積もりフォームは /quote 以外（トップ・サイドバー・記事・会社一覧など）からも
 * quote.store に直接送信される。FormSpamGuard は「フォーム表示時刻」のセッション値が
 * ないと送信をボット扱いで握りつぶすため、HTMLページ(GET)を最初に開いた時点で
 * 表示時刻を記録しておく（/quote の表示では FormSpamGuard が上書きする）。
 * Cookieなしで直接POSTするボットは従来どおり弾かれる。
 */
class MarkQuoteFormRendered
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && ! $request->ajax() && $request->hasSession()
            && ! $request->session()->has('quote_form_rendered_at')) {
            $request->session()->put('quote_form_rendered_at', now()->timestamp);
        }

        return $next($request);
    }
}
