<?php

namespace BestIt\FeatureToggleBundle\Event;

use BestIt\FeatureToggleBundle\Model\Context;

/**
 * Interface FeatureEventInterface
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Event
 */
interface FeatureEventInterface
{
    /**
     * Get feature
     *
     * @return string
     */
    public function getFeature(): string;

    /**
     * Get context
     *
     * @return Context
     */
    public function getContext(): Context;
}
