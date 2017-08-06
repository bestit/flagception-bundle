<?php

namespace BestIt\FeatureToggleBundle\Event;

use BestIt\FeatureToggleBundle\Model\Context;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class PreFeatureEvent
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Event
 */
class PreFeatureEvent extends Event
{
    /**
     * The feature name
     *
     * @var string
     */
    private $feature;

    /**
     * The context
     *
     * @var Context
     */
    private $context;

    /**
     * PreFeatureEvent constructor.
     *
     * @param string $feature
     * @param Context $context
     */
    public function __construct(string $feature, Context $context)
    {
        $this->feature = $feature;
        $this->context = $context;
    }

    /**
     * Get context
     *
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }

    /**
     * Get feature
     *
     * @return string
     */
    public function getFeature(): string
    {
        return $this->feature;
    }
}
