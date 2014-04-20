=== Less Theme Support ===
Contributors: Kopepasah
Tags: less, lessjs, lesscss, theme
Donate link: http://kopepasah.com/donate/
Requires at least: 3.4
Tested up to: 3.9
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables support feature for using Less in a theme.

== Description ==
Using [Less](http://lesscss.org) when developing a theme can both enhance the code and speed development. However, setting up Less in a every theme (not to mention updating Less for each theme) can make any saved time saved.

This plugin solves that issue by setting up Less for the theme.

__Usage__

1. Add `style.less` at the root of the theme.
2. Add the following to the `after_setup_theme` hook.

	`add_theme_support( 'less', array(
		'enable' => true
	) );`


For more information on advanced usage, other options or to join development, visit the [Github repo](https://github.com/kopepasah/less-theme-support).

== Installation ==

1. Upload `less-theme-support` to your `/plugins/` directory for WordPress
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
None yet. Ask Away!

== Changelog ==
= 1.0.2 =
* Added correct tag.

= 1.0.1 =
* Fixed prefix bug.
* Added more efficient style_loader_tag filter.

= 1.0.0 =
* Initial Release Version