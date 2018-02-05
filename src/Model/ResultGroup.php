<?php

namespace Flagception\Bundle\FlagceptionBundle\Model;

/**
 * Class ResultGroup
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\Model
 */
class ResultGroup
{
    /**
     * The feature name
     *
     * @var string
     */
    private $featureName;

    /**
     * Amount of active responses
     *
     * @var int
     */
    private $amountActive = 0;

    /**
     * Amount of inactive respones
     *
     * @var int
     */
    private $amountInactive = 0;

    /**
     * Used activators
     *
     * @var string[]
     */
    private $activators = [];

    /**
     * ResultGroup constructor.
     *
     * @param string $featureName
     */
    public function __construct($featureName)
    {
        $this->featureName = $featureName;
    }

    /**
     * Add activator only if not already known
     *
     * @param string $activator
     *
     * @return void
     */
    public function addActivator($activator)
    {
        if (!in_array($activator, $this->activators, true)) {
            $this->activators[] = $activator;
        }
    }

    /**
     * Increase active response
     *
     * @return void
     */
    public function increaseActive()
    {
        $this->amountActive++;
    }

    /**
     * Increase inactive response
     *
     * @return void
     */
    public function increaseInactive()
    {
        $this->amountInactive++;
    }

    /**
     * Get featureName
     *
     * @return string
     */
    public function getFeatureName()
    {
        return $this->featureName;
    }

    /**
     * Get amount if requests
     *
     * @return int
     */
    public function getAmountRequests()
    {
        return $this->getAmountActive() + $this->getAmountInactive();
    }

    /**
     * Get amountActive
     *
     * @return int
     */
    public function getAmountActive()
    {
        return $this->amountActive;
    }

    /**
     * Get amountInactive
     *
     * @return int
     */
    public function getAmountInactive()
    {
        return $this->amountInactive;
    }

    /**
     * Get activators
     *
     * @return string[]
     */
    public function getActivators()
    {
        return $this->activators;
    }
}
