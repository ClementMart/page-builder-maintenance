=== CM Page Builder Maintenance ===
Contributors: clemart
Tags: page builder, maintenance, elementor, divi, gutenberg, beaver builder
Requires at least: 4.9
Tested up to: 7.0
Stable tag: 0.2
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Maintenance plugin, but redirect to homepage, so page builders are compatible (Elementor, Beaver Builder, Divi, Gutenberg etc).

== Description ==
Maintenance plugin, but redirect to homepage, so page builders are compatible (Elementor, Beaver Builder, Divi, Gutenberg etc).

Activate the plugin to enable maintenance mode. All non-logged-in users visiting any page other than the homepage will be redirected to the homepage with a proper 503 HTTP status code and a Retry-After header, signaling search engines that the unavailability is temporary.

== Changelog ==
= 0.3 =
* Name changed for WordPress plugin naming requirments

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