---
description: oyazuna記事を数日分まとめて「選定→執筆→ファクトチェック→工程チェック→予約投稿」する(引数: 本数。省略時は空き枠の数、最大14)
---

oyazunaの記事自動パイプラインを実行する。引数: `$ARGUMENTS`（本数。空なら空き予約枠の数）。

## 大原則
- 本番へのアクセスは**制限鍵のみ**: `ssh -i ~/.ssh/oyazuna_pipeline_restricted -o IdentitiesOnly=yes -o BatchMode=yes moriseiya@210.131.213.247 <list|ingest-dryrun|ingest>`（`oyazuna_prod`鍵は使わない）
- 各記事は **ファクトチェックに合格しない限り投稿しない**。通らなければ破棄して理由を報告する(無理に通さない)
- 差し戻しは1記事につき最大2回。それでも不合格なら破棄し、代わりの候補で1回だけ補充を試みる
- 公開済み・予約中の記事は一切変更しない(作成のみ)

## 手順
1. **空き枠の算出**: `list` で全記事を取得し、`published_at_utc` が未来の記事＝使用済み枠。今から7日先までのJST 6:00/12:00のうち、現在時刻+1時間以降で未使用の枠を古い順に並べる。本数は `$ARGUMENTS`（空なら空き枠数、上限14）。空き枠が足りなければその数まで
2. **選定**: `article-selector` に、既存記事一覧(slug/title)・必要本数+2件(予備)を渡して候補を取得。`accepted` が足りなければ「候補不足」として足りた分だけで進める(無理に水増ししない)
3. **記事ごとに以下を実行**（作業ディレクトリ: `.claude/pipeline-work/{YYYYMMDD}/{slug}/`）
   1. `article-writer` に、テーマ・ペルソナ・テールワード・fact_risks・作業ディレクトリ・「同時作成の他記事のタイトル/見出し」を渡して `body.html` `meta.json` `sources.json` を作らせる
   2. `article-factchecker` に `body.html` を渡して独立検証。`fail` なら指摘(wrong/unverifiable)をそのまま `article-writer` に渡して修正させ、再検証(最大2回)
   3. **工程チェック**: `content-auditor` に、本文字数(5,000字以上)・title/excerptの文字数と個別性・既存記事との文言重複・禁止要素(黄色span・角丸・on*属性)・内部リンク2つ以上・`/quote`リンクの有無を確認させる。不合格はwriterへ差し戻し(上の2回に含める)
   4. **投稿用JSON組み立て**: `meta.json` + `body.html` + 公開日時(`YYYY-MM-DDTHH:MM:SS+09:00`) を `python3` で `payload.json` にまとめる（`title,slug,excerpt,tags,content,published_at`）
   5. `ingest-dryrun` で検証 → 合格なら `ingest` で保存。出力の `ok:true` と `id` を確認する
4. **台帳追記**: 保存できた記事を `oyazuna_article_registry` メモリに1行ずつ追記（タイトル・テールワード・ペルソナ・slug・Article ID・「予約公開(JST日時)」・作成日）
5. **報告**（日本語・要点のみ）: 表で「slug / タイトル / 公開予定(JST) / 差し戻し回数 / ファクトチェック結果」。破棄した記事は理由つきで列挙。ファクトチェックで見つかった誤りと直した内容も1行ずつ

## 報告に必ず添える注意
- 公開前に止めたい記事は、管理画面(Filament)で該当記事を非公開にすれば公開されない
- 本日(実行時点)までの予約済み記事数と、次回まとめ作成の目安日
