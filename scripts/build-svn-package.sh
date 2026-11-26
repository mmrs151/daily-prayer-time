#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "$0")/.." && pwd)"
plugin_version="$(sed -n 's/^Version: //p' "$repo_root/daily-prayer-time.php" | head -n 1)"
stable_tag="$(sed -n 's/^Stable tag: //p' "$repo_root/readme.txt" | head -n 1)"
if [[ -z "$plugin_version" || "$plugin_version" != "$stable_tag" || ! "$stable_tag" =~ ^[0-9]+(\.[0-9]+)+$ ]]; then
    echo "Plugin version and readme Stable tag must match and be a numeric version." >&2
    exit 1
fi

release_paths=(
    API Assets Models Views freemius vendor
    daily-prayer-time.php readme.txt uninstall.php
    CHANGELOG.md LICENSE index.html composer.json composer.lock
)
manifest="$(mktemp)"
trap 'rm -f "$manifest"' EXIT
git -C "$repo_root" ls-files -z -- "${release_paths[@]}" > "$manifest"

for required_file in daily-prayer-time.php readme.txt vendor/autoload.php Models/QuranADay/files/english.csv Models/QuranADay/files/bangla.csv; do
    if [[ ! -f "$repo_root/$required_file" ]]; then
        echo "Missing release file: $required_file" >&2
        exit 1
    fi
done

if [[ $# -eq 2 && "$1" == '--stage' ]]; then
    output_parent="$(cd "$(dirname "$2")" && pwd)"
    output_dir="$output_parent/$(basename "$2")"
    if [[ -e "$output_dir" ]]; then
        echo "Output directory already exists: $output_dir" >&2
        exit 1
    fi
    mkdir "$output_dir"
    while IFS= read -r -d '' file; do
        mkdir -p "$output_dir/$(dirname "$file")"
        cp "$repo_root/$file" "$output_dir/$file"
    done < "$manifest"
    echo "Staged Daily Prayer Time $stable_tag at $output_dir"
    exit 0
fi
dry_run=false
if [[ $# -eq 1 && "$1" == '--dry-run' ]]; then
    dry_run=true
elif [[ $# -ne 0 ]]; then
    echo "Usage: bash scripts/build-svn-package.sh [--dry-run | --stage NEW_OUTPUT_DIRECTORY]" >&2
    exit 2
fi

# This repository is checked out inside the WordPress.org SVN working copy's trunk.
svn_root="$(svn info --show-item wc-root "$repo_root")"
if [[ "$repo_root" != "$svn_root/trunk" || ! -d "$svn_root/tags" ]]; then
    echo "Expected this Git checkout to be the SVN working copy's trunk." >&2
    exit 1
fi
if [[ -e "$svn_root/tags/$stable_tag" ]]; then
    echo "SVN tag already exists locally: tags/$stable_tag" >&2
    exit 1
fi
if [[ "$dry_run" == false && "$(git -C "$repo_root" branch --show-current)" != master ]]; then
    echo "Switch to updated Git master before preparing the SVN tag." >&2
    exit 1
fi
if [[ "$dry_run" == false && -n "$(git -C "$repo_root" status --porcelain --untracked-files=no)" ]]; then
    echo "Commit or discard changes to Git-tracked files before preparing the SVN tag." >&2
    exit 1
fi

python3 - "$repo_root" "$svn_root" "$stable_tag" "$manifest" "$dry_run" <<'PY'
import os
import shutil
import subprocess
import sys
import xml.etree.ElementTree as ET

trunk, checkout, version, manifest, dry_run = sys.argv[1:]
with open(manifest, 'rb') as handle:
    wanted = {os.fsdecode(path) for path in handle.read().split(b'\0') if path}

def svn(*args):
    subprocess.run(('svn', *args), check=True)

raw = subprocess.check_output(('svn', 'status', '--xml', '--verbose', '.'), cwd=trunk)
entries = ET.fromstring(raw).iter('entry')
versioned_files = set()
versioned_dirs = set()
for entry in entries:
    path = os.path.normpath(entry.attrib['path'])
    item = entry.find('wc-status').attrib['item']
    if item in {'conflicted', 'obstructed', 'missing', 'incomplete', 'added', 'deleted', 'replaced'}:
        raise SystemExit(f'SVN trunk has an unresolved change ({item}): {path}')
    if path == '.' or item in {'unversioned', 'ignored', 'external'}:
        continue
    if os.path.isdir(os.path.join(trunk, path)):
        versioned_dirs.add(path)
    else:
        versioned_files.add(path)

removed_dirs = []
for path in sorted(versioned_dirs, key=lambda value: (value.count('/'), value)):
    if any(file.startswith(path + '/') for file in wanted):
        continue
    if any(path == parent or path.startswith(parent + '/') for parent in removed_dirs):
        continue
    removed_dirs.append(path)

removed_files = []
for path in sorted(versioned_files - wanted):
    if any(path.startswith(parent + '/') for parent in removed_dirs):
        continue
    removed_files.append(path)

added_files = sorted(wanted - versioned_files)
if dry_run == 'true':
    print(f'Would prepare tags/{version}: {len(added_files)} files added, '
          f'{len(removed_files)} files and {len(removed_dirs)} directories removed from SVN.')
    for path in added_files:
        print(f'  add trunk/{path}')
    for path in removed_dirs + removed_files:
        print(f'  remove trunk/{path}')
    print('No SVN working-copy changes were made.')
    sys.exit(0)

for path in removed_dirs:
    svn('rm', '--keep-local', os.path.join(trunk, path))
for path in removed_files:
    svn('rm', '--keep-local', os.path.join(trunk, path))
for path in added_files:
    svn('add', '--parents', os.path.join(trunk, path))

tag_path = os.path.join(checkout, 'tags', version)
svn('copy', trunk, tag_path)

# A local svn copy also duplicates unversioned files (including .git) on disk.
# Keep only the tracked release manifest in the new tag's physical directory.
for directory, subdirectories, filenames in os.walk(tag_path, topdown=False):
    for filename in filenames:
        path = os.path.join(directory, filename)
        relative = os.path.relpath(path, tag_path)
        if relative not in wanted:
            os.unlink(path)
    for name in subdirectories:
        path = os.path.join(directory, name)
        relative = os.path.relpath(path, tag_path)
        if not any(file.startswith(relative + '/') for file in wanted):
            if os.path.islink(path):
                os.unlink(path)
            else:
                shutil.rmtree(path)

print(f'Prepared SVN trunk and tags/{version}: {len(added_files)} added, '
      f'{len(removed_files)} files and {len(removed_dirs)} directories removed from SVN.')
print('Review svn status and svn diff before committing. Nothing was uploaded.')
PY
