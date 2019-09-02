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
 * Class CachePlugin
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
    private $_authorization;

    /**
     * CachePlugin constructor.
     *
     * @param CacheButton $cacheButton
     * @param Context $context
     */
    public function __construct(CacheButton $cacheButton, AuthorizationInterface $authorization)
    {
        $this->_authorization = $authorization;
        $this->cacheButton = $cacheButton;
    }

    /**
     * @param Cache $subject
     * @param LayoutInterface $layout
     */
    public function beforeSetLayout(
        Cache $subject,
        LayoutInterface $layout
    ) {
        if($this->_authorization->isAllowed('Pronko_SelectiveCache::flush_invalidated_cache')) {
            $this->cacheButton->execute($subject);
        }
    }
}
