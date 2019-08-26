<?php

namespace Shapecode\SubscriptionBundle\Command;

use Shapecode\SubscriptionBundle\Model\SubscriptionInterface;

class DisableCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('shapecode:subscription:disable');
        $this->setDescription('Disable a subscription');
    }

    /**
     * {@inheritdoc}
     */
    protected function action(SubscriptionInterface $subscription): void
    {
        $this->getManager()->disable($subscription);

        $this->output->writeln(sprintf('Disabled subscription'));
    }
}
