# 記事本文消失問題 復旧確認手順

## 前提
この手順は、記事本文消失問題の復旧作業が正しく機能していることを確認するためのものです。

## 1. データベース確認（Tinker）

### 既存記事のデータ確認
```bash
php artisan tinker
```

```php
// すべての記事の状況を確認
$articles = \App\Models\Article::all();
foreach ($articles as $article) {
    echo "ID: {$article->id}\n";
    echo "タイトル: {$article->title}\n";
    echo "content: " . (!empty($article->content) ? "あり (". strlen($article->content) ."文字)" : "なし") . "\n";
    echo "content_json: " . (!empty($article->content_json) ? "あり" : "なし") . "\n";
    echo "content_html: " . (!empty($article->content_html) ? "あり (". strlen($article->content_html) ."文字)" : "なし") . "\n";
    echo "タイプ: " . ($article->usesBlockEditor() ? "ブロック" : ($article->usesLegacyEditor() ? "レガシー" : "不明")) . "\n";
    echo "rendered_content: " . (!empty($article->rendered_content) ? "OK (". strlen($article->rendered_content) ."文字)" : "エラー") . "\n";
    echo "---\n";
}
```

### 期待結果
- 既存記事（content のみ）：「レガシー」と表示され、rendered_content が表示される
- 新記事（content_json あり）：「ブロック」と表示される
- すべての記事で rendered_content が「OK」と表示される

## 2. 管理画面での確認

### A. 既存記事の編集画面確認
1. http://127.0.0.1:8002/admin にアクセス
2. 記事管理から既存記事を選択
3. 本文欄に「本文（従来エディタ）」が表示されることを確認
4. 既存の本文内容が正しく表示されることを確認
5. 「ブロック形式に移行」ボタンが表示されることを確認

### B. 既存記事の保存安全性確認
1. 既存記事を開く
2. タイトルなど本文以外の項目を変更
3. 保存する
4. 本文が消失していないことを確認
5. フロント画面でも本文が表示されることを確認

### C. 新規記事の作成確認
1. 「記事を作成」をクリック
2. 本文欄に「本文（ブロックエディタ）」が表示されることを確認
3. ブロックエディタが正常に動作することを確認
4. 保存して正常に動作することを確認

### D. 移行機能の確認
1. 既存記事の編集画面で「ブロック形式に移行」をクリック
2. 確認ダイアログが表示される
3. 実行すると、ブロックエディタに切り替わる
4. 元のテキスト内容が段落ブロックとして表示される
5. 保存して正常に動作することを確認

## 3. フロント表示確認

### 記事一覧ページ
```bash
curl -s http://127.0.0.1:8002/news | grep -o '<article[^>]*>.*</article>' | wc -l
```
期待結果: 記事数が表示される（0でない）

### 個別記事ページ
```bash
# 最初の記事のslugを取得して確認
php artisan tinker
```
```php
$article = \App\Models\Article::first();
echo "http://127.0.0.1:8002/news/{$article->slug}\n";
exit;
```

ブラウザで上記URLにアクセスして本文が表示されることを確認

## 4. 緊急時のデータ復旧

### 万が一データが消失した場合の確認方法
```php
php artisan tinker
```

```php
// 問題のある記事を特定
$problematic = \App\Models\Article::where('content', '')->where('content_json', null)->get();
echo "問題のある記事数: " . $problematic->count() . "\n";

// 各記事の詳細確認
foreach ($problematic as $article) {
    echo "ID: {$article->id}, タイトル: {$article->title}\n";
    echo "最終更新: {$article->updated_at}\n";
}
```

### ログ確認
```bash
tail -f storage/logs/laravel.log | grep -i "content\|article"
```

## 5. 成功チェックリスト

- [ ] 既存記事の管理画面で本文が表示される
- [ ] 既存記事を保存しても本文が消失しない  
- [ ] 新規記事でブロックエディタが動作する
- [ ] 移行機能が正常に動作する
- [ ] フロント表示で全記事の本文が表示される
- [ ] ログにエラーが出ていない

## 6. トラブルシューティング

### 問題: 管理画面で本文が空白
原因: ArticleResourceのvisible条件の問題
解決: `$record->usesLegacyEditor()` を確認

### 問題: 保存時に本文が消える
原因: mutateFormDataBeforeSave の動作不良
解決: ログを確認して保存ガードが動作しているかチェック

### 問題: フロント表示で本文が表示されない
原因: rendered_contentアクセサーの問題
解決: `$article->rendered_content` を直接確認

## 7. 定期チェック（推奨）

### 毎日
```bash
php artisan schedule:run --ansi | grep "article"
```

### 週次
```php
php artisan tinker
```
```php
// 全記事に本文があることを確認
$total = \App\Models\Article::count();
$with_content = \App\Models\Article::where(function($q) {
    $q->whereNotNull('content')->orWhereNotNull('content_json');
})->count();

echo "総記事数: $total\n";
echo "本文あり: $with_content\n";
echo "安全性: " . ($total === $with_content ? "OK" : "警告") . "\n";
```

この手順で問題がないことを確認できれば、記事本文消失問題の復旧は完了です。