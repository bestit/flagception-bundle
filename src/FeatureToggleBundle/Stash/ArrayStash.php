<?php

namespace BestIt\FeatureToggleBundle\Stash;

use BestIt\FeatureToggleBundle\Model\Context;

/**
 * Simple stash where you can set active features via constructor
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Stash
 */
class ArrayStash implements StashInterface
{
    /**
     * Active features
     *
     * @var array
     */
    private $activeFeatures;

    /**
     * ArrayStash constructor.
     *
     * @param array $activeFeatures
     */
    public function __construct(array $activeFeatures = [])
    {
        $this->activeFeatures = $activeFeatures;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'array';
    }

    /**
     * @inheritdoc
     */
    public function isActive(string $name, Context $context): bool
    {
        return in_array($name, $this->activeFeatures, true);
    }
}
