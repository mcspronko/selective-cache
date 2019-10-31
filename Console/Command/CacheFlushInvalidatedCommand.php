<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Console\Command;

use Magento\Framework\DataObject;
use Magento\Framework\Event\Manager as EventManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for flushing invalidated cache types
 */
class CacheFlushInvalidatedCommand extends Command
{
    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * CacheFlushInvalidatedCommand constructor.
     * @param EventManager $eventManager
     */
    public function __construct(
        EventManager $eventManager
    ) {
        $this->eventManager = $eventManager;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('cache:refresh:invalidated');
        $this->setDescription('Flushes cache storage used by currently invalidated cache type(s)');
        parent::configure();
    }

    /**
     * Flushes invalidated cache types
     *
     * @param array $cacheTypes
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var DataObject $cacheContainer */
        $cacheContainer = new DataObject();

        $this->eventManager
            ->dispatch(
                'cache_flush_invalidated',
                ['cache_container' => $cacheContainer]
            );

        $output->writeln($this->getDisplayMessage($cacheContainer->getData('labels')));
    }

    /**
     * Returns an output message to be displayed on the CLI
     *
     * @param array $labels
     * @return string
     */
    protected function getDisplayMessage(array $labels)
    {
        if (!empty($labels)) {
            $message = "Flushed invalidated cache types: \n" . implode("\n", $labels);
        } else {
            $message = 'No invalidated caches were found.';
        }

        return $message;
    }
}
