# Changelog

## 2.0.0

### Breaking changes

- Requires Magento 2.4.6 or newer and PHP 8.1 or newer. Older versions of Magento and PHP 7.1 to 8.0 are no longer
  supported; use version 1.x for these.
- Requires `infrangible/m2-core` 5.0.6 (for `Helper\Layer`), `infrangible/m2-foundation` 3.0 and `fewedev/base` 2.4.3.
- The blocks have native types and use constructor property promotion. `Toolbar\State` and `Toolbar\State\Clear` take
  `Infrangible\Core\Helper\Layer` and `FeWeDev\Base\Variables` instead of the layer resolver, `Toolbar\State\Item`
  takes `FeWeDev\Base\Variables`.
- `Toolbar\State\Item::getFilter()` returns null before a filter is set, and the block renders nothing without filter;
  before, the uninitialized property made it fail.

### Changed

- The templates escape every value, also the CSS classes from the layout. The item template uses the new methods
  `getFilterName()`, `getFilterValue()`, `getRemoveUrl()` and `getRemoveTitle()` of the item block.
- The stylesheet uses the icon font and the remove icon of the theme (`@icons__font-name`, `@icon-remove`) and Magento's
  color variables instead of fixed values; in Luma the result is the same.

### Added

- Translations in 24 locales.
- Unit tests.
