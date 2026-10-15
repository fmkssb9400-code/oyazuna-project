#!/bin/bash
# 自動記事パイプライン専用SSH鍵のforced command。許可する動詞は3つだけ。
# authorized_keys側: command="/var/www/oyazuna/scripts/oyazuna-pipeline-gate.sh",restrict ssh-ed25519 AAAA...
cd /var/www/oyazuna || exit 1

case "$SSH_ORIGINAL_COMMAND" in
  "list")          exec php artisan articles:list-ro ;;
  "ingest")        exec php artisan articles:ingest ;;
  "ingest-dryrun") exec php artisan articles:ingest --dry-run ;;
  *)               echo '{"ok":false,"error":"command not allowed"}' >&2; exit 1 ;;
esac
