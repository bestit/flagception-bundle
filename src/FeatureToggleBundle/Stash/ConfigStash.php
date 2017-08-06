<?php

namespace BestIt\FeatureToggleBundle\Stash;

use BestIt\FeatureToggleBundle\Model\Context;

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
     * @inheritdoc
     */
    public function isActive(string $name, Context $context): bool
    {
        return $this->features[$name] ?? false;
    }

    /**
     * Add active feature
     *
     * @param string $feature
     * @param bool $isActive
     *
     * @return void
     */
    public function add(string $feature, bool $isActive)
    {
        $this->features[$feature] = $isActive;
    }
}
