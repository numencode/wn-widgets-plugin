# Widgets Plugin

The **Widgets Plugin** adds reusable content widgets to Winter CMS: promotions, features, highlights, galleries and
counters. Editors manage them in the backend as groups of items, and CMS components display a group on any page,
layout or static page, with layouts that themes can override.

[![Version](https://img.shields.io/github/v/release/numencode/wn-widgets-plugin?style=flat-square&color=0099FF)](https://github.com/numencode/wn-widgets-plugin/releases)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/numencode/wn-widgets-plugin?style=flat-square&color=0099FF)](https://packagist.org/packages/numencode/wn-widgets-plugin)
[![Tests](https://img.shields.io/github/actions/workflow/status/numencode/wn-widgets-plugin/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/numencode/wn-widgets-plugin/actions)
[![License](https://img.shields.io/github/license/numencode/wn-widgets-plugin?label=open%20source&style=flat-square&color=0099FF)](https://github.com/numencode/wn-widgets-plugin/blob/main/LICENSE.md)

---

## Features

- **Five widget types**, each with its own backend page, permission and component:

  | Widget | Items | Default layout |
  |---|---|---|
  | **Promotions** | title, subtitle, content, picture, link | carousel |
  | **Features** | title, subtitle, content, picture, link | cards (with or without pictures) |
  | **Highlights** | title, subtitle, content, picture, link | text and picture side by side |
  | **Gallery** | picture, title, subtitle | picture grid, 1–12 per row |
  | **Counters** | value, suffix, title, icon | row of numbers |

- **Groups of items** – a group has a title, an intro text and a link; its items are edited inline, can be reordered
  and unpublished.
- **Selectable layouts** – every component has a layout dropdown; themes can override the default layout and add their
  own.
- **Bootstrap 5 starter templates**, meant to be overridden in the theme.
- **Static pages** – all components are available as Winter.Pages snippets.
- **Translations** – groups and items are translatable with [Winter.Translate](https://github.com/wintercms/wn-translate-plugin).
- **Publishing** – unpublished groups and items are hidden on the website; logged-in backend users still see them.
- **Permissions** – one permission per widget type, plus finer control per user group with NumenCode.Fundamentals
  `CmsPermissions`.
- English and Slovenian backend translations.

## Requirements

- PHP 8.2 or newer
- [Winter CMS](https://wintercms.com) 1.2 (tested with 1.2.15)
- [NumenCode.Fundamentals](https://github.com/numencode/wn-fundamentals-plugin) 2.1 or newer (installed automatically)
- Optional: [Winter.Translate](https://github.com/wintercms/wn-translate-plugin) for translations,
  [Winter.Pages](https://github.com/wintercms/wn-pages-plugin) for static page snippets

## Installation

```bash
composer require numencode/wn-widgets-plugin
php artisan winter:up
```

## Managing widgets

The backend menu **Widgets** has a page for every widget type. Each record is a **group** (e.g. "Homepage slider" or
"Office gallery") with:

- a title, an intro text and an optional link (tab "Description"),
- its **items**, edited inline in the "Items" tab: drag to reorder, collapse or expand them one by one or all at
  once,
- a "Published" switch in the sidebar.

Each widget type has its own permission (Settings → Administrators → Permissions → Widgets):
`numencode.widgets.manage_promotions`, `manage_features`, `manage_highlights`, `manage_gallery` and
`manage_counters`.

## Components

| Component | Properties |
|---|---|
| `promotions` | `group`, `layout` |
| `features` | `group`, `layout` |
| `highlights` | `group`, `layout` |
| `gallery` | `group`, `layout`, `items_per_row` (1, 2, 3, 4, 6 or 12; default 4) |
| `counters` | `group`, `layout` |

- **`group`** – ID of the displayed group (a dropdown of all groups in the CMS editor).
- **`layout`** – the layout partial, `@default` by default (see [Layouts](#layouts)).

```twig
title = "Home"
url = "/"

[promotions homeSlider]
group = 1

[gallery]
group = 2
layout = "@default"
items_per_row = 3
==
{% component 'homeSlider' %}
{% component 'gallery' %}
```

Nothing is rendered when no group is selected, or when the group doesn't exist or is unpublished, so templates
don't need checks for an empty group.

The components are also Winter.Pages snippets. With [NumenCode.Bricks](https://github.com/numencode/wn-bricks-plugin)
they are offered as bricks, and each brick shows an "Edit …" link that opens the selected group's backend form in a
new tab (`GroupComponent::bricksEditUrl()`; only for backend users who may manage that widget type).

### Template variables

| Variable | Description |
|---|---|
| `__SELF__.group` | The group: `title`, `content`, `link`, `link_title` |
| `__SELF__.group.items` | Its published items, in their backend order |
| `__SELF__.itemsPerRow` | Gallery only: the number of items per row |
| `__SELF__.columnWidth()` | Gallery only: the width of one item in a 12-column grid (e.g. `3` for 4 per row) |
| `__SELF__.alias` | The component alias, useful for unique HTML IDs |

Item fields: `title`, `subtitle`, `content`, `picture`, `link`, `link_title` (gallery items: `title`, `subtitle`,
`picture`; counters: `title`, `icon`, `value`, `suffix`). `picture` is a media library path, so use
`{{ item.picture|media }}` or `{{ item.picture|media|resize(700, 400, {mode: 'crop'}) }}`.

## Layouts

The default layouts use [Bootstrap 5](https://getbootstrap.com) markup and are starting points. A theme can override
them and add new ones in `themes/<theme>/partials/<component>/`, where `<component>` is the lowercase class name
(`promotions`, `features`, `highlights`, `gallery`, `counters`), regardless of the alias used on the page:

```text
themes/mytheme/partials/
├── promotions/
│   ├── default.htm      # replaces the plugin's default layout
│   └── slider.htm       # an extra layout, shown as "Slider" in the layout dropdown
└── gallery/
    └── masonry.htm
```

```twig
{# themes/mytheme/partials/gallery/masonry.htm #}
<div class="masonry">
    {% for item in __SELF__.group.items %}
        <img src="{{ item.picture|media|resize(600) }}" alt="{{ item.title }}">
    {% endfor %}
</div>
```

Select the layout in the component's `layout` property (e.g. `layout = "@masonry"`). Partials whose name starts with
`_` are not listed, so they can be used for shared snippets. Layout selection uses the NumenCode.Fundamentals
`ComponentRenderer`; to always render the default layout of a component, switch it off in a plugin's `boot()`:

```php
\NumenCode\Widgets\Components\Gallery::$overrideLayout = false;
```

Notes on the default layouts:

- **Promotions** – a Bootstrap carousel with the ID `promotions-<alias>`, so several carousels can be on one page.
- **Gallery** – each picture links to the full-size image with `data-gallery="<alias>"` (and `data-title`), so a
  lightbox script in your theme (e.g. GLightbox or Fancybox) can open them as one gallery.
- **Counters** – the number is printed as text, and also in `data-to` for a counting animation script.

## Restricting actions per user group

The Widgets backend pages respect NumenCode.Fundamentals `CmsPermissions`. For example, to stop the "publishers"
user group from deleting promotions, in your plugin's `boot()`:

```php
use NumenCode\Fundamentals\Classes\CmsPermissions;
use NumenCode\Widgets\Controllers\Promotions;

CmsPermissions::revokeDelete('publishers', Promotions::class);
```

Revoked actions are hidden in the toolbars and blocked on the server. Without the update permission, a record opens
read-only.

## Extending

The models, controllers and components can be extended like any Winter class. To change a widget type's form, use
NumenCode.Fundamentals `ConfigOverride` (each widget type has its own YAML files):

```php
use NumenCode\Fundamentals\Bootstrap\ConfigOverride;
use NumenCode\Widgets\Models\GalleryItem;

ConfigOverride::extendFields(GalleryItem::class, function (array $config) {
    $config['fields']['subtitle']['label'] = 'Category';

    return $config;
});
```

## Upgrading from 1.x

Version 2.0 contains breaking changes; see [Upgrading from 1.x](CHANGELOG.md#upgrading-from-1x) in the changelog.

## Changelog

All notable changes are documented in the [CHANGELOG](CHANGELOG.md).

## Contributing

Please refer to the [CONTRIBUTING](CONTRIBUTING.md) guide for details on contributing to this project.

## Security

If you discover a security issue, please email <info@numencode.com> instead of using the issue tracker.

## Author

The **NumenCode.Widgets** plugin is created and maintained by [Blaz Orazem](https://orazem.si/).
More NumenCode plugins: [numencode.com](https://www.numencode.com).

For inquiries, contact: <info@numencode.com>

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
