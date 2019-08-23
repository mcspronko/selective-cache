<?php

namespace Pronko\SelectiveCache\Controller\Adminhtml\Cache;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\Controller\Adminhtml\Cache;
use Magento\Framework\DataObject;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class FlushInvalidated
 */
class FlushInvalidated extends Cache implements HttpGetActionInterface
{
    /**
     * @return ResultInterface
     */
    public function execute()
    {
        $flag = false;
        /** @var DataObject $invalidatedType */
        foreach ($this->_cacheTypeList->getInvalidated() as $invalidatedType) {
            $this->_cacheTypeList->cleanType($invalidatedType->getData('id'));
            $flag = true;
        }

        if ($flag) {
            $this->messageManager->addSuccessMessage(__("Invalidated caches have been successfully cleaned."));
        } else {
            $this->messageManager->addNoticeMessage(__("There are no invalidated cache types to be cleaned."));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('adminhtml/cache');
    }
}
