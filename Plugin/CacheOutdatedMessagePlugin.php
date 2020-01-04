<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types = 1);

namespace Pronko\SelectiveCache\Plugin;

use Magento\AdminNotification\Model\System\Message\CacheOutdated;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\UrlInterface;

/**
 * Class OutdatedCacheMessagePlugin
 */
class CacheOutdatedMessagePlugin
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * OutdatedCacheMessagePlugin constructor.
     *
     * @param UrlInterface           $urlBuilder
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        UrlInterface $urlBuilder,
        AuthorizationInterface $authorization
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->authorization = $authorization;
    }

    /**
     * @param CacheOutdated $subject
     * @param string        $result
     *
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetText(CacheOutdated $subject, string $result)
    {
        if ($this->authorization->isAllowed('Pronko_SelectiveCache::flush_invalidated_cache')) {
            $result .= __(
                '<br /> Additionally you can <a href="%1">Flush Invalidated Cache</a> directly',
                $this->getFlushInvalidatedOnlyUrl()
            );
        }

        return $result;
    }

    /**
     * @return string
     */
    private function getFlushInvalidatedOnlyUrl()
    {
        return $this->urlBuilder->getUrl('pronko_selectivecache/cache/flushInvalidated');
    }
}
