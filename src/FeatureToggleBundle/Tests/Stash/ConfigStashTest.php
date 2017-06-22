<?php

namespace BestIt\FeatureToggleBundle\Tests\Stash;

use BestIt\FeatureToggleBundle\Bag\FeatureBag;
use BestIt\FeatureToggleBundle\Stash\ConfigStash;
use BestIt\FeatureToggleBundle\Stash\StashInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigStashTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Tests\Stash
 */
class ConfigStashTest extends TestCase
{
    /**
     * Test implement interface
     *
     * @return void
     */
    public function testImplementInterface()
    {
        $stash = new ConfigStash($this->createMock(FeatureBag::class));
        static::assertInstanceOf(StashInterface::class, $stash);
    }

    /**
     * Test name
     *
     * @return void
     */
    public function testName()
    {
        $stash = new ConfigStash($this->createMock(FeatureBag::class));
        static::assertEquals('config', $stash->getName());
    }

    /**
     * Test get active features
     *
     * @return void
     */
    public function testGetActiveFeatures()
    {
        $bag = new FeatureBag();
        $bag->add('feature_1', true);
        $bag->add('feature_2', false);
        $bag->add('feature_3', false);
        $bag->add('feature_4', true);
        $bag->add('feature_5', false);

        $stash = new ConfigStash($bag);
        static::assertEquals(['feature_1', 'feature_4'], $stash->getActiveFeatures());
    }
}
