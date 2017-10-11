<?php

namespace BestIt\FeatureToggleBundle\Stash;

use BestIt\FeatureToggleBundle\Model\Context;

/**
 * Interface StashInterface
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Stash
 */
interface StashInterface
{
    /**
     * Get stash name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Check if the given feature name is active
     * Optional the context object can contain further options to check
     *
     * @param string $name
     * @param Context $context
     *
     * @return bool
     */
    public function isActive(string $name, Context $context): bool;
}
