<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Controller\Adminhtml\Cache;

use Magento\Backend\App\Action;
use Magento\Backend\Controller\Adminhtml\Cache;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\App\Cache\StateInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Manager as EventManager;
use Magento\Framework\View\Result\PageFactory;

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
     * @var Action\Context
     */
    private $context;
    /**
     * @var TypeListInterface
     */
    private $cacheTypeList;
    /**
     * @var StateInterface
     */
    private $cacheState;
    /**
     * @var Pool
     */
    private $cacheFrontendPool;
    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * FlushInvalidated constructor.
     * @param Action\Context $context
     * @param TypeListInterface $cacheTypeList
     * @param StateInterface $cacheState
     * @param Pool $cacheFrontendPool
     * @param PageFactory $resultPageFactory
     * @param EventManager $eventManager
     */
    public function __construct(
        Action\Context $context,
        TypeListInterface $cacheTypeList,
        StateInterface $cacheState,
        Pool $cacheFrontendPool,
        PageFactory $resultPageFactory,
        EventManager $eventManager
    ) {
        parent::__construct($context, $cacheTypeList, $cacheState, $cacheFrontendPool, $resultPageFactory);

        $this->context = $context;
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheState = $cacheState;
        $this->cacheFrontendPool = $cacheFrontendPool;
        $this->resultPageFactory = $resultPageFactory;
        $this->eventManager = $eventManager;
    }

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var DataObject $cacheContainer */
        $cacheContainer = new DataObject();

        $this->eventManager
            ->dispatch(
                'cache_flush_invalidated',
                ['cache_container' => $cacheContainer]
            );

        $cacheLabels = $cacheContainer->getData('labels');

        if (!empty($cacheLabels)) {
            $this->messageManager->addSuccessMessage(
                __("The following cache types have been successfully cleaned: %1", implode(', ', $cacheLabels))
            );
        } else {
            $this->messageManager->addNoticeMessage(__("There are no invalidated cache types to be cleaned."));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('adminhtml/cache');
    }
}
