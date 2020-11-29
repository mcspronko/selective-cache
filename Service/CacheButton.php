<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Service;

use Magento\Backend\Block\Cache;
use Magento\Framework\Escaper;
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
     * @var Escaper
     */
    private $escaper;

    /**
     * CacheButton constructor.
     * @param UrlInterface $url
     * @param Escaper $escaper
     */
    public function __construct(
        UrlInterface $url,
        Escaper $escaper
    ) {
        $this->url = $url;
        $this->escaper = $escaper;
    }

    /**
     * @param Cache $cache
     */
    public function execute(Cache $cache): void
    {
        $cache->addButton(
            'refresh_invalidated_cache',
            [
                'label' => $this->escaper->escapeHtml(__('Refresh Invalidated Cache')),
                'onclick' => sprintf("setLocation('%s')", $this->getFlushInvalidatedOnlyUrl()),
                'class' => 'primary flush-cache-magento'
            ]
        );
    }

    /**
     * @return string
     */
    private function getFlushInvalidatedOnlyUrl(): string
    {
        return $this->escaper->escapeUrl(
            $this->url->getUrl('pronko_selectivecache/*/flushInvalidated')
        );
    }
}
