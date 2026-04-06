<?php

// H2/H3保存確認用テストスクリプト
echo "=== H2/H3 DB保存テスト ===\n";

use App\Models\Article;

// テスト用コンテンツ
$testContent = '<h2>テスト見出し2</h2><p>これは見出し2の下の段落です。</p><h3>テスト見出し3</h3><p>これは見出し3の下の段落です。</p>';

echo "元のコンテンツ:\n";
echo $testContent . "\n\n";

// 記事作成
$article = Article::create([
    'title' => 'H2H3テスト記事_' . time(),
    'slug' => 'h2h3-test-' . time(),
    'content' => $testContent,
    'is_published' => false,
    'published_at' => now()
]);

echo "記事作成完了 ID: " . $article->id . "\n";

// 保存後のコンテンツ確認
$article->refresh();
echo "保存後のコンテンツ:\n";
echo $article->content . "\n\n";

// H2/H3タグの存在確認
$hasH2 = strpos($article->content, '<h2>') !== false;
$hasH3 = strpos($article->content, '<h3>') !== false;

echo "結果:\n";
echo "- H2タグが保持されているか: " . ($hasH2 ? "✅ YES" : "❌ NO") . "\n";
echo "- H3タグが保持されているか: " . ($hasH3 ? "✅ YES" : "❌ NO") . "\n";

// 差分表示
if ($testContent !== $article->content) {
    echo "\n⚠️ コンテンツが変更されました:\n";
    echo "変更前: " . $testContent . "\n";
    echo "変更後: " . $article->content . "\n";
} else {
    echo "\n✅ コンテンツは変更されずに保存されました\n";
}

// テスト記事削除
$article->delete();
echo "\nテスト記事を削除しました\n";

echo "=== テスト終了 ===\n";