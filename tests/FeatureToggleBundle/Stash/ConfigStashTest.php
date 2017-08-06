<?php

namespace Tests\BestIt\FeatureToggleBundle\Stash;

use BestIt\FeatureToggleBundle\Model\Context;
use BestIt\FeatureToggleBundle\Stash\ConfigStash;
use BestIt\FeatureToggleBundle\Stash\StashInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigStashTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\Stash
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
     * Test is active
     *
     * @return void
     */
    public function testIsActive()
    {
        $stash = new ConfigStash();
        $stash->add('feature_1', true);
        $stash->add('feature_2', false);
        $stash->add('feature_3', false);
        $stash->add('feature_4', true);
        $stash->add('feature_5', false);

        static::assertFalse($stash->isActive('feature_3', new Context()));
        static::assertTrue($stash->isActive('feature_4', new Context()));
    }

    /**
     * Test unknown feature return false
     *
     * @return void
     */
    public function testUnknownFeature()
    {
        $stash = new ConfigStash();
        $stash->add('feature_1', true);
        $stash->add('feature_2', false);

        static::assertFalse($stash->isActive('feature_3', new Context()));
    }
}
