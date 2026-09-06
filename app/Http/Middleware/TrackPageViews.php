<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PageView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class TrackPageViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // GET リクエストのみ追跡
        if ($request->isMethod('get') && $response->getStatusCode() === 200) {
            $this->trackPageView($request);
        }

        return $response;
    }

    private function trackPageView(Request $request): void
    {
        $url = $request->fullUrl();
        $routeName = Route::currentRouteName();

        // ボット・クローラー・curl等のツールを除外
        $userAgent = $request->userAgent() ?? '';
        if ($this->isBot($userAgent)) {
            return;
        }

        // 管理画面は除外
        if (str_starts_with($request->path(), 'admin')) {
            return;
        }

        // 管理者としてログイン中のアクセス（自分自身での確認作業等）は除外
        if (Auth::check()) {
            return;
        }

        $pageType = null;
        $articleId = null;

        // ルート名に基づいてページタイプを判定
        if ($routeName === 'news.show') {
            $pageType = 'article';
            // 記事IDを取得（routeパラメーターから）
            $article = $request->route('article');
            if ($article) {
                $articleId = is_object($article) ? $article->id : $article;
            }
        } elseif ($routeName === 'home') {
            $pageType = 'home';
        } elseif (str_starts_with($routeName ?? '', 'companies')) {
            $pageType = 'companies';
        } elseif (str_starts_with($routeName ?? '', 'news')) {
            $pageType = 'news';
        } elseif ($routeName === 'partner.create') {
            $pageType = 'partner';
        }

        // 同じセッション、同じページの重複アクセスは1時間以内は記録しない
        $sessionId = $request->session()->getId();
        $recentView = PageView::where('session_id', $sessionId)
            ->where('url', $url)
            ->where('viewed_at', '>', now()->subHour())
            ->exists();

        if (!$recentView) {
            PageView::create([
                'url' => $url,
                'page_type' => $pageType,
                'article_id' => $articleId,
                'user_agent' => $userAgent,
                'ip_address' => $request->ip(),
                'session_id' => $sessionId,
                'viewed_at' => now(),
            ]);
        }
    }

    private function isBot(string $userAgent): bool
    {
        $bots = [
            'googlebot',
            'bingbot',
            'slurp',
            'duckduckbot',
            'baiduspider',
            'yandexbot',
            'facebookexternalhit',
            'twitterbot',
            'rogerbot',
            'linkedinbot',
            'embedly',
            'quora link preview',
            'showyoubot',
            'outbrain',
            'pinterest',
            'developers.google.com/+/web/snippet',
            // CLIツール・APIクライアント（動作確認・監視等の非人間アクセス）
            'curl/',
            'wget/',
            'python-requests',
            'python-urllib',
            'postmanruntime',
            'httpie',
            'go-http-client',
            'okhttp',
            'node-fetch',
            'axios/',
            'insomnia',
        ];

        $userAgent = strtolower($userAgent);
        
        foreach ($bots as $bot) {
            if (str_contains($userAgent, $bot)) {
                return true;
            }
        }

        return false;
    }
}
