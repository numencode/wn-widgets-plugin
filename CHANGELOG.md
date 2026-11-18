# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.2.0] - 2026-11-18

### Added

- Each component declares its widget type's backend menu icon (`icon` in `componentDetails()`), so
  [NumenCode.Bricks](https://github.com/numencode/wn-bricks-plugin) shows it on the widget bricks.

## [2.1.1] - 2026-11-18

### Fixed

- Packagist's metadata for 2.1.0 referred to a different commit than the git tag. This release has the same code as
  2.1.0 and is the version to install.

## [2.1.0] - 2026-11-17

### Added

- Components declare where their group is edited (`GroupComponent::bricksEditUrl()`), so NumenCode.Bricks shows an
  "Edit …" link on widget bricks that opens the selected group in a new tab. Only for backend users who may manage
  that widget type.

## [2.0.1] - 2026-11-16

### Fixed

- The "– select –" placeholder of the component group dropdown and its "required" message showed raw
  language keys in the Inspector (e.g. when adding a widget snippet to a static page). Winter doesn't translate
  these property keys, so they are now passed translated.

## [2.0.0] - 2026-11-06

A rewrite on NumenCode.Fundamentals 2.1 and Winter CMS 1.2.15. It contains breaking changes –
see [Upgrading from 1.x](#upgrading-from-1x).

### Added

- **Counters as a widget type** – counter groups with any number of counters (value, suffix, title, icon), managed in
  the backend with their own page and permission (`numencode.widgets.manage_counters`). Counters are now translatable.
- **Layout selection is enabled by default** for all components; each component can still be switched off with
  `::$overrideLayout = false`.
- **`CmsPermissions` support** – actions revoked with NumenCode.Fundamentals `CmsPermissions` (create, update,
  delete) are hidden in the toolbars and blocked on the server. Without the update permission a record opens
  read-only.
- **"Expand all" / "Collapse all"** buttons for the items of a group.
- Slovenian translation (`lang/sl`).
- Gallery: `columnWidth()` for templates, and the gallery pictures link to the full-size image with
  `data-gallery="<alias>"` for theme lightbox scripts.

### Changed

- **Requires PHP 8.2+ and NumenCode.Fundamentals 2.1+.**
- **Backend:** items are edited with the Fundamentals `relationrepeater` (collapsed, with the item title in the
  header), Winter's default controller views are used with the sidebar form layout, and the "No. of items" column is a
  sortable relation count.
- **Component properties:** the group is selected with the `group` property (was `title`). The defaults are an empty
  group and the `@default` layout. Gallery `items_per_row` is a dropdown (1, 2, 3, 4, 6, 12).
- **Gallery template variable:** `__SELF__.group` (was `__SELF__.gallery`), like the other components.
- **Default layouts** use Bootstrap 5 markup (were Bootstrap 4). The Promotions carousel has a unique ID per
  component, slides are images, and the counters show their numbers without JavaScript.
- Nothing is rendered when no group is selected or the group is missing or unpublished.
- New groups and items are published by default, also when created from code.
- The widget types share base classes (`WidgetGroup`, `WidgetItem`, `WidgetController`, `GroupComponent`); all class
  names, tables and component aliases stay the same.
- Field labels use the common NumenCode.Fundamentals translations.

### Fixed

- Sorting the group list by "No. of items" failed with an SQL error (`Unknown column 'item_count'`), and the count
  loaded all items of every group.
- The Gallery permission label was missing.
- Two Promotions carousels on one page controlled each other.
- Gallery `items_per_row` of 0 caused a division by zero.
- Empty headings, links and buttons were rendered for empty fields.
- The backend menu failed without a logged-in user.

### Removed

- **Soft deletes on widget groups.** Deleted groups could not be restored in the backend; deleting a group now deletes
  it and its items. The update deletes groups that were soft-deleted in 1.x, with their items, and drops the
  `deleted_at` columns.
- The hard-coded rule that revoked deleting promotions for the `owners` and `publishers` user groups.
- The Counters component properties `first_title`, `first_icon`, `first_value` … `fourth_value`, and the
  `__SELF__.counters` variable.
- The controller `.htm` views (they used the removed Fundamentals `numencode_partial()`).

### Upgrading from 1.x

1. **Requirements** – PHP 8.2+ and NumenCode.Fundamentals 2.1+ (see its
   [upgrade guide](https://github.com/numencode/wn-fundamentals-plugin/blob/main/CHANGELOG.md#upgrading-from-1x)).
   Run `php artisan winter:up` after updating.
2. **Soft-deleted groups** are deleted permanently by the update. If you need any of them, restore them in the
   database (set `deleted_at` to `NULL`) before updating.
3. **Component property `title` → `group`** – in pages, layouts and partials:

   ```ini
   [promotions]
   title = 1    ; 1.x
   group = 1    ; 2.0
   ```

   Static page snippets store their properties in the page content: replace `data-property-title="…"` with
   `data-property-group="…"` in `themes/<theme>/content/static-pages/*.htm` (or re-insert the snippet).
4. **Theme overrides** (`themes/<theme>/partials/<component>/*.htm`):
   - Gallery: `__SELF__.gallery` → `__SELF__.group`, and loop over `group.items` instead of `gallery.items_list`;
   - the default layouts use Bootstrap 5; review your overrides if they include or copy them;
   - checks for an empty or unpublished group (`{% if group %}`) are no longer needed.
5. **Counters** – create a counter group in the backend (Widgets → Counters), select it in the component
   (`group = <id>`) and remove the old `first_title` … properties. In overrides, use `__SELF__.group.items` with
   `item.value`, `item.suffix`, `item.title` and `item.icon` instead of `__SELF__.counters.first` etc.
6. **Layouts** – remove `::$overrideLayout = true` lines from your plugins; Widgets enables layout selection itself.
   Layout values are `@name` (e.g. `layout = "@default"`), as before.
7. **Delete restriction** – the 1.x plugin revoked deleting promotions for the `owners` and `publishers` user groups.
   To keep this, add it to your plugin's `boot()`:

   ```php
   \NumenCode\Fundamentals\Classes\CmsPermissions::revokeDelete(
       ['owners', 'publishers'],
       \NumenCode\Widgets\Controllers\Promotions::class
   );
   ```

8. **Form changes** – the item forms no longer have the `section` field. Check `ConfigOverride` extensions of the
   Widgets `fields.yaml` files.

## [1.2.0] - 2025-03-20

### Changed

- All components use the `ComponentRenderer` trait.
- Requires NumenCode.Fundamentals 1.1 or newer.

## [1.1.0] - 2025-03-06

### Added

- Gallery – manage multiple image galleries.

## [1.0.1] - 2025-02-28

### Changed

- Removed the dependency declarations from the plugin file; the dependencies are in `composer.json`.
- Minor improvements to the README.

## [1.0.0] - 2025-01-16

### Added

- Initial release of the **Widgets Plugin** with Counters, Promotions, Features and Highlights, compatible with
  Winter CMS `1.2.7`.

[Unreleased]: https://github.com/numencode/wn-widgets-plugin/compare/v2.2.0...HEAD
[2.2.0]: https://github.com/numencode/wn-widgets-plugin/compare/v2.1.1...v2.2.0
[2.1.1]: https://github.com/numencode/wn-widgets-plugin/compare/v2.1.0...v2.1.1
[2.1.0]: https://github.com/numencode/wn-widgets-plugin/compare/v2.0.1...v2.1.0
[2.0.1]: https://github.com/numencode/wn-widgets-plugin/compare/v2.0.0...v2.0.1
[2.0.0]: https://github.com/numencode/wn-widgets-plugin/compare/v1.2.0...v2.0.0
[1.2.0]: https://github.com/numencode/wn-widgets-plugin/compare/v1.1.0...v1.2.0
[1.1.0]: https://github.com/numencode/wn-widgets-plugin/compare/v1.0.1...v1.1.0
[1.0.1]: https://github.com/numencode/wn-widgets-plugin/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/numencode/wn-widgets-plugin/releases/tag/v1.0.0
