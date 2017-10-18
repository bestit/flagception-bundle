<?php

namespace BestIt\FeatureToggleBundle\Bag;

use ArrayIterator;
use BestIt\FeatureToggleBundle\Decorator\ContextDecoratorInterface;
use BestIt\FeatureToggleBundle\Exception\ContextDecoratorNotFoundException;
use IteratorAggregate;

/**
 * Storage for ContextDecorators
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Bag
 */
class ContextDecoratorBag implements IteratorAggregate
{
    /**
     * Ordered array of decorators
     *
     * @var ContextDecoratorInterface[]
     */
    private $bag = [];

    /**
     * Add decorator
     *
     * @param ContextDecoratorInterface $decorator
     *
     * @return void
     */
    public function add(ContextDecoratorInterface $decorator)
    {
        $this->bag[] = $decorator;
    }

    /**
     * Check if decorator exists
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        foreach ($this->bag as $decorator) {
            if ($decorator->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get decorator or throw exception
     *
     * @param string $name
     *
     * @return ContextDecoratorInterface
     * @throws ContextDecoratorNotFoundException
     */
    public function get(string $name): ContextDecoratorInterface
    {
        foreach ($this->bag as $decorator) {
            if ($decorator->getName() === $name) {
                return $decorator;
            }
        }

        throw new ContextDecoratorNotFoundException(sprintf('Decorator with key `%s` not exists', $name));
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->bag);
    }

    /**
     * Get all decorators
     *
     * @return ContextDecoratorInterface[]
     */
    public function all(): array
    {
        return $this->bag;
    }
}
