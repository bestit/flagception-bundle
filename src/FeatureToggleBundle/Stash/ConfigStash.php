<?php

namespace BestIt\FeatureToggleBundle\Stash;

/**
 * Class ConfigStash
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Stash
 */
class ConfigStash implements StashInterface
{
    /**
     * The features
     *
     * @var array
     */
    private $features = [];

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
        return $this->features;
    }

    /**
     * Add active feature
     *
     * @param string $feature
     *
     * @return void
     */
    public function add(string $feature)
    {
        $this->features[] = $feature;
    }
}
