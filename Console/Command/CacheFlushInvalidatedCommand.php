<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\SelectiveCache\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\DataObject\Factory;
use Magento\Framework\Event\Manager as EventManager;

/**
 * Command for flushing invalidated cache types
 */
class CacheFlushInvalidatedCommand extends Command
{
    /**
     * @var EventManager
     */
    private EventManager $eventManager;

    /**
     * @var Factory
     */
    private Factory $dataObjectFactory;

    /**
     * @param EventManager $eventManager
     * @param Factory $dataObjectFactory
     * @param string $name
     */
    public function __construct(
        EventManager $eventManager,
        Factory $dataObjectFactory,
        string $name = 'cache:refresh:invalidated'
    ) {
        $this->eventManager = $eventManager;
        $this->dataObjectFactory = $dataObjectFactory;
        parent::__construct($name);
    }

    /**
     * Configure method
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('Flushes cache storage used by currently invalidated cache type(s)');
        parent::configure();
    }

    /**
     * Execute method
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cacheContainer = $this->dataObjectFactory->create();

        $this->eventManager->dispatch(
            'cache_flush_invalidated',
            ['cache_container' => $cacheContainer]
        );

        $labels = $cacheContainer->getData('labels');
        if (empty($labels)) {
            $output->writeln('No invalidated caches were found.');
            return Command::SUCCESS;
        }
        $output->writeln('Flushed invalidated cache types:');
        foreach ($labels as $label) {
            $output->writeln($label);
        }

        return Command::SUCCESS;
    }
}
