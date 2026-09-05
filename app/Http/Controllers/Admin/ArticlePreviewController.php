<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class ArticlePreviewController extends Controller
{
    public function show(string $token)
    {
        $data = Cache::get("article_preview:{$token}");

        if (!$data) {
            abort(404, 'プレビューの有効期限が切れました。管理画面で再度プレビューボタンを押してください。');
        }

        $featuredImageUrl = $data['featured_image_url'] ?? null;
        $supervisorAvatarUrl = $data['supervisor_avatar_url'] ?? null;

        $article = new class extends Article {
            public ?string $previewFeaturedImageUrl = null;

            public ?string $previewSupervisorAvatarUrl = null;

            public function getFeaturedImageUrlAttribute()
            {
                return $this->previewFeaturedImageUrl;
            }
        };

        $article->forceFill(collect($data)->except(['featured_image_url', 'supervisor_avatar_url', 'id'])->toArray());
        $article->id = $data['id'] ?? 0;
        $article->previewFeaturedImageUrl = $featuredImageUrl;
        $article->previewSupervisorAvatarUrl = $supervisorAvatarUrl;
        $article->published_at = $article->published_at ?? now();

        $featuredArticles = new Collection();
        $isPreview = true;

        return view('news.show', compact('article', 'featuredArticles', 'isPreview'));
    }
}
