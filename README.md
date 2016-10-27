# WP Slick Gallery

Easily turn WP gallery into a Slick slider. Enable the plugin, add/edit your galleries and set slick properties through custom gallery sidebar options.

## Description

Wordpress plugin to easily turn any gallery into a Slick-driven slider.

## Installation

1. Upload `slick-wordpress-gallery.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Done!

## Constants

Enable developer mode to use non-minified JS & output slick properties in frontend
```
define('WP_SLICK_DEV', true);
```

To turn off CSS & JS injection via the plugin:

```
define('WP_SLICK_CSS', false);
define('WP_SLICK_JS', false);
```

## Frequently Asked Questions

### Is this a stable version?

No, it's not! Please be aware and use with caution

### Which versions does this plugin support?

I've tested it on 4.5.3 only as it was initially built for a customer only.


## Changelog

### 1.0.0
* Initial version

## Thanks

https://github.com/kenwheeler/slick/

## WP-Info
Contributors: herooutoftime
Tags: gallery, slick
Requires at least: 4.2.1
Tested up to: 4.5.3
Stable tag: 4.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html