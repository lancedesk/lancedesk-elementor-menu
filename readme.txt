=== LanceDesk Responsive Menu for Elementor ===
Contributors: lancedesk
Tags: elementor, menu, responsive menu, mobile menu, navigation
Requires at least: 5.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.7
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Build responsive Elementor navigation menus with one widget. Control desktop, tablet, and mobile layouts without duplicate menu widgets.

== Description ==

LanceDesk Responsive Menu for Elementor is a WordPress plugin for Elementor that helps you build flexible, responsive navigation menus using a single widget instance.

Instead of duplicating multiple menu widgets and hiding them per breakpoint, you can configure one menu for desktop, tablet, and mobile behavior directly inside Elementor.

= Why this plugin =

* One Elementor menu widget across devices
* Device-specific layout controls (desktop/tablet/mobile)
* Submenu trigger options: Hover, Click, Hover & Click
* Optional accordion behavior for cleaner submenu interaction
* Off-canvas support with responsive controls and editor-preview reliability
* Device-specific menu selection for standard and off-canvas modes
* Accessibility-focused markup and keyboard support
* WordPress coding standards and GPL-compatible licensing

= Ideal for =

* Business websites using Elementor
* Landing pages needing custom responsive navigation
* Agencies building reusable Elementor workflows
* Sites where mobile navigation usability is critical

== Installation ==

1. Upload the `lancedesk-responsive-menu-for-elementor` folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen.
2. Activate the plugin through the `Plugins` screen in WordPress.
3. Ensure Elementor is installed and activated.
4. Edit a page with Elementor and search for `LanceDesk Responsive Menu`.
5. Select your WordPress menu and configure responsive settings.

== Frequently Asked Questions ==

= Does this plugin require Elementor? =

Yes. Elementor must be active for this widget to appear.

= Can I use different menu behaviors on desktop and mobile? =

Yes. You can configure responsive layout and submenu behavior for different devices.

= Can I use both hover and click for submenus? =

Yes. Choose `Hover & Click` in the submenu trigger setting.

= Is this plugin accessible? =

The widget includes keyboard navigation support and ARIA attributes for submenu controls.

== Screenshots ==

1. Elementor widget panel for LanceDesk Responsive Menu.
2. Desktop navigation with submenu controls.
3. Tablet responsive layout example.
4. Mobile/off-canvas menu interaction.
5. Submenu styling and trigger options.

== Changelog ==

= 1.0.7 =
* Preserve WordPress menu item CSS classes on frontend `<li>` output for standard and off-canvas renderers.
* Respect per-menu-item link target (`target`) from WordPress menu settings instead of forcing widget-level target only.
* Include menu item relationship (`rel`/XFN) values when set in WordPress menu item options.

= 1.0.6 =
* Renamed plugin branding and slug/text domain to `lancedesk-responsive-menu-for-elementor` for WordPress.org trademark compliance.
* Replaced inline debug script output with `wp_add_inline_script()` on the frontend handle.
* Hardened rendered menu HTML output escaping in standard and off-canvas paths.

= 1.0.5 =
* Added translators comments for placeholder-based i18n strings.
* Hardened request action sanitization and cleanup routines for Plugin Check compliance.
* Removed discouraged debug/textdomain patterns and aligned metadata for WordPress.org scanning.

= 1.0.4 =
* Fixed duplicate Elementor control declarations that triggered control redeclare notices.
* Added safe fallback for missing `mobile_hamburger_position` widget setting.
* Reduced PHP warning/notice noise in menu widget render paths.

= 1.0.3 =
* Added device-specific menu selection mapping for standard and off-canvas output.
* Improved Elementor editor behavior for off-canvas toggle/open/close interactions.
* Fixed hamburger alignment control responsiveness in editor preview.
* Improved device-mode synchronization across desktop/tablet/mobile previews.
* Added richer opt-in debug diagnostics for support and QA workflows.

= 1.0.1 =
* Improved submenu interaction logic for click and hover/click modes.
* Added submenu accordion behavior control.
* Added submenu spacing and border styling controls.
* Improved editor/frontend behavior consistency.
* Added WordPress.org readme and publishing metadata updates.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.7 =
Recommended update to keep WordPress menu item classes and link attributes (target/rel) in frontend output.

= 1.0.6 =
Recommended trademark-compliance and security-hardening update for WordPress.org review approval.

= 1.0.5 =
Recommended compliance update to satisfy WordPress.org Plugin Check and submission scanner requirements.

= 1.0.4 =
Recommended maintenance update to eliminate Elementor control redeclare notices and undefined setting warnings.

= 1.0.3 =
Recommended update for stable mobile/off-canvas editor previews and device-specific menu selection support.

= 1.0.1 =
Recommended update with submenu behavior improvements and new styling controls.
