# 画像サイズ機能テスト手順

## 1) 管理画面でサイズ変更テスト
1. http://127.0.0.1:8002/admin/articles/1/edit にアクセス
2. 画像をクリックしてサイズツールバー表示
3. 「小」に変更して保存
4. ブラウザコンソールで以下のログを確認：
   - ✅ Trix attributes更新完了
   - 🔄 Trixエディターに変更通知完了
   - ✅ Image size applied

## 2) DB確認コマンド
```bash
php artisan tinker --execute="
\$article = App\Models\Article::where('slug', 'test')->first();
echo 'data-trix-attributes 内容:' . PHP_EOL;
if (preg_match('/data-trix-attributes=\"([^\"]+)\"/', \$article->content, \$matches)) {
    echo html_entity_decode(\$matches[1]) . PHP_EOL;
} else {
    echo '見つかりません' . PHP_EOL;
}
"
```

## 3) フロント出力確認
http://127.0.0.1:8002/news/test にアクセスして、画像を右クリック > 検証で確認：
- `<img ... data-size="sm">` が付いているか
- `<img ... class="img-sm">` が付いているか
- `<figure ... class="img-sm">` が付いているか

## 4) CSS適用確認
ブラウザの開発者ツールで画像の「計算済み」スタイルを確認：
- max-width: 320px（小サイズ）
- max-width: 600px（中サイズ）  
- max-width: 900px（大サイズ）

## 5) ログ確認
```bash
tail -f storage/logs/laravel.log | grep "Image size"
```

## 期待する動作
1. 管理画面：サイズ変更後、data-trix-attributesにimageSize保存
2. 保存処理：data-trix-attributesからdata-size属性に変換
3. フロント表示：CSSでサイズが反映される
4. 既存記事：sizeが無い場合は自動的にmd（中サイズ）適用