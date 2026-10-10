#!/bin/bash
# 本番反映コマンド(本番サーバーへのSSH)の直前にBladeシンタックスチェックを強制する。
# 失敗時は exit 2 で実行をブロックし、stderrの内容がClaudeにフィードバックされる。
set -u
input=$(cat)
cmd=$(printf '%s' "$input" | jq -r '.tool_input.command // empty')

# 本番サーバー(210.131.213.247)へのSSH/SCPのみ対象。読み取り専用の確認系は素通りさせる
case "$cmd" in
  *210.131.213.247*|*oyazuna.com*) ;;
  *) exit 0 ;;
esac
case "$cmd" in
  *"git pull"*|*"artisan migrate"*|*"artisan db:seed"*|*"artisan companies:"*|*"scp "*|*"rsync "*) ;;
  *) exit 0 ;;
esac

cd "${CLAUDE_PROJECT_DIR:-.}" || exit 0

# 1. Bladeシンタックスチェック(CLAUDE.md「デプロイ前の必須テスト」1)
if ! out=$(php artisan view:cache 2>&1); then
  echo "【デプロイ中止】php artisan view:cache が失敗しました。Bladeのエラーを修正してから本番反映してください。" >&2
  echo "$out" | tail -15 >&2
  exit 2
fi

# 1b. view:cacheはBladeを変換するだけでPHP構文は検査しないため、コンパイル済みファイルをphp -lで検査
if ! lint=$(find storage/framework/views -name '*.php' -print0 | xargs -0 -n 20 -P 4 php -l 2>&1 | grep -v '^No syntax errors'); then :; fi
if [ -n "$lint" ]; then
  echo "【デプロイ中止】コンパイル済みBladeにPHP構文エラーがあります。" >&2
  echo "$lint" | head -10 >&2
  exit 2
fi

# 2. git pull で本番反映する場合、未pushのコミットがあると反映されない
case "$cmd" in
  *"git pull"*)
    unpushed=$(git log --oneline @{u}..HEAD 2>/dev/null | wc -l | tr -d ' ')
    if [ "${unpushed:-0}" -gt 0 ]; then
      echo "【デプロイ中止】未pushのコミットが${unpushed}件あります。git pushしてから本番でgit pullしてください。" >&2
      exit 2
    fi
    ;;
esac
exit 0
