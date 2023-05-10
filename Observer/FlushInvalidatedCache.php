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
    private TypeListInterface $cacheTypeList;

    /**
     * @param TypeListInterface $cacheTypeList
     */
    public function __construct(TypeListInterface $cacheTypeList)
    {
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        /** @var DataObject $cacheContainer */
        $cacheContainer = $observer->getEvent()->getCacheContainer();

        $cacheLabels = [];
        /** @var DataObject $invalidatedType */
        foreach ($this->cacheTypeList->getInvalidated() as $invalidatedType) {
            $this->cacheTypeList->cleanType($invalidatedType->getData('id'));
            $cacheLabels[] = $invalidatedType->getData('cache_type');
        }
        $cacheContainer->setData('labels', $cacheLabels);
    }
}
