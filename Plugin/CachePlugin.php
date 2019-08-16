<?php
declare(strict_types=1);

namespace Pronko\SelectiveCache\Plugin;

use Magento\Backend\Block\Cache;
use Magento\Framework\View\LayoutInterface;
use Pronko\SelectiveCache\Service\CacheButton;

/**
 * Class CachePlugin
 */
class CachePlugin
{
    /**
     * @var CacheButton
     */
    private $cacheButton;

    /**
     * CachePlugin constructor.
     * @param CacheButton $cacheButton
     */
    public function __construct(CacheButton $cacheButton)
    {
        $this->cacheButton = $cacheButton;
    }

    /**
     * @param Cache $subject
     * @param LayoutInterface $layout
     */
    public function beforeSetLayout(
        Cache $subject,
        LayoutInterface $layout
    ) {
        $this->cacheButton->execute($subject);
    }
}
