<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Test\Unit\Plugin;

use Magento\Framework\AuthorizationInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\View\LayoutInterface;
use Magento\Backend\Block\Cache;
use Pronko\SelectiveCache\Plugin\CachePlugin;
use Pronko\SelectiveCache\Service\CacheButton;

/**
 * Class CachePluginTest creates Testcase for CachePlugin
 */
class CachePluginTest extends TestCase
{
    /**
     * @var CachePlugin
     */
    private $object;

    /**
     * @var CacheButton|MockObject
     */
    private $cacheButton;

    /**
     * @var Cache|MockObject
     */
    private $cache;

    /**
     * @var LayoutInterface|MockObject
     */
    private $layout;

    /**
     * @var AuthorizationInterface|MockObject
     */
    private $authorization;

    protected function setUp()
    {
        $this->cacheButton = $this->getMockBuilder(CacheButton::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cache = $this->getMockBuilder(Cache::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->layout = $this->getMockBuilder(LayoutInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->authorization = $this->getMockBuilder(AuthorizationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->authorization->method('isAllowed')
            ->with('Pronko_SelectiveCache::flush_invalidated_cache')
            ->willReturn(true);

        $this->object = new CachePlugin($this->cacheButton, $this->authorization);
    }

    public function testBeforeSetLayout()
    {
        $this->cacheButton->expects($this->once())
            ->method('execute')
            ->with($this->cache);

        $this->object->beforeSetLayout($this->cache, $this->layout);
    }
}
