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
use Pronko\SelectiveCache\Service\UrlProvider;

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
     * @var UrlProvider
     */
    private UrlProvider $url;

    /**
     * @var Escaper
     */
    private Escaper $escaper;

    /**
     * @param AuthorizationInterface $authorization
     * @param TypeListInterface $cacheTypeList
     * @param UrlProvider $url
     * @param Escaper $escaper
     */
    public function __construct(
        AuthorizationInterface $authorization,
        TypeListInterface $cacheTypeList,
        UrlProvider $url,
        Escaper $escaper
    ) {
        $this->authorization = $authorization;
        $this->cacheTypeList = $cacheTypeList;
        $this->url = $url;
        $this->escaper = $escaper;
    }

    /**
     * Returns a link to flush invalidated cache
     *
     * @return string
     */
    public function getText(): string
    {
        $link = sprintf(
            '<a href="%s">%s</a>',
            $this->url->getFlushInvalidatedUrl(),
            $this->escaper->escapeHtml(__('Flush Invalidated Cache'))
        );

        return $this->escaper->escapeHtml(__('Invalidated cache type(s)')) .
            ': ' .
            implode(', ', $this->getInvalidatedCacheTypes()) .
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
        return implode('|', $this->getInvalidatedCacheTypes());
    }

    /**
     * Check whether there are invalidated cache types in the system.
     * It also checks for permissions to show the message.
     *
     * @return bool
     */
    public function isDisplayed(): bool
    {
        return $this->authorization->isAllowed('Pronko_SelectiveCache::flush_invalidated_cache')
            && $this->getInvalidatedCacheTypes();
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
     * Retrieve invalidated cache types
     *
     * @return array
     */
    private function getInvalidatedCacheTypes(): array
    {
        $output = [];
        foreach ($this->cacheTypeList->getInvalidated() as $type) {
            $output[] = $type->getCacheType();
        }
        return $output;
    }
}
