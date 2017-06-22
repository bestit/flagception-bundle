<?php

namespace BestIt\FeatureToggleBundle\Manager;

use BestIt\FeatureToggleBundle\Bag\StashBag;
use BestIt\FeatureToggleBundle\Stash\StashInterface;

/**
 * Class FeatureManager
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Manager
 */
class FeatureManager implements FeatureManagerInterface
{
    /**
     * The stash bag
     *
     * @var StashBag
     */
    private $stashBag;

    /**
     * FeatureManager constructor.
     *
     * @param StashBag $stashBag
     */
    public function __construct(StashBag $stashBag)
    {
        $this->stashBag = $stashBag;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive(string $name): bool
    {
        /** @var StashInterface $stash */
        foreach ($this->stashBag as $stash) {
            foreach ($stash->getActiveFeatures() as $feature) {
                if ($feature === $name) {
                    return true;
                }
            }
        }

        return false;
    }
}
