Shapecode - Subscription Bundle
=======================
[![paypal](https://img.shields.io/badge/Donate-Paypal-blue.svg)](http://paypal.me/nloges)

[![PHP Version](https://img.shields.io/packagist/php-v/shapecode/subscription-bundle.svg)](https://packagist.org/packages/shapecode/subscription-bundle)
[![Latest Stable Version](https://img.shields.io/packagist/v/shapecode/subscription-bundle.svg?label=stable)](https://packagist.org/packages/shapecode/subscription-bundle)
[![Latest Unstable Version](https://img.shields.io/packagist/vpre/shapecode/subscription-bundle.svg?label=unstable)](https://packagist.org/packages/shapecode/subscription-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/shapecode/subscription-bundle.svg)](https://packagist.org/packages/shapecode/subscription-bundle)
[![Monthly Downloads](https://img.shields.io/packagist/dm/shapecode/subscription-bundle.svg)](https://packagist.org/packages/shapecode/subscription-bundle)
[![Daily Downloads](https://img.shields.io/packagist/dd/shapecode/subscription-bundle.svg)](https://packagist.org/packages/shapecode/subscription-bundle)
[![License](https://img.shields.io/packagist/l/shapecode/subscription-bundle.svg)](https://packagist.org/packages/shapecode/subscription-bundle)

> SubscriptionBundle helps you to create and manage subscriptions services (also known as plans) for your users in your application.

The SubscriptionBundle fits perfectly in your Symfony application and your models. It don't cares about what persistence
layer are you using (a [http://www.doctrine-orm.org](Doctrine), [http://www.redis.io](Redis)...); it only provides an easy 
and solid base where start to handle this type of products in your Symfony applications.

**Features**
 * Trying to maintain a easy, solid, well-documented and **agnostic** base to start to work without headaches.
 * Many actions allowed on to subscriptions: *active*, *expire*, *disable* and *renew* with his appropriate events.
 * **Extensible**: you can extend and change the out-of-the-box features creating your own strategies that determine how 
 a subscription should be handled to fit to your requirements.

**Compatible**
 * Symfony 3.3+/4+ applications with Doctrine
 
Documentation
-------------

* [Quick Start](#quick-start)
* [Guide](https://github.com/shapecode/subscription-bundle/blob/master/doc/Guide.md)
* Strategies
    * Product strategies:
        * [What is a product strategy](https://github.com/shapecode/subscription-bundle/blob/master/doc/WhatIsProductStrategy.md)
        * [How to create a product strategy](https://github.com/shapecode/subscription-bundle/blob/master/doc/HowToCreateAProductStrategy.md])
        * [Out-of-the-box strategies](https://github.com/shapecode/subscription-bundle/blob/master/doc/strategies/product):
            * [Default product strategy](https://github.com/shapecode/subscription-bundle/blob/master/doc/strategies/product/DefaultStrategy.md)
            
    * Subscription strategies:
        * [What is a subscription strategy](https://github.com/shapecode/subscription-bundle/blob/master/doc/WhatIsAProductStrategy.md)
        * [How to create a subscription strategy](https://github.com/shapecode/subscription-bundle/blob/master/doc/HowToCreateASubscriptionStrategy.md)
        * [Out-of-the-box strategies](https://github.com/shapecode/subscription-bundle/blob/master/doc/strategies/subscription):
            * [End Last Strategy](https://github.com/shapecode/subscription-bundle/blob/master/doc/strategies/subscription/EndLastStrategy.md)

* CookBooks/Examples:
    * [Symfony 4 example sandbox](https://github.com/shapecode/sf4-subscription-example) with doctrine

Quick start
-----------

### 1. Download the bundle:

```bash
$ composer require shapecode/subscription-bundle
```

### 2. Enable the bundle in Symfony Application (only Symfony 3):

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Shapecode\SubscriptionBundle\ShapecodeSubscriptionBundle(),
        );
    }

    // ...
}
```

### 3. Configure the bundle:

```yaml
shapecode_subscription:
    # Where is the subscription model located in your application
    # Remember that your model must implement the interface
    subscription_class: AppBundle\Entity\Subscription # Interface: Shapecode\SubscriptionBundle\Model\SubscriptionInterface

    # Repository services name
    # Remember that repositories must be implement the interfaces
    subscription_repository: app.repository.subscription # Interface: Shapecode\SubscriptionBundle\Repository\SubscriptionRepositoryInterface
    product_repository: app.repository.product           # Interface: Shapecode\SubscriptionBundle\Repository\ProductRepositoryInterface
```
Read the [complete configuration reference](https://github.com/shapecode/subscription-bundle/blob/master/doc/ReferenceConfig.md) for more configuration options or tweaks.

License
-------

This software is published under the [MIT License](https://github.com/shapecode/subscription-bundle/master/LICENSE.md)

Contributing
------------

I will be very happy if you want to contribute fixing some issue, providing new strategies or whatever you want. Thanks!

Info
------------

Porject forked from [terox/SubscriptionBundle](https://github.com/terox/SubscriptionBundle)
