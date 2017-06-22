<?php

namespace BestIt\FeatureToggleBundle\Manager;

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
     *
     * @return bool
     */
    public function isActive(string $name): bool;
}
