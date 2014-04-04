<?php
/*
Plugin Name: Less Theme Support
Version: 1.0.0
Plugin URI: https://github.com/kopepasah/less-theme-support
Description: Adds support for using Less in a theme, primarily for development.
Author: Justin Kopepasah
Author URI: http://kopepasah.com

Copyright 2014  (email: justin@kopepasah.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

class Less_Theme_Support {
	/**
	 * @var Less_Theme_Support
	 * @since 1.0.0
	*/
	private static $instance;

	/**
	 * Our support array.
	 *
	 * @since 1.0.0 
	*/
	public $support = array();

	/**
	 * Our prefix.
	 *
	 * @since 1.0.0 
	*/
	public $prefix = '';

	/**
	 * Main Less Theme Suport Instance
	 *
	 * @since 1.0.0
	 * @static
	*/
	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Less Theme Suport Constructor
	 *
	 * @since 1.0.0
	*/
	public function __construct() {

		/**
		 * Do a version compare for WordPress. We need
		 * version 3.4 or above, as wp_get_theme() was
		 * added then.
		 *
		 * @since 1.0.0
		*/
		if ( version_compare( $GLOBALS['wp_version'], '3.4', '<=' ) ) {
			add_action( 'admin_notices', array( $this, 'notice' ) );
			return;
		}

		/**
		 * Let's check if the current theme supports less.
		 * If not, bail.
		*/
		if ( ! current_theme_supports( 'less' ) ) {
			return;
		}

		/**
		 * Since we've made it this far, we assume the theme
		 * support Less. Now we can get that theme support.
		*/
		$this->support = $this->get_support();

		/**
		 * Set the prefix for our filters and such.
		*/
		$this->prefix = $this->get_prefix();

		add_filter( 'style_loader_tag',   array( $this, 'filter_style_loader_tag' ) );
		add_filter( 'stylesheet_uri',     array( $this, 'filter_stylesheet_uri' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 100 );
	}

	/**
	 * Notice when SyntacHighlighter is not active.
	 *
	 * @since 1.0.0
	*/
	public function notice() {
		$notice = __( 'Less Theme Support requires WordPress version 3.4 of above.', 'less-theme-support' );

		echo '<div class="error"><p>' . $notice . '</p></div>';
	}

	/**
	 * Get the Less theme support arguments.
	 *
	 * @since 1.0.0
	 * @uses get_theme_support()
	 * @return array Contains the arguments passed for Less.
	*/
	function get_support() {
		$support = get_theme_support( 'less' );

		return $support[0];
	}

	/**
	 * Function for getting the theme object and 
	 * setting our prefix.
	 *
	 * @since 1.2.0
	 * @uses wp_get_theme() Gets a WP_Theme object for a theme.
	 * @return string The prefix set based on the theme's text domain.
	*/
	function get_prefix() {
		// Use wp_get_theme() to get the theme object.
		$theme = wp_get_theme();

		/**
		 * Set the prefix based on the theme object's 
		 * text domain. Text domains are required to use
		 * dashes in lieu of underscores, so we replace
		 * those here, as well as converting to string to
		 * lowercase in order to maintain consistency.
		 *
		 * i.e. the_textdomain_enable_less is more consistent
		 *      with other common WordPress filters than
		 *      Theme-TextDomain_enable_less.
		*/
		$prefix = strtolower( str_replace( '-', '_', $theme->get( 'TextDomain' ) ) );

		// Return our prefix, but allow users to modify if desired.
		return apply_filters( 'less_theme_support_prefix', $prefix );
	}

	/**
	 * Filter the stylesheet_uri to output the less
	 * stylesheet, a minified stylesheet or simply
	 * change nothing.
	 *
	 * Note, the less support is only enabled if a
	 * current user has the capability to edit themes.
	 *
	 * @since 1.0.0
	 * @param string $stylesheet_uri     Stylesheet URI for the current theme/child theme.
	 * @param string $stylesheet_dir_uri Stylesheet directory URI for the current theme/child theme.
	 * @return string The URI of the stylesheet.
	*/
	function filter_stylesheet_uri( $stylesheet_uri, $stylesheet_dir_uri ) {
		if ( current_user_can( 'edit_themes' ) && $this->support['enable'] ) {
			$stylesheet_uri = $stylesheet_dir_uri . '/style.less';
		} else if ( $this->support['minify'] ) {
			$stylesheet_uri = $stylesheet_dir_uri . '/style.min.css';
		}

		return $stylesheet_uri;
	}

	/**
	 * Filter the style_loader_tag to change the rel
	 * attribute on our theme stylesheet. It assumes
	 * the text domain is the same as the handle used
	 * to enqueue the stylesheet.
	 *
	 * Note, the less support is only enabled if a
	 * current user has the capability to edit themes.
	 *
	 * @since 1.0.0
	 * @param $tag The stylesheet tag.
	 * @return string The stylesheet tag.
	*/
	function filter_style_loader_tag( $tag ) {
		if ( preg_match( '/' . $this->prefix . '-css/', $tag ) ) {
			if ( current_user_can( 'edit_themes' ) && $this->support['enable'] ) {
				$tag = preg_replace( '/rel=\'stylesheet\'/', 'rel=\'stylesheet/less\'', $tag );
			}
		}

		return $tag;
	}

	/**
	 * Enqueue's the LESS script, as well as, the script
	 * develop and watch modes.
	 *
	 * Note, the less support is only enabled if a
	 * current user has the capability to edit themes.
	 *
	 * @since 1.0.0
	*/
	function enqueue_scripts() {
		if ( current_user_can( 'edit_themes' ) && $this->support['enable'] ) {

			// Give developers the option to enable development mode.
			if ( $this->support['develop'] ) {
				wp_enqueue_script( 'less-dev', plugins_url( 'js/less-develop.js', __FILE__ ) );
			}

			wp_enqueue_script( 'less', plugins_url( 'js/less.min.js', __FILE__ ), array(), '1.7.0' );

			// If development mode is enabled, watch for changes.
			if ( $this->support['watch'] ) {
				wp_enqueue_script( 'less-watch', plugins_url( 'js/less-watch.js', __FILE__ ) );
			}
		}
	}
}

/**
 * Instantiate this plugin after the theme is setup.
 *
 * @since 1.0.0
*/
function less_theme_support() {
	return Less_Theme_Support::instance();
}
add_action( 'after_setup_theme', 'less_theme_support', 100 );