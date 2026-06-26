=== CM Page Builder Maintenance ===
Contributors: clemart
Tags: page builder, maintenance, elementor, divi, gutenberg, beaver builder
Requires at least: 4.9
Tested up to: 7.0
Stable tag: 1.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Maintenance plugin, but redirect to homepage, so page builders are compatible (Elementor, Beaver Builder, Divi, Gutenberg etc).

== Description ==
Maintenance plugin, but redirect to homepage, so page builders are compatible (Elementor, Beaver Builder, Divi, Gutenberg etc).

Activate the plugin to enable maintenance mode. All non-logged-in users visiting any page other than the homepage will be redirected to the homepage with a proper 503 HTTP status code and a Retry-After header, signaling search engines that the unavailability is temporary.

Features:
* Toggle to enable/disable maintenance mode without deactivating the plugin
* Minimum role setting: choose which user role can bypass maintenance mode
* URL whitelist: allow specific pages to remain accessible during maintenance
* 503 HTTP status header with Retry-After for SEO-friendly maintenance
* Compatible with all major page builders: Elementor, Beaver Builder, Divi, Gutenberg

== Changelog ==

= 1.0 =
* Added settings page with enable/disable toggle
* Added minimum role to bypass maintenance mode (Subscriber, Contributor, Author, Editor, Administrator)
* Added URL whitelist to keep specific pages accessible during maintenance
* Added "Settings" action link in the plugin list
* Added French and English translations (languages/)
* Added load_plugin_textdomain() for i18n support

= 0.3 =
* Plugin renamed to CM Page Builder Maintenance for WordPress plugin naming requirements

= 0.2 =
* Replaced wp_redirect() with wp_safe_redirect() for improved security
* Added 503 Service Unavailable HTTP status header
* Added Retry-After: 3600 header for SEO-friendly maintenance mode
* Added PAGE_BUILDER_MAINTENANCE_VERSION constant
* Refactored conditions with early returns for better readability
* Updated tested up to WordPress 7.0
* Updated minimum PHP version to 7.4

= 0.1 =
* Initial release.