#Less Theme Support

Using [Less](http://lesscss.org) when developing a theme can both enhance the code and speed development. However, setting up Less in a theme every time (not to mention updating Less for all the themes) can make the time saved futile.

This plugin solves that issue.

##Installation

This plugin can be installed and activated the [WordPress way](https://codex.wordpress.org/Managing_Plugins).

##Usage

Enabling Less Theme Support is similar to adding theme support for post-formats or editor styles, as it uses `add_theme_support()`.

Here is an example:

```php
add_theme_support( 'less', array(
	'enable' => true
) );
```

Once enabled you can add a `style.less` file in your theme root and start editing.

###Options

All options are boolean values. Empty options are `false`.

__`enable`__

Enables Less and enqueues less.min.js on the front end. The scripts are only enqueued logged in users with the capability to edit themes.

__`develop`__

Enables development environment for Less and enqueues less-develop.js. The scripts are only enqueued for logged in users with the capability to edit themes. Requires `enable` option to be true.

__`watch`__

Enables watch mode for Less and enqueues less-watch.js. The scripts are only enqueued for logged in users with the capability to edit themes. Requires `enable` option to be true.

__`minify`__

Enables usage of a minified stylesheet (`style.min.css`) on the front end for all other visitors.

##Examples
```php
add_theme_support( 'less', array(
	'enable'  => true,
	'develop' => true,
	'watch'  => true
) );
```

```php
add_theme_support( 'less', array(
	'minify' => true
) );
```