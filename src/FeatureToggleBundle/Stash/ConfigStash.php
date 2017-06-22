<?php

namespace BestIt\FeatureToggleBundle\Stash;

use BestIt\FeatureToggleBundle\Bag\FeatureBag;
use BestIt\FeatureToggleBundle\Model\Feature;

/**
 * Class ConfigStash
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Stash
 */
class ConfigStash implements StashInterface
{
    /**
     * The feature
     *
     * @var FeatureBag
     */
    private $featureBag;

    /**
     * ConfigStash constructor.
     *
     * @param FeatureBag $featureBag
     */
    public function __construct(FeatureBag $featureBag)
    {
        $this->featureBag = $featureBag;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'config';
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveFeatures(): array
    {
        $features = array_filter($this->featureBag->all(), function (Feature $feature) {
            return $feature->isActive();
        });

        return array_values(array_map(function (Feature $feature) {
            return $feature->getName();
        }, $features));
    }
}
