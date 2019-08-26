<?php

namespace Shapecode\SubscriptionBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Shapecode\SubscriptionBundle\Repository\SubscriptionRepositoryInterface;
use Shapecode\SubscriptionBundle\Subscription\SubscriptionManager;
use Shapecode\SubscriptionBundle\Tests\Mock\SubscriptionMock;

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
