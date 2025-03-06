# Widgets Plugin

The **NumenCode Widgets** plugin for Winter CMS provides a flexible and easy-to-use solution for managing reusable
widgets in Winter CMS. This plugin simplifies adding dynamic, visually engaging components to your themes and
templates, enhancing the overall user experience.

[![Version](https://img.shields.io/github/v/release/numencode/wn-widgets-plugin?style=flat-square&color=0099FF)](https://github.com/numencode/wn-widgets-plugin/releases)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/numencode/wn-widgets-plugin?style=flat-square&color=0099FF)](https://packagist.org/packages/numencode/wn-widgets-plugin)
[![Checks](https://img.shields.io/github/check-runs/numencode/wn-widgets-plugin/main?style=flat-square)](https://github.com/numencode/wn-widgets-plugin/actions)
[![Tests](https://img.shields.io/github/actions/workflow/status/numencode/wn-widgets-plugin/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/numencode/wn-widgets-plugin/actions)
[![License](https://img.shields.io/github/license/numencode/wn-widgets-plugin?label=open%20source&style=flat-square&color=0099FF)](https://github.com/numencode/wn-widgets-plugin/blob/main/LICENSE.md)

---

## Target Audience

The target audience for this plugin includes developers and designers who want to enhance their Winter CMS projects
by incorporating reusable, visually appealing widgets. The provided widgets, such as counters, promotions, features,
highlights, and galleries, are commonly used in modern web design and are often found in templates and themes
available on various marketplaces. This plugin bridges the gap between static designs and dynamic functionality.

## Installation

This plugin is available for installation via [Composer](http://getcomposer.org/).

```bash
composer require numencode/wn-widgets-plugin
```

After installing the plugin you will need to run the migrations.

```bash
php artisan winter:up
```

## Requirements

* [NumenCode Fundamentals Plugin](https://github.com/numencode/wn-fundamentals-plugin) version ^1.0 or higher.
* [Winter CMS](https://wintercms.com/) 1.2.7 or higher.
* PHP version 8.0 or higher.

---

## Features Overview

This plugin provides an intuitive interface to create and manage a variety of widgets, enabling end users
to customize their websites with ease. With multilingual support via the `Winter.Translate` plugin,
it ensures global usability.

### Counters

Showcase statistics or numeric information on your website using dynamic and engaging animated counters.
Enter the desired number, provide explanatory text, and optionally include an icon class for added flair.
Counters can be easily configured through component properties.

### Promotions

Create attention-grabbing promotional content such as sliders and carousels that seamlessly integrate
into your website's aesthetic. Perfect for highlighting creative content within a compact space.
Promotions are managed via the `Promotions` tab in the Widgets plugin.

### Features

Display multiple items in an organized list or an eye-catching slider/carousel format. This is ideal
for showcasing services, products, or other grouped content. Manage features directly from the `Features` tab.

### Highlights

Create visually striking content cards with accompanying descriptions to emphasize key aspects of your website.
Highlights are perfect for promoting unique selling points or key features and are managed through the `Highlights` tab.

### Gallery

Easily create and manage multiple image galleries with a flexible and intuitive interface. Upload, reorder,
and showcase images using the dedicated Gallery component, which seamlessly integrates into any page.
Manage galleries via the `Gallery` tab in the Widgets plugin.

---

## Changelog

All notable changes are documented in the [CHANGELOG](CHANGELOG.md).

---

## Contributing

Please refer to the [CONTRIBUTING](CONTRIBUTING.md) guide for details on contributing to this project.

---

## Security

If you identify any security issues, email info@numencode.com rather than using the issue tracker.

---

## Author

The **NumenCode.Widgets** plugin is created and maintained by [Blaz Orazem](https://orazem.si/).

For inquiries, contact: info@numencode.com

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

[![License](https://img.shields.io/github/license/numencode/wn-widgets-plugin?style=flat-square&color=0099FF)](https://github.com/numencode/wn-widgets-plugin/blob/main/LICENSE.md)
