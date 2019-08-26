<?php

namespace Shapecode\SubscriptionBundle\Tests\Command;

use Shapecode\SubscriptionBundle\Command\AbstractCommand;
use Shapecode\SubscriptionBundle\Command\ActiveCommand;
use Symfony\Component\Console\Tester\CommandTester;

class ActiveCommandTest extends CommandTestCase
{
    public function testExecute()
    {
        $application = new \Symfony\Component\Console\Application();
        $application->add(new ActiveCommand());

        /** @var AbstractCommand $command */
        $command = $application->find('shapecode:subscription:active');
        $command->setContainer($this->getMockContainer());

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'id'      => 1,
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Activated subscription', $output);
    }
}
