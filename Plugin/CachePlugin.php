<?php
declare(strict_types=1);

namespace Pronko\SelectiveCache\Plugin;

use Magento\Backend\Block\Cache;
use Magento\Framework\View\LayoutInterface;

/**
 * Class CachePlugin
 */
class CachePlugin
{
    /**
     * @param Cache $subject
     * @param LayoutInterface $layout
     */
    public function beforeSetLayout(
        Cache $subject,
        LayoutInterface $layout
    ) {
        $subject->addButton(
            'flush_invalidated_only',
            [
                'label' => __('Flush Invalidated Only'),
                'onclick' => 'setLocation(\'' . $this->getFlushInvalidatedOnlyUlr() . '\')',
                'class' => 'primary flush-cache-magento'
            ]
        );
    }

    /**
     * @return string
     */
    private function getFlushInvalidatedOnlyUlr()
    {
        return '';
    }
}
