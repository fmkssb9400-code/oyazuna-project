# 🔍 画像サイズ問題 強化版デバッグガイド

## 🚀 新しい機能
- **Trix内部状態の直接操作**
- **フォーム送信時の強制同期**  
- **変更検出とリマインダー通知**
- **詳細なデバッグログ**

## 📋 テスト手順

### 1) 管理画面テスト
```
1. http://127.0.0.1:8002/admin/articles/1/edit にアクセス
2. ブラウザのコンソールを開く（F12 > Console）
3. 画像をクリックしてサイズツールバー表示
4. 「小」サイズに変更
5. 右上に「💾 画像サイズを変更しました。保存してください。」通知が表示される
6. コンソールで以下を確認：
   ✅ Trix attributes更新完了
   🎯 Trixエディター検出 - 直接操作開始  
   🎉 Trix完全同期処理完了
7. 保存ボタンをクリック
8. コンソールで「💾 保存ボタンクリック検出」を確認
```

### 2) DB状態確認
```bash
php artisan tinker --execute="
echo '=== 最新記事のdata-trix-attributes確認 ===' . PHP_EOL;
\$article = App\Models\Article::where('slug', 'test')->first();
\$content = \$article->content;
if (preg_match('/data-trix-attributes=\"([^\"]+)\"/', \$content, \$matches)) {
    \$decoded = html_entity_decode(\$matches[1]);
    echo 'data-trix-attributes: ' . \$decoded . PHP_EOL;
    \$json = json_decode(\$decoded, true);
    if (isset(\$json['imageSize'])) {
        echo '✅ imageSize発見: ' . \$json['imageSize'] . PHP_EOL;
    } else {
        echo '❌ imageSize見つかりません' . PHP_EOL;
    }
} else {
    echo '❌ data-trix-attributes見つかりません' . PHP_EOL;
}
"
```

### 3) ログ監視
```bash
# リアルタイムログ監視
tail -f storage/logs/laravel.log | grep -E "(Article Save Debug|Image size|Trix)"

# または過去のログ確認
grep -A 5 -B 5 "Article Save Debug Start" storage/logs/laravel.log | tail -20
```

### 4) フロント確認
```
1. http://127.0.0.1:8002/news/test にアクセス  
2. 画像を右クリック > 検証
3. 以下を確認：
   <img ... data-size="sm" class="img-sm" ...>
   <figure ... class="img-sm" ...>
```

## 🔧 期待する動作の流れ

```
1. 画像クリック → サイズ選択 → data-size-modified="true" 追加
2. updateTrixAttributes() → data-trix-attributes 更新
3. triggerEditorUpdate() → Trix内部状態同期  
4. showSaveReminder() → 通知表示
5. 保存ボタンクリック → フォーム送信検出
6. mutateFormDataBeforeSave() → processImageSizes() 実行
7. data-trix-attributes → data-size属性 + class変換
8. DB保存 → content フィールド更新
9. フロント表示 → CSS適用
```

## ⚠️ トラブルシューティング

### コンソールエラーがある場合
```javascript
// ブラウザコンソールで手動実行
const img = document.querySelector('.ProseMirror img');
if (img) {
    console.log('Image found:', img.src);
    // 手動でサイズ変更テスト
    changeImageSize(img, 'sm'); 
}
```

### 保存されない場合
```bash
# 権限確認
ls -la storage/logs/

# Trixエディター確認
php artisan tinker --execute="
echo 'Trix editor elements:' . PHP_EOL;
// JavaScriptコンソールで: document.querySelector('trix-editor')
"
```

## 🎯 成功判定

✅ **管理画面**: 画像サイズ変更 → 通知表示 → 保存
✅ **DB**: data-trix-attributes に imageSize 保存  
✅ **処理**: processImageSizes でdata-size変換
✅ **フロント**: img[data-size="sm"] でCSS適用
✅ **表示**: 画像が実際に小さくなる
✅ **永続化**: 再編集時もサイズが維持される