<?php
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
    protected $_cacheTypeList;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /** @var CleanCacheLogger */
    protected $logger;

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
        $this->_cacheTypeList = $cacheTypeList;
        $this->_scopeConfig = $scopeConfig;
        $this->_logger = $logger;
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
