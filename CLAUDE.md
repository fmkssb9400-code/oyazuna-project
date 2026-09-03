## 会話言語

会話は日本語で行うこと

## 業界知識ベース（自動読み込み）

高所ロープ作業（無足場工法）・ビルメンテナンス・外壁塗装/補修/調査・鳥害対策・看板・雨漏り調査業界の知識を調査済み。SEO記事・比較コンテンツ・営業資料・事業戦略の相談時はこれを踏まえること。詳細な出典URL付き調査ファイルは`docs/industry-knowledge/`配下（工法・安全資格／市場・業界構造／7サービス別実務知識／SEO・検索行動の4本）。

@docs/INDUSTRY_KNOWLEDGE.md

## 本番サイト情報

- 本番URL: https://oyazuna.com
- 本番サーバー: IP `210.131.213.247`（同じサーバーに「こせんば」プロジェクトも同居）
- ディレクトリ: `/var/www/oyazuna`（`www-data:www-data`所有）
- SSH接続方法・鍵の登録状況など詳細はmemory（`oyazuna_prod_server_access`）を参照
- Webサーバーは**nginx**（Apacheではない）。`public/.htaccess`に書いたgzip圧縮・キャッシュヘッダー設定（mod_deflate/mod_expires）は本番では効かない。同様の設定が必要な場合はnginxのserver block設定側で対応すること

## 本番反映時の承認の取り方【重要】

Auto Mode時、SSHで本番サーバーへ`git pull`する等の本番反映コマンドは、チャットの自由文での確認（「進めてよいですか？」→「お願いします」）だけだと、Claude Codeのauto mode classifierに理由不明のままブロックされることがある。

**対処法**: 本番反映の直前に`AskUserQuestion`ツールで選択式の確認を取ってから実行すること。この形式なら通ることを確認済み（2026-09-03）。同じSSHコマンドでも、自由文確認では拒否され、AskUserQuestionでの構造化承認では通った。

## デプロイ前の必須テスト

**重要**: 本番デプロイ前に必ず以下を実行すること

### 1. Bladeシンタックスチェック
```bash
php artisan view:cache
```
エラーが出たら修正してからデプロイ

### 2. 本番でのキャッシュクリア（デプロイ後）
```bash
# 権限問題でsudoが使えない場合
php -r "array_map('unlink', glob('storage/framework/views/*.php'));"
php artisan view:clear
php artisan cache:clear
```

### 3. モバイル（スマホ）表示の確認【必須】

**画面まわりの変更（新規ページ・レイアウト変更・表やカードの追加など）をしたら、PC版だけでなく必ずスマホ幅でも見た目を確認すること。**

- agent-browserで `agent-browser set device "iPhone 14"` のようにモバイル端末をエミュレートしてスクリーンショットを撮り、崩れ・はみ出し・見切れがないか確認する
- 特に表（table）は横幅が画面を超えて横スクロールが必要になりやすい。`overflow-x-auto`だけでは気づかれないことがあるため、必要なら「→ 横にスクロールできます」のようなヒントテキストをスマホ表示時（`sm:hidden`）に出す
- 確認だけでなく、崩れていた場合はその場で修正してから完了とすること
