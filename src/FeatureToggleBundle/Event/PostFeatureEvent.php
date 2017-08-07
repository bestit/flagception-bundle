<?php

namespace BestIt\FeatureToggleBundle\Event;

use BestIt\FeatureToggleBundle\Model\Context;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class PostFeatureEvent
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Event
 */
class PostFeatureEvent extends Event implements FeatureEventInterface
{
    /**
     * The feature name
     *
     * @var string
     */
    private $feature;

    /**
     * Is feature is active
     *
     * @var bool
     */
    private $isActive;

    /**
     * The context
     *
     * @var Context
     */
    private $context;

    /**
     * Stash key / name which activated the feature
     * @var string|null
     */
    private $stashName;

    /**
     * PostFeatureEvent constructor.
     *
     * @param string $feature
     * @param bool $isActive
     * @param Context $context
     * @param string $stashName
     */
    public function __construct(string $feature, bool $isActive, Context $context, string $stashName = null)
    {
        $this->feature = $feature;
        $this->isActive = $isActive;
        $this->context = $context;
        $this->stashName = $stashName;
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

    /**
     * Get isActive
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
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
     * Get stash name or null if feature is inactive
     *
     * @return null|string
     */
    public function getStashName()
    {
        return $this->stashName;
    }
}
