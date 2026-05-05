# LanceDesk Responsive Menu for Elementor

Responsive Elementor menu widget for WordPress with per-device layout controls, flexible submenu triggers, and cleaner mobile navigation behavior.

![Version](https://img.shields.io/badge/version-1.0.6-blue)
![License](https://img.shields.io/badge/license-GPL--2.0%2B-green)
![WordPress](https://img.shields.io/badge/wordpress-5.0%2B-blue)
![PHP](https://img.shields.io/badge/php-7.4%2B-blue)
![Elementor](https://img.shields.io/badge/elementor-3.0%2B-blue)

## Why This Plugin

Most Elementor menu workflows require duplicated widgets and breakpoint visibility hacks.
LanceDesk Responsive Menu for Elementor is designed for modern WordPress builds where you want one menu widget that adapts cleanly across desktop, tablet, and mobile.

## Key Features

- Single widget instance for responsive WordPress navigation menus
- Device-aware layout control: horizontal, vertical, grid
- Submenu trigger modes: `Hover`, `Click`, `Hover & Click`
- Optional accordion behavior for nested submenu UX
- Off-canvas support with device-level enable/disable rules
- Device-specific menu selection for both standard and off-canvas modes
- Submenu spacing, border, and nested vertical styling controls
- Improved Elementor editor preview parity for mobile/off-canvas interactions
- Accessibility-focused ARIA and keyboard interaction support

## Why It Matters

This plugin is built for WordPress + Elementor navigation workflows.

- Build one responsive menu instead of duplicating multiple widgets per breakpoint
- Keep navigation behavior consistent across desktop, tablet, and mobile
- Improve usability with configurable submenu triggers and accordion behavior
- Reduce maintenance overhead when updating menus and template layouts

Clear, consistent navigation improves user experience and can support better engagement across your site.

## Requirements

- WordPress 5.0+
- PHP 7.4+
- Elementor 3.0+

## Installation

1. Upload the plugin folder to `/wp-content/plugins/lancedesk-responsive-menu-for-elementor/` or install the zip in wp-admin.
2. Activate the plugin.
3. Open Elementor and drag `LanceDesk Responsive Menu` into your layout.
4. Pick a WordPress menu and configure responsive layout behavior.

## Changelog

### 1.0.6

- Renamed plugin branding and slug/text domain to `lancedesk-responsive-menu-for-elementor` for WordPress.org trademark compliance
- Replaced inline debug script output with `wp_add_inline_script()` on the frontend handle
- Hardened rendered menu HTML output escaping in standard and off-canvas render paths

### 1.0.5

- Added translators comments for placeholder-based i18n strings
- Hardened request action sanitization and cleanup routines for Plugin Check compliance
- Removed discouraged debug/textdomain patterns and aligned metadata for WordPress.org scanning

### 1.0.4

- Fixed duplicate Elementor control registration for menu underline controls
- Fixed missing `mobile_hamburger_position` setting fallback to avoid PHP warnings
- Reduced noisy PHP notices in widget render/control initialization paths

### 1.0.3

- Added device-specific menu ID mapping for standard and off-canvas render variants
- Improved off-canvas behavior in Elementor editor preview (toggle/open/close reliability)
- Fixed hamburger alignment control responsiveness in editor preview
- Improved editor/runtime device sync for desktop, tablet, and mobile states
- Expanded debug tooling for support diagnostics while keeping debug mode opt-in

### 1.0.1

- Improved submenu interaction behavior for click and hover/click modes
- Added configurable submenu accordion behavior
- Added submenu spacing and border customization controls
- Improved editor/frontend consistency for submenu behavior
- Added WordPress.org-compatible `readme.txt`

### 1.0.0

- Initial release

## License

GPL-2.0-or-later. See `LICENSE`.
