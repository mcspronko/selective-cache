<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Test\Unit\Service;

use Magento\Framework\Escaper;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Magento\Backend\Block\Cache;
use Pronko\SelectiveCache\Service\CacheButton;
use Pronko\SelectiveCache\Service\UrlProvider;

/**
 * Class CacheButtonTest creates TestCase for button Creation
 */
class CacheButtonTest extends TestCase
{
    /**
     * @var CacheButton
     */
    private CacheButton $object;

    /**
     * @var MockObject
     */
    private MockObject $cache;

    /**
     * @var MockObject
     */
    private MockObject $url;

    /**
     * @var MockObject
     */
    private MockObject $escaper;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->cache = $this->getMockBuilder(Cache::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->url = $this->getMockBuilder(UrlProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->escaper = $this->getMockBuilder(Escaper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new CacheButton($this->url, $this->escaper);
    }

    /**
     * @return void
     */
    public function testExecute(): void
    {
        $url = 'https://www.example.com/pronko_selectivecache/index/flushInvalidated';
        $label = 'Refresh Invalidated Cache';
        $this->url->expects($this->any())
            ->method('getFlushInvalidatedUrl')
            ->willReturn($url);

        $this->escaper->method('escapeUrl')
            ->willReturn($url);
        $this->escaper->method('escapeHtml')
            ->willReturn($label);

        $buttonId = 'refresh_invalidated_cache';
        $buttonConfig = [
            'label' => __($label),
            'onclick' => 'setLocation(\'' . $url . '\')',
            'class' => 'primary flush-cache-magento'
        ];
        $this->cache->expects($this->once())
            ->method('addButton')
            ->with($buttonId, $buttonConfig);

        $this->object->execute($this->cache);
    }
}
