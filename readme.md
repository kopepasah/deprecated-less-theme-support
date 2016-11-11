# (Deprecated)

This repository is no longer maintained and will be removed on 2017-01-01.

# Less Theme Support

Using [Less](http://lesscss.org) when developing a theme can both enhance the code and speed development. However, setting up Less in a every theme (not to mention updating Less for each theme) can make any saved time saved.

This plugin solves that issue by setting up Less for the theme.

## Less Version

The current version installed in this plugin is __1.7.0__. I try to keep up with the latest, but if I fall behind just send me a pull request.

##Installation

This plugin can be installed and activated the [WordPress way](https://codex.wordpress.org/Managing_Plugins).

##Usage

1. Add `style.less` at the root of the theme.
2. Add the following to the `after_setup_theme` hook.

```php
add_theme_support( 'less', array(
	'enable' => true
) );
```

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
