<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Cron;

use Psr\Log\LoggerInterface;
use Magento\Framework\DataObject\Factory;
use Magento\Framework\Event\Manager as EventManager;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class FlushInvalidatedCacheTypes flushes invalidated cache types by cronjob
 */
class FlushInvalidatedCacheTypes
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var EventManager
     */
    private EventManager $eventManager;

    /**
     * @var Factory
     */
    private Factory $dataObjectFactory;

    /**
     * @param EventManager $eventManager
     * @param Factory $dataObjectFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        EventManager $eventManager,
        Factory $dataObjectFactory,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        $this->eventManager = $eventManager;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * Method execute flushes invalidated Cache types
     *
     * @return void
     */
    public function execute(): void
    {
        if (!$this->scopeConfig->isSetFlag('selectivecache/cron/enabled')) {
            return;
        }

        $cacheContainer = $this->dataObjectFactory->create();

        $this->eventManager->dispatch(
            'cache_flush_invalidated',
            ['cache_container' => $cacheContainer]
        );

        $labels = $cacheContainer->getData('labels');

        //TODO add configuration setting to enable/disable logging
        if (!empty($labels)) {
            $this->logger->info(
                sprintf("Cache types cleared automatically: %s", implode(', ', $labels))
            );
        }
    }
}
