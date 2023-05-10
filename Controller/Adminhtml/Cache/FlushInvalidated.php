<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Controller\Adminhtml\Cache;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject\Factory;
use Magento\Framework\Event\Manager as EventManager;
use Magento\Backend\App\Action;

/**
 * Class FlushInvalidated triggers Cache Flush and creates reloads the page
 */
class FlushInvalidated extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Pronko_SelectiveCache::flush_invalidated_cache';

    /**
     * @var EventManager
     */
    private EventManager $eventManager;

    /**
     * @var Factory
     */
    private Factory $dataObjectFactory;

    /**
     * @param Action\Context $context
     * @param EventManager $eventManager
     * @param Factory $dataObjectFactory
     */
    public function __construct(
        Action\Context $context,
        EventManager $eventManager,
        Factory $dataObjectFactory
    ) {
        $this->eventManager = $eventManager;
        $this->dataObjectFactory = $dataObjectFactory;
        parent::__construct($context);
    }

    /**
     * Method execute
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $cacheContainer = $this->dataObjectFactory->create();

        $this->eventManager->dispatch(
            'cache_flush_invalidated',
            ['cache_container' => $cacheContainer]
        );

        $labels = $cacheContainer->getData('labels');

        if (!empty($labels)) {
            $this->messageManager->addSuccessMessage(
                __(
                    '%1 cache type(s) refreshed.',
                    implode(', ', $labels)
                )
            );
        } else {
            $this->messageManager->addNoticeMessage(
                __('There are no invalidated cache types to be cleaned.')
            );
        }

        return $this->resultRedirectFactory->create()->setRefererOrBaseUrl();
    }
}
