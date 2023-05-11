<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Notification;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Notification\MessageInterface;
use Magento\Framework\UrlInterface;

/**
 * Cache Types message with the link to refresh invalidated cache types
 */
class CacheInvalidated implements MessageInterface
{
    /**
     * @var AuthorizationInterface
     */
    private AuthorizationInterface $authorization;

    /**
     * @var TypeListInterface
     */
    private TypeListInterface $cacheTypeList;

    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @var Escaper
     */
    private Escaper $escaper;

    /**
     * @param AuthorizationInterface $authorization
     * @param TypeListInterface $cacheTypeList
     * @param UrlInterface $urlBuilder
     * @param Escaper $escaper
     */
    public function __construct(
        AuthorizationInterface $authorization,
        TypeListInterface $cacheTypeList,
        UrlInterface $urlBuilder,
        Escaper $escaper
    ) {
        $this->authorization = $authorization;
        $this->cacheTypeList = $cacheTypeList;
        $this->urlBuilder = $urlBuilder;
        $this->escaper = $escaper;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        $link = sprintf(
            '<a href="%s">%s</a>',
            $this->getFlushInvalidatedUrl(),
            $this->escaper->escapeHtml(__('Flush Invalidated Cache'))
        );

        return $this->escaper->escapeHtml(__('Invalidated cache type(s)')) .
            ': ' .
            implode(', ', $this->getCacheTypesForRefresh()) .
            '. ' .
            $link;
    }

    /**
     * Retrieve unique message identity
     *
     * @return string
     */
    public function getIdentity(): string
    {
        return implode('|', $this->getCacheTypesForRefresh());
    }

    /**
     * Check whether
     *
     * @return bool
     */
    public function isDisplayed(): bool
    {
        return $this->authorization->isAllowed('Pronko_SelectiveCache::flush_invalidated_cache')
            && $this->getCacheTypesForRefresh();
    }

    /**
     * Retrieve message severity
     *
     * @return int
     */
    public function getSeverity(): int
    {
        return MessageInterface::SEVERITY_CRITICAL;
    }

    /**
     * @return array
     */
    private function getCacheTypesForRefresh(): array
    {
        $output = [];
        foreach ($this->cacheTypeList->getInvalidated() as $type) {
            $output[] = $type->getCacheType();
        }
        return $output;
    }

    /**
     * Method getFlushInvalidatedOnlyUrl gets Url for flushing only invalidated cache types
     *
     * @return string
     */
    private function getFlushInvalidatedUrl(): string
    {
        return $this->urlBuilder->getUrl('pronko_selectivecache/cache/flushInvalidated');
    }
}
