<?php

namespace BestIt\FeatureToggleBundle;

/**
 * Class ToggleEvents
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle
 */
final class ToggleEvents
{
    /**
     * Event before feature is searched in stash
     *
     * @Event("BestIt\FeatureToggleBundle\Event\PreFeatureEvent")
     */
    const FEATURE_IS_ACTIVE_PRE = 'best_it_feature_toggle.event.feature_is_active.pre';

    /**
     * Event after feature is searched and state is known
     *
     * @Event("BestIt\FeatureToggleBundle\Event\PostFeatureEvent")
     */
    const FEATURE_IS_ACTIVE_POST = 'best_it_feature_toggle.event.feature_is_active.post';
}
