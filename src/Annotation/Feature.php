<?php

namespace Laertejjunior\SubscriptionBundle\Annotation;

/**
 * Class Feature
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 *
 * @package Laertejjunior\SubscriptionBundle\Annotation
 * @author  Nikita Loges
 */
class Feature
{

    /** @var string[] */
    public $features;

    /**
     * @param array $data An array of key/value parameters
     *
     * @throws \BadMethodCallException
     */
    public function __construct(array $data)
    {
        if (isset($data['value'])) {
            $data['features'] = $data['value'];
            unset($data['value']);
        }

        if (!isset($data['features'])) {
            throw new \BadMethodCallException('features property is missing');
        }

        if (!is_array($data['features'])) {
            $data['features'] = [$data['features']];
        }

        foreach ($data as $key => $value) {
            $method = 'set'.str_replace('_', '', $key);
            if (!method_exists($this, $method)) {
                throw new \BadMethodCallException(sprintf('Unknown property "%s" on annotation "%s".', $key, \get_class($this)));
            }
            $this->$method($value);
        }
    }

    /**
     * @return string[]
     */
    public function getFeatures(): array
    {
        return $this->features;
    }

    /**
     * @param string[] $features
     */
    public function setFeatures(array $features): void
    {
        $this->features = $features;
    }
}
