#!/usr/bin/env bash
# Sync this plugin into a local WordPress.org SVN working copy (trunk + assets).
# Prereq: svn checkout https://plugins.svn.wordpress.org/lancedesk-responsive-menu-for-elementor/ <DIR>
# Usage: ./scripts/sync-to-wordpress-org-svn.sh /path/to/svn/checkout
#
# Then: cd /path/to/svn/checkout && svn status && svn add --force trunk assets && svn commit

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PLUGIN_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
SVN_ROOT="${1:?Usage: $0 /path/to/lancedesk-responsive-menu-for-elementor-svn-checkout}"

if [[ ! -d "${SVN_ROOT}/trunk" ]] || [[ ! -d "${SVN_ROOT}/assets" ]]; then
  echo "error: expected ${SVN_ROOT}/trunk and ${SVN_ROOT}/assets (run svn checkout first)." >&2
  exit 1
fi

if ! command -v rsync >/dev/null 2>&1; then
  echo "error: rsync is required (Git for Windows Bash includes it, or use WSL)." >&2
  exit 1
fi

echo "==> Syncing plugin -> trunk (excludes .svn, .git, GitHub, directory-assets)"
rsync -a --delete \
  --exclude '.svn/' \
  --exclude '.git/' \
  --exclude '.github/' \
  --exclude 'directory-assets/' \
  --exclude '.distignore' \
  --exclude 'README.md' \
  --exclude 'scripts/' \
  "${PLUGIN_ROOT}/" "${SVN_ROOT}/trunk/"

echo "==> Copying directory-assets -> assets/ (flat: banner, icon, screenshot-*.png)"
cp -f "${PLUGIN_ROOT}/directory-assets/banner-772x250.png" "${SVN_ROOT}/assets/"
cp -f "${PLUGIN_ROOT}/directory-assets/icon-256x256.png" "${SVN_ROOT}/assets/"
shopt -s nullglob
for f in "${PLUGIN_ROOT}/directory-assets/screenshots/"*.png; do
  cp -f "$f" "${SVN_ROOT}/assets/$(basename "$f")"
done

echo ""
echo "Done. Next (from your machine, with SVN installed):"
echo "  cd \"${SVN_ROOT}\""
echo "  svn status"
echo "  svn add --force trunk assets"
echo "  svn commit -m \"Release 1.0.8 — sync trunk and directory assets\""
echo ""
echo "If this is the first tagged build for this version on .org:"
echo "  svn cp trunk tags/1.0.8"
echo "  svn commit -m \"Tag 1.0.8\""
