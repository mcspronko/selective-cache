<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Test\Unit\Service;

use Magento\Backend\Block\Cache;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pronko\SelectiveCache\Service\CacheButton;
use Magento\Framework\UrlInterface;

/**
 * Class CacheButtonTest creates TestCase for button Creation
 */
class CacheButtonTest extends TestCase
{
    /**
     * @var CacheButton
     */
    private $object;

    /**
     * @var Cache|MockObject
     */
    private $cache;

    /**
     * @var UrlInterface|MockObject
     */
    private $url;

    protected function setUp()
    {
        $this->cache = $this->getMockBuilder(Cache::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->url = $this->getMockBuilder(UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new CacheButton($this->url);
    }

    public function testExecute()
    {
        $url = 'https://www.example.com/pronko_selectivecache/index/flushInvalidated';
        $this->url->expects($this->any())
            ->method('getUrl')
            ->with('pronko_selectivecache/*/flushInvalidated')
            ->willReturn($url);

        $buttonId = 'refresh_invalidated_cache';
        $buttonConfig = [
            'label' => __('Refresh Invalidated Cache'),
            'onclick' => 'setLocation(\'' . $url . '\')',
            'class' => 'primary flush-cache-magento'
        ];
        $this->cache->expects($this->once())
            ->method('addButton')
            ->with($buttonId, $buttonConfig);

        $this->object->execute($this->cache);
    }
}
