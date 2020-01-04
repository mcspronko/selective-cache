<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Controller\Adminhtml\Cache;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Controller\ResultInterface;
use Magento\Backend\Controller\Adminhtml\Cache;

/**
 * Class FlushInvalidated
 */
class FlushInvalidated extends Cache implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Pronko_SelectiveCache::flush_invalidated_cache';

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        $cacheLabels = [];

        /** @var DataObject $invalidatedType */
        foreach ($this->_cacheTypeList->getInvalidated() as $invalidatedType) {
            $this->_cacheTypeList->cleanType($invalidatedType->getData('id'));
            $cacheLabels[] = $invalidatedType->getData('cache_type');
        }

        if (!empty($cacheLabels)) {
            $this->messageManager->addSuccessMessage(
                __("The following cache types have been successfully cleaned: %1", implode(', ', $cacheLabels))
            );
        } else {
            $this->messageManager->addNoticeMessage(__("There are no invalidated cache types to be cleaned."));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath($this->_redirect->getRefererUrl());
    }
}
