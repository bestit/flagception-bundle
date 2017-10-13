<?php

namespace BestIt\FeatureToggleBundle\Decorator;

use BestIt\FeatureToggleBundle\Model\Context;

/**
 * Interface ContextDecoratorInterface
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Decorator
 */
interface ContextDecoratorInterface
{
    /**
     * Get decorator name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Decorate the context object with global settings
     *
     * @param Context $context
     *
     * @return Context
     */
    public function decorate(Context $context): Context;
}
