<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Logger;

use Magento\Framework\Logger\Handler\Debug;

class DebugHandler extends Debug
{
    /**
     * @var string
     */
    protected $fileName = '/var/log/pronkoconsulting/selective_cache_debug.log';
}
