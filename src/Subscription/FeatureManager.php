<?php

namespace Shapecode\SubscriptionBundle\Subscription;

use Doctrine\Common\Persistence\ManagerRegistry;
use Shapecode\SubscriptionBundle\Model\ProductInterface;
use Shapecode\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use function Doctrine\ORM\QueryBuilder;

/**
 * Class FeatureManager
 *
 * @package Shapecode\SubscriptionBundle\Subscription
 * @author  Nikita Loges
 */
class FeatureManager
{

    /** @var ManagerRegistry */
    protected $registry;

    /** @var SubscriptionRegistry */
    protected $subscriptionRegistry;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var SubscriptionConfig */
    protected $config;

    /**
     * @param ManagerRegistry          $registry
     * @param SubscriptionRegistry     $subscriptionRegistry
     * @param EventDispatcherInterface $eventDispatcher
     * @param SubscriptionConfig       $config
     */
    public function __construct(
        ManagerRegistry $registry,
        SubscriptionRegistry $subscriptionRegistry,
        EventDispatcherInterface $eventDispatcher,
        SubscriptionConfig $config
    ) {
        $this->registry = $registry;
        $this->subscriptionRegistry = $subscriptionRegistry;
        $this->eventDispatcher = $eventDispatcher;
        $this->config = $config;
    }

    public function productHasFeature(ProductInterface $product, string $key): bool
    {
    }

    public function subscriptionHasFeature(SubscriptionInterface $subscription, string $key): bool
    {
    }

    public function userHasFeature(UserInterface $user, string $key): bool
    {
        $repo = $this->config->getFeatureRepository();
        $qb = $repo->createQueryBuilder('f');

        $qb->select('count(f.id) as count');

        $qb->join('p.subscriptions', 's');
        $qb->join('s.product', 'p');

        $qb->andWhere($qb->expr()->eq('s.user', ':user'));
        $qb->andWhere($qb->expr()->eq('s.active', ':active'));
        $qb->andWhere($qb->expr()->lte('s.startDate', ':startDate'));
        $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->gte('s.endDate', ':endDate'),
                $qb->expr()->isNull('s.endDate')
            )
        );

        $qb->setParameter('user', $user);
        $qb->setParameter('active', true);
        $qb->setParameter('startDate', new \DateTime());
        $qb->setParameter('endDate', new \DateTime());

        $result = $qb->getQuery()->getSingleScalarResult();

        return $result > 0;
    }
}
