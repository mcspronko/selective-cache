<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Plugin;

use Magento\AdminNotification\Model\System\Message\CacheOutdated;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;

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
     * @var Escaper
     */
    private $escaper;

    /**
     * CacheOutdatedMessagePlugin constructor.
     * @param UrlInterface $urlBuilder
     * @param AuthorizationInterface $authorization
     * @param Escaper $escaper
     */
    public function __construct(
        UrlInterface $urlBuilder,
        AuthorizationInterface $authorization,
        Escaper $escaper
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->authorization = $authorization;
        $this->escaper = $escaper;
    }

    /**
     * Method afterGetText
     *
     * @param CacheOutdated $subject
     * @param string        $result
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */

    public function afterGetText(CacheOutdated $subject, string $result)
    {
        if ($this->authorization->isAllowed('Pronko_SelectiveCache::flush_invalidated_cache')) {
            $link = sprintf(
                '<a href="%s">%s</a>',
                $this->getFlushInvalidatedOnlyUrl(),
                $this->escaper->escapeHtml(__('Flush Invalidated Cache'))
            );

            $result .= '<br />' . $this->escaper->escapeHtml(__(
                'Additionally you can %1 directly.',
                $link
            ), ['a']);
        }

        return $result;
    }

    /**
     * Method getFlushInvalidatedOnlyUrl gets Url for flushing only invalidated cache types
     *
     * @return string
     */
    private function getFlushInvalidatedOnlyUrl(): string
    {
        return $this->urlBuilder->getUrl('pronko_selectivecache/cache/flushInvalidated');
    }
}
