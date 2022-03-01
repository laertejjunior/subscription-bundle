<?php

namespace Laertejjunior\SubscriptionBundle\Tests\Command;

use Laertejjunior\SubscriptionBundle\Command\AbstractCommand;
use Laertejjunior\SubscriptionBundle\Command\DisableCommand;
use Symfony\Component\Console\Tester\CommandTester;

class DisableCommandTest extends CommandTestCase
{
    public function testExecute()
    {
        $application = new \Symfony\Component\Console\Application();
        $application->add(new DisableCommand());

        /** @var AbstractCommand $command */
        $command = $application->find('shapecode:subscription:disable');
        $command->setContainer($this->getMockContainer());

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'id'      => 1,
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Disabled subscription', $output);
    }
}
