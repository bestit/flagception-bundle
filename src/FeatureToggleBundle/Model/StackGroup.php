<?php

namespace BestIt\FeatureToggleBundle\Model;

/**
 * Class StackGroup
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Model
 */
class StackGroup
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
     * Used stashes
     *
     * @var string[]
     */
    private $stashes = [];

    /**
     * StackGroup constructor.
     *
     * @param string $featureName
     */
    public function __construct(string $featureName)
    {
        $this->featureName = $featureName;
    }

    /**
     * Add stash only if not already known
     *
     * @param string $stashName
     *
     * @return void
     */
    public function addStash(string $stashName)
    {
        if (!in_array($stashName, $this->stashes, true)) {
            $this->stashes[] = $stashName;
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
    public function getFeatureName(): string
    {
        return $this->featureName;
    }

    /**
     * Get amount if requests
     *
     * @return int
     */
    public function getAmountRequests(): int
    {
        return $this->getAmountActive() + $this->getAmountInactive();
    }

    /**
     * Get amountActive
     *
     * @return int
     */
    public function getAmountActive(): int
    {
        return $this->amountActive;
    }

    /**
     * Get amountInactive
     *
     * @return int
     */
    public function getAmountInactive(): int
    {
        return $this->amountInactive;
    }

    /**
     * Get stashes
     *
     * @return string[]
     */
    public function getStashes(): array
    {
        return $this->stashes;
    }
}
