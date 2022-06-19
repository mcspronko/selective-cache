<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Plugin;

use Magento\Backend\Block\Cache;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\LayoutInterface;
use Pronko\SelectiveCache\Service\CacheButton;

/**
 * Class CachePlugin for creating Button in Backend
 */

class CachePlugin
{
    /**
     * @var CacheButton
     */
    private $cacheButton;

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * CachePlugin constructor.
     * @param CacheButton $cacheButton
     * @param AuthorizationInterface $authorization
     */
    public function __construct(CacheButton $cacheButton, AuthorizationInterface $authorization)
    {
        $this->authorization = $authorization;
        $this->cacheButton = $cacheButton;
    }

    /**
     * Method beforeSetLayout
     *
     * @param Cache $subject
     * @param LayoutInterface $layout
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return void
     */

    public function beforeSetLayout(Cache $subject, LayoutInterface $layout)
    {
        if ($this->authorization->isAllowed('Pronko_SelectiveCache::flush_invalidated_cache')) {
            $this->cacheButton->execute($subject);
        }
    }
}
