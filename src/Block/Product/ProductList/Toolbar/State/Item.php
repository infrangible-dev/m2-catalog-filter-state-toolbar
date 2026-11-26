<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\CatalogFilterStateToolbar\Block\Product\ProductList\Toolbar\State;

use FeWeDev\Base\Variables;
use Magento\Catalog\Model\Layer\Filter\Item as FilterItem;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;

/**
 * An applied filter with the link that removes it. The blocks of the child container "additional" that are items as
 * well get the same filter, for example an icon of a theme.
 */
class Item extends Template
{
    /** @var FilterItem|null the applied filter */
    private ?FilterItem $filter = null;

    /**
     * @param Template\Context $context
     * @param Variables $variables
     * @param array $data
     * @phpstan-param array<string, mixed> $data
     */
    public function __construct(
        Template\Context $context,
        protected Variables $variables,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Returns the applied filter, or null before it is set.
     *
     * @return FilterItem|null
     */
    public function getFilter(): ?FilterItem
    {
        return $this->filter;
    }

    /**
     * Sets the applied filter that the block shows.
     *
     * @param FilterItem $filter
     *
     * @return void
     */
    public function setFilter(FilterItem $filter): void
    {
        $this->filter = $filter;
    }

    /**
     * Returns the translated name of the filter, for example "Color".
     *
     * @return string
     */
    public function getFilterName(): string
    {
        return $this->filter === null ?
            '' : (string)__($this->variables->stringValueOrDefault($this->filter->getName()));
    }

    /**
     * Returns the applied value of the filter without HTML, for example "Red".
     *
     * The HTML of a price range becomes "$10.00 - $19.99"; the templates escape the value.
     *
     * @return string
     */
    public function getFilterValue(): string
    {
        return $this->filter === null ?
            '' : strip_tags($this->variables->stringValueOrDefault($this->filter->getData('label')));
    }

    /**
     * Returns the URL of the product list without the filter.
     *
     * @return string
     */
    public function getRemoveUrl(): string
    {
        return $this->filter === null ? '' : $this->filter->getRemoveUrl();
    }

    /**
     * Returns the title of the link that removes the filter, for example "Remove Color Red".
     *
     * @return string
     */
    public function getRemoveTitle(): string
    {
        return sprintf(
            '%s %s %s',
            __('Remove'),
            $this->getFilterName(),
            $this->getFilterValue()
        );
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

    /**
     * Renders the blocks of the child container "additional"; blocks that are items get the filter of this block.
     *
     * @return string
     */
    public function renderAdditional(): string
    {
        try {
            $layout = $this->getLayout();
        } catch (LocalizedException) {
            return '';
        }

        $additionalContainerName = $layout->getChildName(
            $this->getNameInLayout(),
            'additional'
        );

        if (! is_string($additionalContainerName)) {
            return '';
        }

        $childHtml = '';

        foreach ($layout->getChildBlocks($additionalContainerName) as $childBlock) {
            if ($childBlock instanceof Item && $this->filter !== null) {
                $childBlock->setFilter($this->filter);
            }

            $childHtml .= $childBlock->toHtml();
        }

        return $childHtml;
    }

    /**
     * Renders nothing before the filter is set.
     *
     * @return string
     */
    protected function _toHtml(): string
    {
        return $this->filter === null ? '' : parent::_toHtml();
    }
}
