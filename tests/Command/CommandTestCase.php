<?php

namespace Laertejjunior\SubscriptionBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Laertejjunior\SubscriptionBundle\Repository\SubscriptionRepositoryInterface;
use Laertejjunior\SubscriptionBundle\Subscription\SubscriptionManager;
use Laertejjunior\SubscriptionBundle\Tests\Mock\SubscriptionMock;

class CommandTestCase extends TestCase
{
    protected function getMockContainer()
    {
        // Manager
        $manager = \Mockery::mock(SubscriptionManager::class);

        $manager
            ->shouldReceive('activate')
            ->once();

        $manager
            ->shouldReceive('disable')
            ->once();

        $manager
            ->shouldReceive('expire')
            ->once();

        // Repository
        $repository = \Mockery::mock(SubscriptionRepositoryInterface::class)
            ->shouldReceive('findById')
            ->withAnyArgs()
            ->andReturn(new SubscriptionMock())
            ->getMock();

        // Container
        $container = \Mockery::mock(Container::class);
        $container
            ->shouldReceive('get')
            ->once()
            ->with('shapecode.subscription.repository.subscription')
            ->andReturn($repository);

        $container
            ->shouldReceive('get')
            ->with('shapecode.subscription.manager')
            ->andReturn($manager);

        return $container;
    }

}
