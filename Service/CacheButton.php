<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Service;

use Magento\Backend\Block\Cache;
use Magento\Framework\Escaper;

/**
 * Class CacheButton creates a button to refresh invalidated cache types
 */
class CacheButton
{
    /**
     * @var UrlProvider
     */
    private UrlProvider $url;

    /**
     * @var Escaper
     */
    private Escaper $escaper;

    /**
     * @param UrlProvider $url
     * @param Escaper $escaper
     */
    public function __construct(
        UrlProvider $url,
        Escaper $escaper
    ) {
        $this->url = $url;
        $this->escaper = $escaper;
    }

    /**
     * Adds the "Refresh Invalidated Cache" button to the Cache block class
     *
     * @param Cache $cache
     * @return void
     */
    public function execute(Cache $cache): void
    {
        $cache->addButton(
            'refresh_invalidated_cache',
            [
                'label' => $this->escaper->escapeHtml(__('Refresh Invalidated Cache')),
                'onclick' => sprintf(
                    "setLocation('%s')",
                    $this->escaper->escapeUrl($this->url->getFlushInvalidatedUrl())
                ),
                'class' => 'primary flush-cache-magento'
            ]
        );
    }
}
