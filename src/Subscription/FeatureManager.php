<?php

namespace Shapecode\SubscriptionBundle\Subscription;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Shapecode\SubscriptionBundle\Model\ProductInterface;
use Shapecode\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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
        $repo = $this->config->getSubscriptionRepository();

        /** @var QueryBuilder $qb */
        $qb = $repo->createQueryBuilder('s');

        $qb->select('count(s.id) as count');

        $qb->join('s.product', 'p');
        $qb->join('p.features', 'f');

        $qb->andWhere($qb->expr()->eq('s.user', ':user'));
        $qb->andWhere($qb->expr()->eq('s.active', ':active'));
        $qb->andWhere($qb->expr()->lte('s.startDate', ':startDate'));
        $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->gte('s.endDate', ':endDate'),
                $qb->expr()->isNull('s.endDate')
            )
        );
        $qb->andWhere($qb->expr()->eq('f.key', ':feature'));

        $qb->setParameter('feature', $key);
        $qb->setParameter('user', $user);
        $qb->setParameter('active', true);
        $qb->setParameter('startDate', new \DateTime());
        $qb->setParameter('endDate', new \DateTime());

        $result = $qb->getQuery()->getSingleScalarResult();

        return $result > 0;
    }

    public function userHasActiveSubscription(UserInterface $user): bool
    {
        $repo = $this->config->getSubscriptionRepository();

        /** @var QueryBuilder $qb */
        $qb = $repo->createQueryBuilder('s');

        $qb->select('count(s.id) as count');

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

    public function userGetActiveSubscription(UserInterface $user): ?SubscriptionInterface
    {
        $repo = $this->config->getSubscriptionRepository();

        /** @var QueryBuilder $qb */
        $qb = $repo->createQueryBuilder('s');

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

        $qb->setMaxResults(1);
        $qb->orderBy('s.id', 'ASC');

        return $qb->getQuery()->getOneOrNullResult();
    }
}
