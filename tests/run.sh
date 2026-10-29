#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "$0")/.." && pwd)"
compose_file="$repo_root/tests/compose.yml"
project_name="dpt-tests-$$"
wp_version="${DPT_WP_VERSION:-latest}"
dc=(docker compose -p "$project_name" -f "$compose_file")
wp=(php -d memory_limit=512M /usr/local/bin/wp --allow-root)

for required_file in vendor/autoload.php Models/QuranADay/files/english.csv Models/QuranADay/files/bangla.csv; do
    if [[ ! -f "$repo_root/$required_file" ]]; then
        echo "Missing required release file: $required_file" >&2
        exit 1
    fi
done

cleanup() {
    "${dc[@]}" down --volumes --remove-orphans >/dev/null
}
trap cleanup EXIT

"${dc[@]}" up -d db
"${dc[@]}" run --rm wp "${wp[@]}" core download --version="$wp_version"
"${dc[@]}" run --rm wp "${wp[@]}" config create \
    --dbname=wordpress --dbuser=wordpress --dbpass=test-password --dbhost=db
"${dc[@]}" run --rm wp "${wp[@]}" core install \
    --url=http://localhost --title='DPT Tests' \
    --admin_user=dpt-test-admin --admin_password=dpt-test-password \
    --admin_email=test@example.invalid --skip-email
"${dc[@]}" run --rm wp "${wp[@]}" plugin activate dpt
"${dc[@]}" run --rm wp "${wp[@]}" eval-file wp-content/plugins/dpt/tests/integration.php
