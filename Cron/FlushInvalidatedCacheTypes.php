<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Cron;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Pronko\SelectiveCache\Logger\CleanCacheLogger;

/** Class FlushInvalidatedCacheTypes flushes invalidated cache types by cronjob */
class FlushInvalidatedCacheTypes
{

    /**
     * @var TypeListInterface
     */
    private $cacheTypeList;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var CleanCacheLogger
     */
    private $logger;

    /**
     * FlushInvalidatedCache constructor.
     *
     * @param TypeListInterfaceAlias $cacheTypeList
     * @param ScopeConfigInterface $scopeConfig
     * @param CleanCacheLogger $logger
     */

    public function __construct(
        TypeListInterface $cacheTypeList,
        ScopeConfigInterface $scopeConfig,
        CleanCacheLogger $logger
    ) {
        $this->cacheTypeList = $cacheTypeList;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * Method execute flushes invalidated Cache types
     *
     * @return void
     */

    public function execute()
    {
        $cronenabled = $this->scopeConfig->isSetFlag(
            'selectivecache/cron/enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if ($cronenabled==true) {
            foreach ($this->cacheTypeList->getInvalidated() as $invalidatedType) {
                $this->cacheTypeList->cleanType($invalidatedType->getData('id'));
                $cacheLabels[] = $invalidatedType->getData('cache_type');
            }

            if (!empty($cacheLabels)) {
                $this->logger->info(__("Cache types cleared automatically: %1", implode(', ', $cacheLabels)));
            }
        }
    }
}
