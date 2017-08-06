<?php

namespace BestIt\FeatureToggleBundle\Bag;

use ArrayIterator;
use BestIt\FeatureToggleBundle\Model\Stack;
use IteratorAggregate;

/**
 * Class StackBag
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Bag
 */
class StackBag implements IteratorAggregate
{
    /**
     * Stack array
     *
     * @var Stack[]
     */
    private $bag = [];

    /**
     * Add stack
     *
     * @param Stack $stack
     *
     * @return void
     */
    public function add(Stack $stack)
    {
        $this->bag[] = $stack;
    }

    /**
     * Check if identical stack already exists
     *
     * @param Stack $stack
     *
     * @return bool
     */
    public function has(Stack $stack): bool
    {
        foreach ($this->bag as $item) {
            if ($item == $stack) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->bag);
    }

    /**
     * Get all stacks
     *
     * @return Stack[]
     */
    public function all(): array
    {
        return $this->bag;
    }
}
