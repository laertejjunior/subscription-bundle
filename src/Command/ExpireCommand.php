<?php

namespace Laertejjunior\SubscriptionBundle\Command;

use Laertejjunior\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Component\Console\Input\InputArgument;

class ExpireCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('shapecode:subscription:expire');
        $this->setDescription('Expire a subscription');
        $this->addArgument(
            'reason',
            InputArgument::OPTIONAL,
            'Reason of expiration',
            'expired'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function action(SubscriptionInterface $subscription): void
    {
        $reason = $this->input->getArgument('reason');

        $this->getManager()->expire($subscription, $reason);

        $this->output->writeln(sprintf('Expired subscription'));
    }
}
