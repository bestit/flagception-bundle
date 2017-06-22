<?php

namespace BestIt\FeatureToggleBundle\Bag;

use ArrayIterator;
use BestIt\FeatureToggleBundle\Exception\FeatureNotFoundException;
use BestIt\FeatureToggleBundle\Model\Feature;
use IteratorAggregate;

/**
 * Class FeatureBag
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Bag
 */
class FeatureBag implements IteratorAggregate
{
    /**
     * Named array of features
     *
     * @var Feature[]
     */
    private $bag = [];

    /**
     * Add feature
     *
     * @param string $name
     * @param bool $isActive
     *
     * @return void
     */
    public function add(string $name, bool $isActive)
    {
        $this->bag[$name] = new Feature($name, $isActive);
    }

    /**
     * Check if feature exists
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->bag);
    }

    /**
     * Get feature or throw exception
     *
     * @param string $name
     *
     * @return Feature
     * @throws FeatureNotFoundException
     */
    public function get(string $name): Feature
    {
        if (!$this->has($name)) {
            throw new FeatureNotFoundException(sprintf('Feature with key `%s` not exists', $name));
        }

        return $this->bag[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->bag);
    }

    /**
     * Get all features
     *
     * @return Feature[]
     */
    public function all(): array
    {
        return $this->bag;
    }
}
