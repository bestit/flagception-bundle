<?php

namespace BestIt\FeatureToggleBundle\Manager;

use BestIt\FeatureToggleBundle\Model\Context;

/**
 * Interface FeatureManagerInterface
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Manager
 */
interface FeatureManagerInterface
{
    /**
     * Check if feature is active
     *
     * @param string $name
     * @param Context|null $context
     *
     * @return bool
     */
    public function isActive(string $name, Context $context = null): bool;
}
