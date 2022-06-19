<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Observer;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class FlushInvalidatedCache gets cache types to display after flushing
 */

class FlushInvalidatedCache implements ObserverInterface
{
    /**
     * @var TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * FlushInvalidatedCache constructor.
     * @param TypeListInterfaceAlias $cacheTypeList
     */
    public function __construct(TypeListInterface $cacheTypeList)
    {
        $this->_cacheTypeList = $cacheTypeList;
    }

    /**
     * Flush Invalidated cache
     *
     * @param Observer $observer
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(Observer $observer)
    {
        /** @var DataObject $cacheContainer */
        $cacheContainer = $observer->getEvent()->getCacheContainer();

        $cacheLabels = [];
        /** @var DataObject $invalidatedType */
        foreach ($this->_cacheTypeList->getInvalidated() as $invalidatedType) {
            $this->_cacheTypeList->cleanType($invalidatedType->getData('id'));
            $cacheLabels[] = $invalidatedType->getData('cache_type');
        }
        $cacheContainer->setData('labels', $cacheLabels);
    }
}
