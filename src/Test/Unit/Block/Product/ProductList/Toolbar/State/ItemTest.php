<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\CatalogFilterStateToolbar\Test\Unit\Block\Product\ProductList\Toolbar\State;

use FeWeDev\Base\Variables;
use Infrangible\CatalogFilterStateToolbar\Block\Product\ProductList\Toolbar\State\Item;
use Magento\Catalog\Model\Layer\Filter\Item as FilterItem;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\LayoutInterface;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Tests the block of an applied filter.
 */
#[AllowMockObjectsWithoutExpectations]
class ItemTest extends TestCase
{
    /**
     * Returns labels of applied filters and the value that the block shows.
     *
     * @return array<string, array{mixed, string}>
     */
    public static function valueProvider(): array
    {
        return [
            'option'       => ['Red', 'Red'],
            'ampersand'    => ['Black & White', 'Black & White'],
            'price range'  => [
                '<span class="price">$10.00</span> - <span class="price">$19.99</span>',
                '$10.00 - $19.99'
            ],
            'no label'     => [null, '']
        ];
    }

    /**
     * Tests the name, the value and the title of the link that removes the filter.
     *
     * @dataProvider valueProvider
     *
     * @param mixed $label
     * @param string $expected
     *
     * @return void
     */
    #[DataProvider('valueProvider')]
    public function testFilter(mixed $label, string $expected): void
    {
        $filter = $this->createStub(FilterItem::class);
        $filter->method('getName')->willReturn('Color');
        $filter->method('getData')->willReturnMap([['label', null, $label]]);
        $filter->method('getRemoveUrl')->willReturn('https://example.com/gear.html');

        $block = $this->createBlock();
        $block->setFilter($filter);

        $this->assertSame($filter, $block->getFilter());
        $this->assertSame('Color', $block->getFilterName());
        $this->assertSame($expected, $block->getFilterValue());
        $this->assertSame('Remove Color ' . $expected, $block->getRemoveTitle());
        $this->assertSame('https://example.com/gear.html', $block->getRemoveUrl());
    }

    /**
     * Before the filter is set, the block has no values and renders nothing.
     *
     * @return void
     */
    public function testWithoutFilter(): void
    {
        $block = $this->createBlock();

        $this->assertNull($block->getFilter());
        $this->assertSame('', $block->getFilterName());
        $this->assertSame('', $block->getFilterValue());
        $this->assertSame('', $block->getRemoveUrl());
        $this->assertSame('', $block->toHtml());
    }

    /**
     * The blocks of the child container "additional" are rendered; the ones that are items get the filter.
     *
     * @return void
     */
    public function testRenderAdditional(): void
    {
        $filter = $this->createStub(FilterItem::class);

        $icon = $this->createMock(Item::class);
        $icon->expects($this->once())->method('setFilter')->with($filter);
        $icon->method('toHtml')->willReturn('<a>icon</a>');

        $other = $this->createStub(AbstractBlock::class);
        $other->method('toHtml')->willReturn('<span>other</span>');

        $layout = $this->createStub(LayoutInterface::class);
        $layout->method('getChildName')->willReturn('state.item.additional');
        $layout->method('getChildBlocks')->willReturnMap([['state.item.additional', [$icon, $other]]]);

        $block = $this->createBlock();
        $block->setLayout($layout);
        $block->setFilter($filter);

        $this->assertSame('<a>icon</a><span>other</span>', $block->renderAdditional());
    }

    /**
     * Without the child container "additional" nothing is rendered.
     *
     * @return void
     */
    public function testRenderAdditionalWithoutContainer(): void
    {
        $layout = $this->createStub(LayoutInterface::class);
        $layout->method('getChildName')->willReturn(false);

        $block = $this->createBlock();
        $block->setLayout($layout);

        $this->assertSame('', $block->renderAdditional());
    }

    /**
     * Returns the block.
     *
     * @return Item
     */
    private function createBlock(): Item
    {
        $context = $this->createStub(Context::class);
        $context->method('getEventManager')->willReturn($this->createStub(ManagerInterface::class));
        $context->method('getScopeConfig')->willReturn($this->createStub(ScopeConfigInterface::class));

        return new Item($context, new Variables());
    }
}
