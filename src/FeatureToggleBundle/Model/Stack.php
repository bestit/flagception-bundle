<?php

namespace BestIt\FeatureToggleBundle\Model;

/**
 * Class Stack
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Model
 */
class Stack
{
    /**
     * The feature name
     *
     * @var string
     */
    private $featureName;

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
     * The stash name
     *
     * @var string|null
     */
    private $stashName;

    /**
     * Stack constructor.
     *
     * @param string $featureName
     * @param bool $isActive
     * @param Context $context
     * @param null|string $stashName
     */
    public function __construct(string $featureName, bool $isActive, Context $context, string $stashName = null)
    {
        $this->featureName = $featureName;
        $this->isActive = $isActive;
        $this->context = $context;
        $this->stashName = $stashName;
    }

    /**
     * Get featureName
     *
     * @return string
     */
    public function getFeatureName(): string
    {
        return $this->featureName;
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
     * Get stashName
     *
     * @return null|string
     */
    public function getStashName()
    {
        return $this->stashName;
    }
}
