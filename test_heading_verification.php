<?php

// H2/H3タグの保存確認用テストスクリプト
// 実行: php artisan tinker < test_heading_verification.php

use App\Models\Article;

echo "=== H2/H3タグ保存確認テスト ===\n";

// 1. テスト用記事を作成（または既存記事を使用）
$testContent = '<h2>テスト見出し2</h2><p>これはテスト段落です。</p><h3>テスト見出し3</h3><p>別のテスト段落です。</p>';

$article = Article::create([
    'title' => 'H2/H3テスト記事',
    'slug' => 'h2-h3-test-' . time(),
    'content' => $testContent,
    'is_published' => false
]);

echo "記事ID: " . $article->id . "\n";
echo "保存前のコンテンツ:\n" . $testContent . "\n\n";

// 2. 保存後のコンテンツを確認
$article->refresh();
echo "保存後のコンテンツ:\n" . $article->content . "\n\n";

// 3. H2/H3タグが保持されているかチェック
$hasH2 = strpos($article->content, '<h2>') !== false;
$hasH3 = strpos($article->content, '<h3>') !== false;

echo "H2タグが保持されているか: " . ($hasH2 ? "✅ Yes" : "❌ No") . "\n";
echo "H3タグが保持されているか: " . ($hasH3 ? "✅ Yes" : "❌ No") . "\n";

// 4. 既存記事でH2/H3を含むものを検索
$articlesWithH2 = Article::where('content', 'LIKE', '%<h2%')->count();
$articlesWithH3 = Article::where('content', 'LIKE', '%<h3%')->count();

echo "\n=== 既存記事の見出しタグ使用状況 ===\n";
echo "H2タグを含む記事数: " . $articlesWithH2 . "\n";
echo "H3タグを含む記事数: " . $articlesWithH3 . "\n";

// 5. テスト記事を削除
$article->delete();
echo "\nテスト記事を削除しました。\n";

echo "\n=== テスト完了 ===\n";