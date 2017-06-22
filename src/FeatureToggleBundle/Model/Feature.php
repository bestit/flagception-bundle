<?php

namespace BestIt\FeatureToggleBundle\Model;

/**
 * Class Feature
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Model
 */
class Feature
{
    /**
     * Feature name
     *
     * @var string
     */
    private $name;

    /**
     * Active state
     *
     * @var bool
     */
    private $isActive;

    /**
     * Feature constructor.
     *
     * @param string $name
     * @param bool $isActive
     */
    public function __construct(string $name, bool $isActive)
    {
        $this->name = $name;
        $this->isActive = $isActive;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
     * Enable / activate feature
     *
     * @return void
     */
    public function enable()
    {
        $this->isActive = true;
    }

    /**
     * Disable / deactive feature
     *
     * @return void
     */
    public function disable()
    {
        $this->isActive = false;
    }
}
