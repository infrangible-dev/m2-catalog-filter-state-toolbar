<?php

declare(strict_types=1);

namespace Infrangible\CatalogFilterStateToolbar\Block\Product\ProductList\Toolbar;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class State extends Template
{
    /** @var \Magento\Catalog\Model\Layer\Resolver */
    private $layerResolver;

    public function __construct(
        Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );

        $this->layerResolver = $layerResolver;
    }

    /**
     * @return \Magento\Catalog\Model\Layer\Filter\Item[]
     */
    public function getActiveFilters(): array
    {
        $filters = $this->getLayer()->getState()->getFilters();

        if (! is_array($filters)) {
            $filters = [];
        }

        return $filters;
    }

    protected function getLayer(): \Magento\Catalog\Model\Layer
    {
        if (! $this->hasData('layer')) {
            $this->setData(
                'layer',
                $this->layerResolver->get()
            );
        }

        return $this->_getData('layer');
    }

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
