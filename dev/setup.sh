#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "$0")/.." && pwd)"
compose_file="$repo_root/dev/compose.yml"
dc=(docker compose -p dpt-demo -f "$compose_file")
wp=("${dc[@]}" run --rm wp php -d memory_limit=512M /usr/local/bin/wp --allow-root)

for required_file in vendor/autoload.php Models/QuranADay/files/english.csv Models/QuranADay/files/bangla.csv; do
    if [[ ! -f "$repo_root/$required_file" ]]; then
        echo "Missing required plugin file: $required_file" >&2
        exit 1
    fi
done

"${dc[@]}" up -d --pull always db wordpress
if ! "${dc[@]}" exec -T wordpress php -r 'exit(version_compare(PHP_VERSION, "8.4.27", "<") ? 1 : 0);'; then
    echo 'The demo PHP image is older than the October 2026 security fixes.' >&2
    "${dc[@]}" down
    exit 1
fi

if ! "${wp[@]}" core is-installed >/dev/null 2>&1; then
    "${wp[@]}" core install \
        --url=http://127.0.0.1:8080 \
        --title='Daily Prayer Time demo' \
        --admin_user=dptdemo \
        --admin_password=local-demo-only \
        --admin_email=demo@example.invalid \
        --skip-email
fi

"${wp[@]}" option update timezone_string Europe/London
"${wp[@]}" plugin activate dpt
"${wp[@]}" eval 'dpt_fs()->skip_connection();'
"${wp[@]}" eval-file wp-content/plugins/dpt/dev/seed.php

echo 'Open http://127.0.0.1:8080/ to view the shortcode gallery.'
echo 'Admin: http://127.0.0.1:8080/wp-admin/ (dptdemo / local-demo-only)'
