<?php

namespace Pronko\SelectiveCache\Controller\Adminhtml\Cache;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Backend\Controller\Adminhtml\Cache;

/**
 * Class FlushInvalidated
 */
class FlushInvalidated extends Cache implements HttpGetActionInterface
{
    public function execute()
    {
        $this->messageManager->addSuccessMessage(__("The Flush Invalidated Only logic has to be implemented here."));
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('adminhtml/*');
    }
}
