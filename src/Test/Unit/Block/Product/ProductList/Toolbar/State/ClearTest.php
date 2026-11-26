<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\CatalogFilterStateToolbar\Test\Unit\Block\Product\ProductList\Toolbar\State;

use FeWeDev\Base\Variables;
use Infrangible\CatalogFilterStateToolbar\Block\Product\ProductList\Toolbar\State\Clear;
use Infrangible\Core\Helper\Layer as LayerHelper;
use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\Catalog\Model\Layer\Filter\Item;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

/**
 * Tests the link that removes all applied filters.
 */
#[AllowMockObjectsWithoutExpectations]
class ClearTest extends TestCase
{
    /**
     * The URL of the current page sets the parameters of all applied filters to their clean value (removes them);
     * filters without filter model are skipped.
     *
     * @return void
     */
    public function testClearUrl(): void
    {
        $urlBuilder = $this->createMock(UrlInterface::class);
        $urlBuilder->expects($this->once())->method('getUrl')->with(
            '*/*/*',
            [
                '_current'     => true,
                '_use_rewrite' => true,
                '_query'       => ['color' => null, 'price' => null],
                '_escape'      => true
            ]
        )->willReturn('https://example.com/gear.html');

        $context = $this->createStub(Context::class);
        $context->method('getUrlBuilder')->willReturn($urlBuilder);

        $broken = $this->createStub(Item::class);
        $broken->method('getFilter')->willThrowException(new LocalizedException(__('The filter must be an object.')));

        $layerHelper = $this->createStub(LayerHelper::class);
        $layerHelper->method('getActiveFilters')->willReturn(
            [$this->createItem('color'), $broken, $this->createItem('price')]
        );

        $block = new Clear($context, $layerHelper, new Variables());

        $this->assertSame('https://example.com/gear.html', $block->getClearUrl());
    }

    /**
     * Returns an applied filter with the request variable of its filter.
     *
     * @param string $requestVar
     *
     * @return Item
     */
    private function createItem(string $requestVar): Item
    {
        $filter = $this->createStub(AbstractFilter::class);
        $filter->method('getRequestVar')->willReturn($requestVar);
        $filter->method('getCleanValue')->willReturn(null);

        $item = $this->createStub(Item::class);
        $item->method('getFilter')->willReturn($filter);

        return $item;
    }
}
