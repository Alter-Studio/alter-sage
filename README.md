# [Sage](https://roots.io/sage/)
[![Packagist](https://img.shields.io/packagist/vpre/roots/sage.svg?style=flat-square)](https://packagist.org/packages/roots/sage)
[![devDependency Status](https://img.shields.io/david/dev/roots/sage.svg?style=flat-square)](https://david-dm.org/roots/sage#info=devDependencies)
[![Build Status](https://img.shields.io/travis/roots/sage.svg?style=flat-square)](https://travis-ci.org/roots/sage)

Sage is a WordPress starter theme with a modern development workflow.

## Features

* Sass for stylesheets
* Modern JavaScript
* [Webpack](https://webpack.github.io/) for compiling assets, optimizing images, and concatenating and minifying files
* [Browsersync](http://www.browsersync.io/) for synchronized browser testing
* [Blade](https://laravel.com/docs/5.5/blade) as a templating engine
* [Controller](https://github.com/soberwp/controller) for passing data to Blade templates
* CSS framework (optional): [Bootstrap 4](https://getbootstrap.com/), [Bulma](https://bulma.io/), [Foundation](https://foundation.zurb.com/), [Tachyons](http://tachyons.io/)
* Font Awesome (optional)

See a working example at [roots-example-project.com](https://roots-example-project.com/).

## Requirements

Make sure all dependencies have been installed before moving on:

* [WordPress](https://wordpress.org/) >= 4.7
* [PHP](https://secure.php.net/manual/en/install.php) >= 7.0 (with [`php-mbstring`](https://secure.php.net/manual/en/book.mbstring.php) enabled)
* [Composer](https://getcomposer.org/download/)
* [Node.js](http://nodejs.org/) >= 6.9.x
* [Yarn](https://yarnpkg.com/en/docs/install)

## Theme installation

Install Sage using Composer from your WordPress themes directory (replace `your-theme-name` below with the name of your theme):

```shell
# @ app/themes/ or wp-content/themes/
$ composer create-project roots/sage your-theme-name dev-master
```

During theme installation you will have options to update `style.css` theme headers, select a CSS framework, add Font Awesome, and configure Browsersync.

## Theme structure

```shell
themes/your-theme-name/   # → Root of your Sage based theme
├── app/                  # → Theme PHP
│   ├── controllers/      # → Controller files
│   ├── admin.php         # → Theme customizer setup
│   ├── filters.php       # → Theme filters
│   ├── helpers.php       # → Helper functions
│   └── setup.php         # → Theme setup
├── composer.json         # → Autoloading for `app/` files
├── composer.lock         # → Composer lock file (never edit)
├── dist/                 # → Built theme assets (never edit)
├── node_modules/         # → Node.js packages (never edit)
├── package.json          # → Node.js dependencies and scripts
├── resources/            # → Theme assets and templates
│   ├── assets/           # → Front-end assets
│   │   ├── config.json   # → Settings for compiled assets
│   │   ├── build/        # → Webpack and ESLint config
│   │   ├── fonts/        # → Theme fonts
│   │   ├── images/       # → Theme images
│   │   ├── scripts/      # → Theme JS
│   │   └── styles/       # → Theme stylesheets
│   ├── functions.php     # → Composer autoloader, theme includes
│   ├── index.php         # → Never manually edit
│   ├── screenshot.png    # → Theme screenshot for WP admin
│   ├── style.css         # → Theme meta information
│   └── views/            # → Theme templates
│       ├── layouts/      # → Base templates
│       └── partials/     # → Partial templates
└── vendor/               # → Composer packages (never edit)
```

## Theme setup

Edit `app/setup.php` to enable or disable theme features, setup navigation menus, post thumbnail sizes, and sidebars.

## Theme development

* Run `yarn` from the theme directory to install dependencies
* Update `resources/assets/config.json` settings:
  * `devUrl` should reflect your local development hostname
  * `publicPath` should reflect your WordPress folder structure (`/wp-content/themes/sage` for non-[Bedrock](https://roots.io/bedrock/) installs)

### Build commands

* `yarn run start` — Compile assets when file changes are made, start Browsersync session
* `yarn run build` — Compile and optimize the files in your assets directory
* `yarn run build:production` — Compile assets for production

## Directives

All directive additions are located in the setup.php file.
You can refer to this file for a better understanding of what these directives output.

Basic Fields
- `@field`
- `@getField`

```blade
@field('echo the field')
```

Repeater and flexible content loop
- `@fields`
- `@endFields`

Subfields
- `@sub`
- `@getSub`
- `@hasSub`
- `@endSub`

```blade
<ul>
  @fields('list')
    @hasSub('title')
      <li>@sub('title')</li>
    @endSub
    <li>@sub('bullet')</li>
  @endFields
</ul>
```

Layouts for flexible content fields
- `@ifLayout`
- `@elseLayout`
- `@endLayout`

```blade
@fields('flexiblefield')
  @ifLayout('layoutone')
  @elseLayout('layouttwo')
  @endLayout
@endFields
```

Conditional statments
- `@hasField`
- `@endField`

```blade
@hasField('thefield')
<div class="thefield">
  <p class="large">@field('innerfield')</p>
</div>
@endField
```

Responsive Image
The responsive image directive does not require any css to determine the padding based off the image ratio.
This is built to work with [Lazysizes](https://github.com/aFarkas/lazysizes).

- `@reponsiveImage`

```blade
@reponsiveImage('imagefield')
```

This currently only works for images that have the small, medium and large sizes.
eg.
```php
get_field($expression)['sizes']['small']
get_field($expression)['sizes']['medium']
get_field($expression)['sizes']['large']
```

Non-responsive Images
This is also built to work with [Lazysizes](https://github.com/aFarkas/lazysizes), however these images have css classes set to manage the padding.

Regular Images
- `@recImg`
- `@squImg`
- `@widImg`
- `@thumbImg`
- `@squTax`

Subfield Images
- `@recSub`
- `@squSub`
- `@widSub`
- `@miniSub`
- `@thumbSub`

```blade
@recImg('imagefield')

@fields('images')
  @squSub('subimage')
@endFields
```

Gallery
- `@theGallery`

```blade
@theGallery('galleryfield')
```

Icons
- `@icon`

```blade
@theGallery('galleryfield')
```

Translation
- `@trans`

```blade
@trans('translation field')
```

## Responsive Images & lazysizes

A filter exists to calculate the ratio padding for lazyloading responsive images.
This is accessable via the returned image array.
The naming convention to select the ratio {{size}}-ratio.

Example:
```php
get_field($expression)['sizes']['small-ratio'];
get_field($expression)['sizes']['medium-ratio'];
get_field($expression)['sizes']['large-ratio'];
```


A directive exists to assist echoing images with this.

- `@reponsiveImage`

```blade
@reponsiveImage('imagefield')
```


## Documentation

* [Sage documentation](https://roots.io/sage/docs/)
* [Controller documentation](https://github.com/soberwp/controller#usage)

## Contributing

Contributions are welcome from everyone. We have [contributing guidelines](https://github.com/roots/guidelines/blob/master/CONTRIBUTING.md) to help you get started.

## Gold sponsors

Help support our open-source development efforts by [contributing to Sage on OpenCollective](https://opencollective.com/sage).

<a href="https://kinsta.com/?kaid=OFDHAJIXUDIV"><img src="https://roots.io/app/uploads/kinsta.svg" alt="Kinsta" width="200" height="150"></a> <a href="https://k-m.com/"><img src="https://roots.io/app/uploads/km-digital.svg" alt="KM Digital" width="200" height="150"></a>

## Community

Keep track of development and community news.

* Participate on the [Roots Discourse](https://discourse.roots.io/)
* Follow [@rootswp on Twitter](https://twitter.com/rootswp)
* Read and subscribe to the [Roots Blog](https://roots.io/blog/)
* Subscribe to the [Roots Newsletter](https://roots.io/subscribe/)
* Listen to the [Roots Radio podcast](https://roots.io/podcast/)
