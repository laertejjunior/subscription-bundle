<?php

namespace Laertejjunior\SubscriptionBundle\Tests\Command;

use Laertejjunior\SubscriptionBundle\Command\AbstractCommand;
use Laertejjunior\SubscriptionBundle\Command\ExpireCommand;
use Symfony\Component\Console\Tester\CommandTester;

class ExpireCommandTest extends CommandTestCase
{
    public function testExecute()
    {
        $application = new \Symfony\Component\Console\Application();
        $application->add(new ExpireCommand());

        /** @var AbstractCommand $command */
        $command = $application->find('shapecode:subscription:expire');
        $command->setContainer($this->getMockContainer());

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'id'      => 1,
            'reason'  => 'testing reason',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Expired subscription', $output);
    }
}
