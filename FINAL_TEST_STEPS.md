# 🔥 最終修正版テスト手順

## 🚨 修正内容まとめ
1. **JavaScript**: Trixの内部HTMLを直接書き換えて確実に保存
2. **PHP**: DOMDocumentエラーを修正し、正規表現ベースで処理  
3. **デバッグ**: 全プロセスを詳細ログで追跡可能

## 📋 テスト実行手順

### STEP 1: 管理画面でサイズ変更
```
1. http://127.0.0.1:8002/admin/articles/1/edit
2. ブラウザコンソールを開く (F12 → Console)
3. 画像をクリック → 「小」ボタンクリック
4. コンソールで以下を確認：
   🚀 === 画像サイズ変更処理開始 ===
   🎯 updateTrixAttributes開始: sm
   📖 既存attributes: {caption: "...", ...}
   📝 新しいattributes: {caption: "...", imageSize: "sm"}
   ✅ DOM属性更新完了
   🔧 Trix内部HTML直接更新開始
   ✅ Trix内部HTML更新完了
   ✅ 隠しフィールド直接更新
   🔍 最終確認 - data-trix-attributes: {...}
   ✅ 確認成功: imageSize = sm
```

### STEP 2: 保存実行
```
5. 「Save changes」ボタンクリック
6. コンソールで「💾 保存ボタンクリック検出」確認
```

### STEP 3: DB確認
```bash
php artisan tinker --execute="
\$article = App\Models\Article::where('slug', 'test')->first();
echo 'Updated: ' . \$article->updated_at . PHP_EOL;
if (preg_match('/data-trix-attributes=\"([^\"]+)\"/', \$article->content, \$matches)) {
    \$decoded = html_entity_decode(\$matches[1]);
    echo 'Attributes: ' . \$decoded . PHP_EOL;
    \$json = json_decode(\$decoded, true);
    echo 'ImageSize: ' . (\$json['imageSize'] ?? 'なし') . PHP_EOL;
} else {
    echo 'data-trix-attributes 見つかりません' . PHP_EOL;
}
"
```

### STEP 4: ログ確認  
```bash
tail -f storage/logs/laravel.log | grep -E "(Article Save Debug|Found imageSize|Image size applied)"
```

### STEP 5: フロント確認
```
1. http://127.0.0.1:8002/news/test
2. 画像を右クリック → 検証
3. 確認項目：
   <img ... data-size="sm" class="img-sm" ...>
   <figure ... class="img-sm" ...>
4. 計算済みスタイル確認:
   max-width: 320px (小サイズの場合)
```

## ✅ 成功判定チェックリスト

- [ ] **コンソール**: "✅ 確認成功: imageSize = sm" が表示される
- [ ] **DB**: data-trix-attributesに `"imageSize":"sm"` が保存される  
- [ ] **ログ**: "Found imageSize in trix-attributes" "Image size applied via regex"
- [ ] **HTML**: `data-size="sm"` と `class="img-sm"` が出力される
- [ ] **表示**: 画像が実際に小さくなる (320px)
- [ ] **永続化**: 再編集時にサイズが維持される

## 🔧 もし失敗した場合

### JavaScriptが動かない場合
```javascript
// ブラウザコンソールで手動実行
const img = document.querySelector('.trix-content img');
if (img) {
    console.log('Image found');
    changeImageSize(img, 'sm');
}
```

### 保存されない場合
```bash
# 権限チェック
ls -la storage/logs/
# キャッシュクリア
php artisan config:clear
php artisan view:clear
```

## 🎯 今回の根本修正ポイント

**以前の問題**: JavaScriptの変更がTrixの内部状態に反映されていない
**今回の解決策**: 
1. `trixContent.innerHTML` を直接書き換え
2. 正規表現で `data-trix-attributes` を確実に更新
3. 隠しフィールドに直接反映
4. サーバー側で正規表現ベース処理に変更

**これで絶対に保存されるはずです！**