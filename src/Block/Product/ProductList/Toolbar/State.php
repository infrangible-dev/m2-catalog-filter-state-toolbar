<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\CatalogFilterStateToolbar\Block\Product\ProductList\Toolbar;

use FeWeDev\Base\Variables;
use Infrangible\CatalogFilterStateToolbar\Block\Product\ProductList\Toolbar\State\Item as ItemBlock;
use Infrangible\Core\Helper\Layer as LayerHelper;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\Item;
use Magento\Framework\View\Element\Template;

/**
 * The applied filters of the layered navigation above the product list, each with a link that removes it, and a link
 * that removes all of them.
 */
class State extends Template
{
    /**
     * @param Template\Context $context
     * @param LayerHelper $layerHelper
     * @param Variables $variables
     * @param array $data
     * @phpstan-param array<string, mixed> $data
     */
    public function __construct(
        Template\Context $context,
        protected LayerHelper $layerHelper,
        protected Variables $variables,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Returns the applied filters of the product list.
     *
     * @return list<Item>
     */
    public function getActiveFilters(): array
    {
        return $this->layerHelper->getActiveFilters($this->getLayer());
    }

    /**
     * Returns the layer of the product list: the one of the block data "layer", or the layer of the current page.
     *
     * @return Layer
     */
    protected function getLayer(): Layer
    {
        $layer = $this->_getData('layer');

        if (! $layer instanceof Layer) {
            $layer = $this->layerHelper->getLayer();

            $this->setData(
                'layer',
                $layer
            );
        }

        return $layer;
    }

    /**
     * Renders an applied filter with the child block "item".
     *
     * @param Item $filter
     *
     * @return string
     */
    public function renderFilter(Item $filter): string
    {
        $itemBlock = $this->getChildBlock('item');

        if ($itemBlock instanceof ItemBlock) {
            $itemBlock->setFilter($filter);

            return $itemBlock->toHtml();
        }

        return '';
    }

    /**
     * Renders the link that removes all applied filters, the child block "clear".
     *
     * @return string
     */
    public function renderClear(): string
    {
        return $this->getChildHtml('clear');
    }

    /**
     * Returns a value of the block data as string, for example a CSS class from the layout.
     *
     * @param string $key
     *
     * @return string
     */
    public function getDataString(string $key): string
    {
        return $this->variables->stringValueOrDefault($this->getData($key));
    }
}
