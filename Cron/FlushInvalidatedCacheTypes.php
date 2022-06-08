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
    private $_cacheTypeList;

    /**
     * @var ScopeConfigInterface
     */
    private $_scopeConfig;

    /**
     * @var CleanCacheLogger
     */
    private $_logger;

    /**
     * FlushInvalidatedCache constructor.
     *
     * @param TypeListInterfaceAlias $_cacheTypeList
     * @param ScopeConfigInterface $_scopeConfig
     * @param CleanCacheLogger $_logger
     */

    public function __construct(
        TypeListInterface $_cacheTypeList,
        ScopeConfigInterface $_scopeConfig,
        CleanCacheLogger $_logger
    ) {
        $this->_cacheTypeList = $_cacheTypeList;
        $this->_scopeConfig = $_scopeConfig;
        $this->_logger = $_logger;
    }

    /**
     * Method execute flushes invalidated Cache types
     *
     * @return void
     */

    public function execute()
    {
        $cronenabled = $this->getConfig('selectivecache/general/enabled');
        if ($cronenabled==true) {
            foreach ($this->_cacheTypeList->getInvalidated() as $invalidatedType) {
                $this->_cacheTypeList->cleanType($invalidatedType->getData('id'));
                $cacheLabels[] = $invalidatedType->getData('cache_type');
            }

            if (!empty($cacheLabels)) {
                $logoutput = implode(", ", $cacheLabels);
                $this->_logger->info(__("Following cache types were automatically cleared: ").$logoutput);
            }
        }
    }

    /**
     * Get Configuration values
     *
     * @param string $config_path
     * @return string|null
     */

    public function getConfig(string $config_path): ?string
    {
        return $this->_scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
