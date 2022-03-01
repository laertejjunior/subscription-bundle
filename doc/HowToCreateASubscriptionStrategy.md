How to create a subscription strategy
=====================================

## Create your own subscription strategy:

### Create the strategy class:

Place your strategy file where you consider more suitable in your file structure:

```php
<?php

namespace App\Strategy\Subscription;

use Laertejjunior\SubscriptionBundle\Strategy\AbstractSubscriptionStrategy;
use Laertejjunior\SubscriptionBundle\Model\ProductInterface;

class MyProductStrategy extends AbstractSubscriptionStrategy
{
    /**
     * @param ProductInterface        $product       Product that will be used to create the new subscription
     * @param SubscriptionInterface[] $subscriptions Active subscriptions
     *
     * @return SubscriptionInterface
     *
     * @throws PermanentSubscriptionException
     */
    public function createSubscription(ProductInterface $product, array $subscriptions = [])
    {
        //
        // My business logic...
        //                  ... remember throw exceptions!
        //                  
        //                  ... and do some magic (why not?)
        //
        
        // This create a subscription model
        $subscription = $this->createSubscriptionInstance();
        //
        // ... some subscription model tweaks...
        
        return $subscription;
    }
}
````
#### 📌 Tips:
* Review the **[end_last](https://github.com/shapecode/subscription-bundle/blob/master/doc/strategies/subscription/EndLastStrategy.md)** strategy if your need help.

#### ❗ Remember:
* Use in your subscription model the next interface: ***Laertejjunior\SubscriptionBundle\Model\SubscriptionInterface***.
* Use in your repositories the next interface: ***Laertejjunior\SubscriptionBundle\Repository\SubscriptionRepositoryInterface***.

Both classes must be [configurated](https://github.com/shapecode/subscription-bundle/blob/master/doc/ReferenceConfig.md).

### Declare the strategy into container:

This register the subscription strategy into the container and register int into internal registry.

```yaml
    app.strategy.my_product:
        class: App\Strategy\Subscription\MyProductStrategy
        arguments:
            - "%shapecode_subscription.config.subscription.class%"
            # Inject any other product strategy if you need:
            - "@shapecode.subscription.strategy.product.default"
        tags:
            - { name: subscription.strategy, type: subscription, strategy: my_subscription_strategy }
```

Well you are ready to start working with your subscription strategy 🚀!
