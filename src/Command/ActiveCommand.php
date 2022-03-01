<?php

namespace Laertejjunior\SubscriptionBundle\Command;

use Laertejjunior\SubscriptionBundle\Model\SubscriptionInterface;

class ActiveCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('shapecode:subscription:active');
        $this->setDescription('Active a subscription a expired/disabled subscription');
    }

    /**
     * {@inheritdoc}
     */
    protected function action(SubscriptionInterface $subscription): void
    {
        $this->getManager()->activate($subscription);

        $this->output->writeln(sprintf('Activated subscription'));
    }
}
