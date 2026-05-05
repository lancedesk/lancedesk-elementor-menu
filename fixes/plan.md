# WordPress.org Review Fix Plan

## Goal
Resolve all 3 highlighted review categories from `feedback.log` and align implementation with `wordpress-plugin-creation-checklist.md`.

## Category 1: Naming and trademark-safe slug/display name
- [x] Pick trademark-safe display name and slug pattern (`... for Elementor`).
- [x] Update plugin main header metadata.
- [x] Update readme title/branding and install path references.
- [x] Update plugin constants for canonical slug/text-domain usage.
- [ ] Prepare reviewer reply note to request slug reservation change on wp.org.

### Scanned sections
- `lancedesk-elementor-menu.php` (plugin header + constants)
- `readme.txt` (title + install path)
- `README.md` (install path text)
- `feedback.log` (review requirement and explicit slug reservation instruction)

---

## Category 2: Replace inline `<script>` usage with enqueue APIs
- [x] Remove raw inline `<script>` block from widget rendering.
- [x] Move debug runtime JS into `wp_add_inline_script()` attached to an enqueued handle.
- [x] Keep widget-specific runtime data safely encoded (`wp_json_encode`) and injected as data.
- [x] Verify no direct `<script>` output remains in widget class.

### Scanned sections
- `includes/widgets/class-ldjem-menu-widget.php` (`render_debug_output()` around flagged inline script)
- `includes/class-ldjem-frontend.php` (existing registered/enqueued handles used for attachment)

---

## Category 3: Escape generated output on echo (remove unsafe output ignores)
- [x] Remove/replace `phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped` in rendered menu HTML.
- [x] Escape generated recursive menu HTML at output boundaries (`wp_kses_post` where HTML is intended).
- [x] Keep context-specific escapes for attributes/URLs/text in builders.
- [x] Re-scan for remaining flagged ignore patterns and resolve targeted issues.

### Scanned sections
- `includes/widgets/class-ldjem-menu-widget.php`
  - standard menu root/template outputs
  - off-canvas root/template outputs
  - recursive submenu rendering methods

---

## Validation and clean pass
- [x] Re-scan plugin for `<script>` direct output in PHP.
- [x] Re-scan for `OutputNotEscaped` ignore markers.
- [x] Run linter diagnostics for edited files.
- [x] Final manual checklist pass against `wordpress-plugin-creation-checklist.md` items relevant to this review.

## Reviewer response draft checklist
- [ ] Mention trademark-safe rename completed.
- [ ] Explicitly request new wp.org slug reservation.
- [ ] Confirm enqueue refactor for JS and escaping hardening done.
- [ ] Confirm tested on clean install with debug enabled.
