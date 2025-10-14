<?php

declare(strict_types=1);

namespace Infrangible\CatalogFilterStateToolbar\Block\Product\ProductList\Toolbar\State;

use Magento\Framework\Exception\LocalizedException;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Clear extends \Infrangible\CatalogFilterStateToolbar\Block\Product\ProductList\Toolbar\State
{
    public function getClearUrl(): string
    {
        $filterState = [];

        foreach ($this->getActiveFilters() as $item) {
            try {
                $filterState[ $item->getFilter()->getRequestVar() ] = $item->getFilter()->getCleanValue();
            } catch (LocalizedException $exception) {
            }
        }

        $params[ '_current' ] = true;
        $params[ '_use_rewrite' ] = true;
        $params[ '_query' ] = $filterState;
        $params[ '_escape' ] = true;

        return $this->_urlBuilder->getUrl(
            '*/*/*',
            $params
        );
    }
}
