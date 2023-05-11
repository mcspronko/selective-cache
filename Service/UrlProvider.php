<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Service;

use Magento\Backend\Model\UrlInterface;

class UrlProvider
{
    /**
     * Flush Invalidated Cache Types URI
     * @var string
     */
    private const FLUSH_CACHE_URI = 'pronko_selectivecache/cache/flushInvalidated';

    /**
     * @var UrlInterface
     */
    private UrlInterface $url;

    /**
     * @param UrlInterface $url
     */
    public function __construct(UrlInterface $url)
    {
        $this->url = $url;
    }

    /**
     * Provides the url for flushing invalidated cache types
     *
     * @return string
     */
    public function getFlushInvalidatedUrl(): string
    {
        return $this->url->getUrl(self::FLUSH_CACHE_URI);
    }
}
