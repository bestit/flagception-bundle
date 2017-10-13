<?php

namespace BestIt\FeatureToggleBundle\Decorator;

use BestIt\FeatureToggleBundle\Model\Context;

/**
 * Simple decorator for adding context values via constructor
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Decorator
 */
class ArrayContextDecorator implements ContextDecoratorInterface
{
    /**
     * Named values (key => value pairs)
     *
     * @var array
     */
    private $values;

    /**
     * ArrayContextDecorator constructor.
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'array';
    }

    /**
     * {@inheritdoc}
     */
    public function decorate(Context $context): Context
    {
        foreach ($this->values as $name => $value) {
            $context->add($name, $value);
        }

        return $context;
    }

}
