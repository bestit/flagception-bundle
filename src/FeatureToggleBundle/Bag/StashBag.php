<?php

namespace BestIt\FeatureToggleBundle\Bag;

use ArrayIterator;
use BestIt\FeatureToggleBundle\Exception\StashNotFoundException;
use BestIt\FeatureToggleBundle\Stash\StashInterface;
use IteratorAggregate;

/**
 * Class StashBag
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Bag
 */
class StashBag implements IteratorAggregate
{
    /**
     * Ordered array of features
     *
     * @var StashInterface[]
     */
    private $bag = [];

    /**
     * Add stash
     *
     * @param StashInterface $stash
     *
     * @return void
     */
    public function add(StashInterface $stash)
    {
        $this->bag[] = $stash;
    }

    /**
     * Check if stash exists
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        foreach ($this->bag as $stash) {
            if ($stash->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get stash or throw exception
     *
     * @param string $name
     *
     * @return StashInterface
     * @throws StashNotFoundException
     */
    public function get(string $name): StashInterface
    {
        foreach ($this->bag as $stash) {
            if ($stash->getName() === $name) {
                return $stash;
            }
        }

        throw new StashNotFoundException(sprintf('Stash with key `%s` not exists', $name));
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->bag);
    }

    /**
     * Get all stashes
     *
     * @return StashInterface[]
     */
    public function all(): array
    {
        return $this->bag;
    }
}
