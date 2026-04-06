# クリモバ（高所窓ガラス清掃業者比較・一括見積もりサイト）MVP 実装完了

## プロジェクト概要
高所の窓ガラス清掃業者（ロープアクセス対応）の比較・一括見積もりサイト。
業者検索、比較、一括見積もり依頼、管理画面（Filament）を実装済み。

## 実装された機能

### フロントエンド
- ✅ トップページ（/）- 検索フォーム
- ✅ 業者一覧（/companies）- 検索・フィルタ・ソート
- ✅ 業者詳細（/companies/{slug}）
- ✅ 比較ページ（/compare）- 複数社比較テーブル
- ✅ 一括見積フォーム（/quote）- 見積依頼送信
- ✅ 完了ページ（/quote/complete）

### バックエンド
- ✅ 正規化されたDB設計（pivot テーブル使用）
- ✅ 検索・フィルタリング機能
- ✅ セッション式比較機能
- ✅ 一括見積もり機能
- ✅ 二重送信防止（30分間レート制限）
- ✅ 案件追跡（public_id 自動生成）

### 管理画面（Filament）
- ✅ 業者管理（CRUD、対応エリア/工法/建物種別/サービス種別）
- ✅ 見積依頼管理（一覧・詳細・ステータス更新）
- ✅ 公開/非公開切り替え

## データベース構成

### コアテーブル
- `companies` - 業者情報
- `prefectures` - 都道府県マスタ
- `service_methods` - 工法マスタ（ロープ/ゴンドラ/高所作業車/足場）
- `building_types` - 建物種別マスタ（オフィス/マンション/店舗/工場/その他）
- `service_categories` - サービス種別マスタ（窓/外壁/看板/その他）

### Pivotテーブル（多対多関係）
- `company_prefecture` - 業者⇔対応エリア
- `company_service_method` - 業者⇔対応工法
- `building_type_company` - 業者⇔対応建物種別
- `company_service_category` - 業者⇔対応サービス

### 見積関連
- `quote_requests` - 見積依頼（案件番号、依頼者情報、条件）
- `quote_recipients` - 送信先（どの業者に送ったか、送信ステータス）
- `company_assets` - 業者の画像（ロゴ/実績写真）

## セットアップ手順

### 1. 依存関係インストール
```bash
composer install
npm install && npm run dev
```

### 2. 環境設定
```bash
cp .env.example .env
php artisan key:generate
```

### 3. データベース準備
```bash
touch database/database.sqlite  # SQLite使用の場合
php artisan migrate --seed
```

### 4. 管理者ユーザー作成
```bash
php artisan tinker --execute="App\Models\User::create(['name' => 'Admin', 'email' => 'admin@kurimoba.com', 'password' => bcrypt('password')]);"
```

### 5. 開発サーバー起動
```bash
php artisan serve
```

## アクセス先
- **フロントサイト**: http://127.0.0.1:8000
- **管理画面**: http://127.0.0.1:8000/admin
  - メール: `admin@kurimoba.com`
  - パスワード: `password`

## 主要なURL

### フロントエンド
- `/` - トップページ（検索フォーム）
- `/companies` - 業者一覧（検索結果）
- `/companies/{slug}` - 業者詳細
- `/compare` - 比較ページ
- `/quote` - 一括見積フォーム
- `/quote/complete` - 完了ページ

### 管理画面（Filament）
- `/admin` - 管理トップ
- `/admin/companies` - 業者管理
- `/admin/quote-requests` - 見積依頼管理

## テストデータ
以下の業者データが初期登録されています：
1. **東京ロープアクセス** - 東京・神奈川対応、50階まで、緊急対応可
2. **スカイクリーン大阪** - 関西3府県対応、30階まで
3. **全国ビル清掃サービス** - 全国対応、無制限、緊急対応可

## 今後の拡張予定

### Phase 2（集客・CV改善）
- エリアLP（/area/tokyo）
- 口コミ機能
- reCAPTCHA対応
- 実際のメール送信機能

### Phase 3（業者向けSaaS化）
- 業者ログイン
- 案件管理・ステータス更新
- チャット機能

## .env 設定が必要な項目

### メール送信（将来実装時）
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@kurimoba.com
MAIL_FROM_NAME="クリモバ"
```

### ファイルストレージ（画像アップロード用）
```env
FILESYSTEM_DISK=public
```

## デプロイメント要件
- PHP 8.1+
- MySQL 8.0+ または PostgreSQL 13+
- Redis（セッション・キャッシュ推奨）
- SMTP サーバー（見積もりメール送信用）

## 注意事項
- 現在のメール送信は仮実装（送信済みマークのみ）
- 実際の運用前にSMTP設定とメールテンプレートの実装が必要
- セキュリティ設定（CSRF、XSS対策）は Laravel デフォルトを使用
- 画像アップロード機能は管理画面で実装予定