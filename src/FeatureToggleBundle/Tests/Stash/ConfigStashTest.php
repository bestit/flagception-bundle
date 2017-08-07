<?php

namespace BestIt\FeatureToggleBundle\Tests\Stash;

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
        $stash = new ConfigStash();
        static::assertInstanceOf(StashInterface::class, $stash);
    }

    /**
     * Test name
     *
     * @return void
     */
    public function testName()
    {
        $stash = new ConfigStash();
        static::assertEquals('config', $stash->getName());
    }

    /**
     * Test get active features
     *
     * @return void
     */
    public function testGetActiveFeatures()
    {
        $stash = new ConfigStash();
        $stash->add('feature_1');
        $stash->add('feature_4');

        static::assertEquals(['feature_1', 'feature_4'], $stash->getActiveFeatures());
    }
}
