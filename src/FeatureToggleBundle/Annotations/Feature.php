<?php

namespace BestIt\FeatureToggleBundle\Annotations;

/**
 * Class FeatureActive
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Annotations
 * @Annotation
 */
class Feature
{
    /**
     * Name of feature
     *
     * @var string
     */
    public $name;
}
