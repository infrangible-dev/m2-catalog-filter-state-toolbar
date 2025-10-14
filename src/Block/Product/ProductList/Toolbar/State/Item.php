<?php

declare(strict_types=1);

namespace Infrangible\CatalogFilterStateToolbar\Block\Product\ProductList\Toolbar\State;

use Magento\Framework\View\Element\Template;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Item extends Template
{
    /** @var \Magento\Catalog\Model\Layer\Filter\Item */
    private $filter;

    public function getFilter(): \Magento\Catalog\Model\Layer\Filter\Item
    {
        return $this->filter;
    }

    public function setFilter(\Magento\Catalog\Model\Layer\Filter\Item $filter): void
    {
        $this->filter = $filter;
    }
}
