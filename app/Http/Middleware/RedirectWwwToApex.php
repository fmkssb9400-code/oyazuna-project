<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectWwwToApex
{
    /**
     * www.oyazuna.com へのアクセスを oyazuna.com へ301リダイレクトする。
     * nginx側でwww/非wwwが同一server blockで処理されており正規化されていないため、
     * アプリ層でホストを統一する（GSCがwww版を「重複」と判定し、非www版の一部ページが
     * クロールされない原因になっていた）。
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->getHost() === 'www.oyazuna.com') {
            $url = 'https://oyazuna.com' . $request->getRequestUri();

            return redirect()->to($url, 301);
        }

        return $next($request);
    }
}
