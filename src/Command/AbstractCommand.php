<?php

namespace Laertejjunior\SubscriptionBundle\Command;

use Laertejjunior\SubscriptionBundle\Model\SubscriptionInterface;
use Laertejjunior\SubscriptionBundle\Subscription\SubscriptionConfig;
use Laertejjunior\SubscriptionBundle\Subscription\SubscriptionManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractCommand
 *
 * @package Laertejjunior\SubscriptionBundle\Command
 * @author  Nikita Loges
 */
abstract class AbstractCommand extends Command
{
    /** @var SubscriptionManager */
    protected $manager;

    /** @var SubscriptionConfig */
    protected $config;

    /** @var InputInterface */
    protected $input;

    /** @var OutputInterface */
    protected $output;

    /**
     * @param SubscriptionConfig  $config
     * @param SubscriptionManager $manager
     */
    public function __construct(
        SubscriptionConfig $config,
        SubscriptionManager $manager
    ) {
        $this->manager = $manager;
        $this->config = $config;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'Subscription ID');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $subscriptionId = $input->getArgument('id');
        $subscription = $this->config->getSubscriptionRepository()->findById($subscriptionId);

        if (null === $subscription) {
            return $output->writeln(sprintf('<error>The subscription with ID "%s" was not found.</error>', $subscriptionId));
        }

        // Execute the action
        $this->action($subscription);

        $output->writeln('<green>Finished.</green>');
    }

    /**
     * Action to execute when
     *
     * @param SubscriptionInterface $subscription
     */
    abstract protected function action(SubscriptionInterface $subscription): void;

    /**
     * @return SubscriptionConfig
     */
    protected function getConfig(): SubscriptionConfig
    {
        return $this->config;
    }

    /**
     * @return SubscriptionManager
     */
    protected function getManager(): SubscriptionManager
    {
        return $this->manager;
    }
}
