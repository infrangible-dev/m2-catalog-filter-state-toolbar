<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\CatalogFilterStateToolbar\Test\Unit\Block\Product\ProductList\Toolbar;

use FeWeDev\Base\Variables;
use Infrangible\CatalogFilterStateToolbar\Block\Product\ProductList\Toolbar\State;
use Infrangible\CatalogFilterStateToolbar\Block\Product\ProductList\Toolbar\State\Item as ItemBlock;
use Infrangible\Core\Helper\Layer as LayerHelper;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\Item;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\LayoutInterface;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

/**
 * Tests the block with the applied filters above the product list.
 */
#[AllowMockObjectsWithoutExpectations]
class StateTest extends TestCase
{
    /**
     * The applied filters are the ones of the layer of the page, which is read once.
     *
     * @return void
     */
    public function testActiveFilters(): void
    {
        $layer = $this->createStub(Layer::class);
        $filters = [$this->createStub(Item::class), $this->createStub(Item::class)];

        $layerHelper = $this->createMock(LayerHelper::class);
        $layerHelper->expects($this->once())->method('getLayer')->willReturn($layer);
        $layerHelper->method('getActiveFilters')->with($layer)->willReturn($filters);

        $block = new State($this->createStub(Context::class), $layerHelper, new Variables());

        $this->assertSame($filters, $block->getActiveFilters());
        $this->assertSame($filters, $block->getActiveFilters());
    }

    /**
     * An applied filter is rendered with the child block "item", which gets the filter.
     *
     * @return void
     */
    public function testRenderFilter(): void
    {
        $filter = $this->createStub(Item::class);

        $itemBlock = $this->createMock(ItemBlock::class);
        $itemBlock->expects($this->once())->method('setFilter')->with($filter);
        $itemBlock->method('toHtml')->willReturn('<li>Color Red</li>');

        $layout = $this->createStub(LayoutInterface::class);
        $layout->method('getChildName')->willReturn('state.item');
        $layout->method('getBlock')->willReturn($itemBlock);

        $block = new State($this->createStub(Context::class), $this->createStub(LayerHelper::class), new Variables());
        $block->setLayout($layout);

        $this->assertSame('<li>Color Red</li>', $block->renderFilter($filter));
    }

    /**
     * Without the child block "item" nothing is rendered.
     *
     * @return void
     */
    public function testRenderFilterWithoutItemBlock(): void
    {
        $layout = $this->createStub(LayoutInterface::class);
        $layout->method('getChildName')->willReturn(false);

        $block = new State($this->createStub(Context::class), $this->createStub(LayerHelper::class), new Variables());
        $block->setLayout($layout);

        $this->assertSame('', $block->renderFilter($this->createStub(Item::class)));
    }

    /**
     * The CSS classes from the layout are strings.
     *
     * @return void
     */
    public function testDataString(): void
    {
        $block = new State($this->createStub(Context::class), $this->createStub(LayerHelper::class), new Variables());
        $block->setData('itemsClass', 'items');
        $block->setData('filtersClass', ['no', 'string']);

        $this->assertSame('items', $block->getDataString('itemsClass'));
        $this->assertSame('', $block->getDataString('filtersClass'));
        $this->assertSame('', $block->getDataString('missing'));
    }
}
