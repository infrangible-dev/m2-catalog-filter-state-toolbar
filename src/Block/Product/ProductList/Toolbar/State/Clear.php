<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\CatalogFilterStateToolbar\Block\Product\ProductList\Toolbar\State;

use Infrangible\CatalogFilterStateToolbar\Block\Product\ProductList\Toolbar\State;
use Magento\Framework\Exception\LocalizedException;

/**
 * The link that removes all applied filters, like "Clear All" of Magento's layered navigation.
 */
class Clear extends State
{
    /**
     * Returns the URL of the product list without the applied filters.
     *
     * Other parameters (search query, sort order) stay.
     *
     * @return string
     */
    public function getClearUrl(): string
    {
        $filterState = [];

        foreach ($this->getActiveFilters() as $item) {
            try {
                $filter = $item->getFilter();

                $filterState[ $filter->getRequestVar() ] = $filter->getCleanValue();
            } catch (LocalizedException) {
                continue;
            }
        }

        return $this->_urlBuilder->getUrl(
            '*/*/*',
            [
                '_current'     => true,
                '_use_rewrite' => true,
                '_query'       => $filterState,
                '_escape'      => true
            ]
        );
    }
}
