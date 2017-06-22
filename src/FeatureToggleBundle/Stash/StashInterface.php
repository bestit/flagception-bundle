<?php

namespace BestIt\FeatureToggleBundle\Stash;

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
     * Get array of feature names which are active in this stash
     *
     * @return string[]
     */
    public function getActiveFeatures(): array;
}
