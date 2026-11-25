#!/usr/bin/env bash
set -euo pipefail

if [[ $# -ne 1 ]]; then
    echo "Usage: bash scripts/build-svn-package.sh OUTPUT_DIRECTORY" >&2
    exit 2
fi

repo_root="$(cd "$(dirname "$0")/.." && pwd)"
output_parent="$(cd "$(dirname "$1")" && pwd)"
output_dir="$output_parent/$(basename "$1")"
if [[ -e "$output_dir" ]]; then
    echo "Output directory already exists: $output_dir" >&2
    exit 1
fi

plugin_version="$(sed -n 's/^Version: //p' "$repo_root/daily-prayer-time.php" | head -n 1)"
stable_tag="$(sed -n 's/^Stable tag: //p' "$repo_root/readme.txt" | head -n 1)"
if [[ -z "$plugin_version" || "$plugin_version" != "$stable_tag" ]]; then
    echo "Plugin version and readme Stable tag must match." >&2
    exit 1
fi

release_paths=(
    API Assets Models Views freemius vendor
    daily-prayer-time.php readme.txt uninstall.php
    CHANGELOG.md LICENSE index.html composer.json composer.lock
)
mkdir "$output_dir"
while IFS= read -r -d '' file; do
    mkdir -p "$output_dir/$(dirname "$file")"
    cp "$repo_root/$file" "$output_dir/$file"
done < <(git -C "$repo_root" ls-files -z -- "${release_paths[@]}")

for required_file in daily-prayer-time.php readme.txt vendor/autoload.php Models/QuranADay/files/english.csv Models/QuranADay/files/bangla.csv; do
    if [[ ! -f "$output_dir/$required_file" ]]; then
        echo "Missing release file: $required_file" >&2
        exit 1
    fi
done
for excluded_path in .git .github .gitignore dev tests scripts docker-compose.yml; do
    if [[ -e "$output_dir/$excluded_path" ]]; then
        echo "Development path included in release: $excluded_path" >&2
        exit 1
    fi
done

echo "Staged Daily Prayer Time $plugin_version for SVN trunk at $output_dir"
echo "Copy this directory's contents into SVN trunk, then use svn copy to create tags/$plugin_version."
