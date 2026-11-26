# Infrangible Catalog Filter State Toolbar

Shows the applied filters of the layered navigation directly above the product list of category and search result
pages, each with a link that removes it, followed by a **Clear All** link. The list of applied filters ("Now Shopping
by") and the "Clear All" link in the sidebar are hidden.

## How it works

- The layout handles `catalog_category_view` and `catalogsearch_result_index` add the block
  `category.product.list.additional.state` (search: `search.product.list.additional.state`) before the product list.
  It shows nothing while no filter is applied.
- Each applied filter shows its name and value, for example "Color Red"; price ranges are shown as text
  ("$10.00 - $19.99"). The link removes the filter and keeps the other ones, the search query and the sort order.
- **Clear All** removes all applied filters and keeps the search query and the sort order.

The CSS classes are arguments of the blocks (`toolbarClass`, `filtersClass` and `itemsClass` of the state block,
`itemClass`, `linkClass`, `labelClass` and `valueClass` of the blocks `item` and `clear`). The child container
`additional` of both blocks takes additional blocks, for example icons of a theme; blocks of the class
`Block\Product\ProductList\Toolbar\State\Item` in it get the filter of their item.

For Hyvä themes, the module `infrangible/m2-catalog-filter-state-toolbar-hyva` adds the styles and icons.

## Requirements

- Magento Open Source or Adobe Commerce 2.4.6 or newer with the Luma theme or a theme based on it
- PHP 8.1 or newer

## Installation

```bash
composer require infrangible/m2-catalog-filter-state-toolbar
bin/magento module:enable Infrangible_CatalogFilterStateToolbar
bin/magento setup:upgrade
```

In production mode, also run `bin/magento setup:di:compile` and `bin/magento setup:static-content:deploy`.

## License

Infrangible Catalog Filter State Toolbar is licensed under the MIT License - see the LICENSE file for details.
