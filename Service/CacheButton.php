<?php

declare(strict_types=1);

namespace Pronko\SelectiveCache\Service;

use Magento\Backend\Block\Cache;
use Magento\Framework\UrlInterface;

/**
 * Class CacheButton
 */
class CacheButton
{
    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * CacheButton constructor.
     * @param UrlInterface $url
     */
    public function __construct(UrlInterface $url)
    {
        $this->url = $url;
    }

    /**
     * @param Cache $cache
     */
    public function execute(Cache $cache)
    {
        $cache->addButton(
            'flush_invalidated_only',
            [
                'label' => __('Flush Invalidated Only'),
                'onclick' => 'setLocation(\'' . $this->getFlushInvalidatedOnlyUlr() . '\')',
                'class' => 'primary flush-cache-magento'
            ]
        );
    }

    /**
     * @return string
     */
    private function getFlushInvalidatedOnlyUlr()
    {
        return $this->url->getUrl('adminhtml/pronko_selectivecache/flushInvalidated');
    }
}
