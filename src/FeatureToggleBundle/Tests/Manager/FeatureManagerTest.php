<?php

namespace BestIt\FeatureToggleBundle\Tests\Manager;

use BestIt\FeatureToggleBundle\Bag\FeatureBag;
use BestIt\FeatureToggleBundle\Bag\StashBag;
use BestIt\FeatureToggleBundle\Manager\FeatureManager;
use BestIt\FeatureToggleBundle\Manager\FeatureManagerInterface;
use BestIt\FeatureToggleBundle\Stash\StashInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class FeatureManagerTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Tests\Manager
 */
class FeatureManagerTest extends TestCase
{
    /**
     * Test implement interface
     *
     * @return void
     */
    public function testImplementInterface()
    {
        $manager = new FeatureManager(
            $this->createMock(StashBag::class)
        );

        static::assertInstanceOf(FeatureManagerInterface::class, $manager);
    }

    /**
     * Test feature not active
     *
     * @return void
     */
    public function testFeatureNotActive()
    {
        $manager = new FeatureManager(
            $stashBag = new StashBag()
        );

        $stashBag->add($cookieStash = $this->createMock(StashInterface::class));
        $cookieStash
            ->method('getActiveFeatures')
            ->willReturn(['feature_bazz', 'feature_bar']);

        $stashBag->add($configStash = $this->createMock(StashInterface::class));
        $configStash
            ->method('getActiveFeatures')
            ->willReturn([]);

        $stashBag->add($customObjectStash = $this->createMock(StashInterface::class));
        $customObjectStash
            ->method('getActiveFeatures')
            ->willReturn(['feature_foobar']);

        static::assertEquals(false, $manager->isActive('feature_foo'));
    }

    /**
     * Test feature is active
     *
     * @return void
     */
    public function testFeatureActive()
    {
        $manager = new FeatureManager(
            $stashBag = new StashBag()
        );

        $stashBag->add($cookieStash = $this->createMock(StashInterface::class));
        $cookieStash
            ->method('getActiveFeatures')
            ->willReturn(['feature_bazz', 'feature_bar']);

        $stashBag->add($configStash = $this->createMock(StashInterface::class));
        $configStash
            ->method('getActiveFeatures')
            ->willReturn([]);

        $stashBag->add($customObjectStash = $this->createMock(StashInterface::class));
        $customObjectStash
            ->method('getActiveFeatures')
            ->willReturn(['feature_foo']);

        static::assertEquals(true, $manager->isActive('feature_foo'));
    }
}
