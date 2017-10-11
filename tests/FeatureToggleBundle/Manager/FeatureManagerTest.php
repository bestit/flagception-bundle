<?php

namespace Tests\BestIt\FeatureToggleBundle\Manager;

use BestIt\FeatureToggleBundle\Bag\StashBag;
use BestIt\FeatureToggleBundle\Event\PostFeatureEvent;
use BestIt\FeatureToggleBundle\Event\PreFeatureEvent;
use BestIt\FeatureToggleBundle\Manager\FeatureManager;
use BestIt\FeatureToggleBundle\Manager\FeatureManagerInterface;
use BestIt\FeatureToggleBundle\Model\Context;
use BestIt\FeatureToggleBundle\Stash\StashInterface;
use BestIt\FeatureToggleBundle\ToggleEvents;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class FeatureManagerTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\Manager
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
            $this->createMock(StashBag::class),
            $this->createMock(EventDispatcherInterface::class)
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
            $stashBag = new StashBag(),
            $this->createMock(EventDispatcherInterface::class)
        );

        $stashBag->add($cookieStash = $this->createMock(StashInterface::class));
        $cookieStash
            ->method('isActive')
            ->with('feature_foo', new Context())
            ->willReturn(false);

        $stashBag->add($configStash = $this->createMock(StashInterface::class));
        $configStash
            ->method('isActive')
            ->with('feature_foo', new Context())
            ->willReturn(false);

        $stashBag->add($customObjectStash = $this->createMock(StashInterface::class));
        $customObjectStash
            ->method('isActive')
            ->with('feature_foo', new Context())
            ->willReturn(false);

        static::assertEquals(false, $manager->isActive('feature_foo'));
    }

    /**
     * Test feature is active without context
     *
     * @return void
     */
    public function testFeatureActiveWithoutContext()
    {
        $manager = new FeatureManager(
            $stashBag = new StashBag(),
            $eventDispatcher = $this->createMock(EventDispatcherInterface::class)
        );

        $eventDispatcher
            ->expects(static::exactly(2))
            ->method('dispatch')
            ->withConsecutive(
                [
                    ToggleEvents::FEATURE_IS_ACTIVE_PRE,
                    new PreFeatureEvent('feature_foo', new Context())
                ],
                [
                    ToggleEvents::FEATURE_IS_ACTIVE_POST,
                    new PostFeatureEvent(
                        'feature_foo',
                        true,
                        new Context(),
                        'config'
                    )
                ]
            );

        $stashBag->add($cookieStash = $this->createMock(StashInterface::class));
        $cookieStash
            ->method('isActive')
            ->with('feature_foo', new Context())
            ->willReturn(false);

        $stashBag->add($configStash = $this->createMock(StashInterface::class));
        $configStash
            ->method('getName')
            ->willReturn('config');
        $configStash
            ->method('isActive')
            ->with('feature_foo', new Context())
            ->willReturn(true);

        $stashBag->add($customObjectStash = $this->createMock(StashInterface::class));
        $customObjectStash
            ->method('isActive')
            ->with('feature_foo', new Context())
            ->willReturn(false);

        static::assertEquals(true, $manager->isActive('feature_foo'));
    }

    /**
     * Test feature is active with context
     *
     * @return void
     */
    public function testFeatureActiveWithContext()
    {
        $manager = new FeatureManager(
            $stashBag = new StashBag(),
            $eventDispatcher = $this->createMock(EventDispatcherInterface::class)
        );

        $context = new Context();
        $context->add('user_id', 23);
        $context->add('role', 'ROLE_ADMIN');

        $eventDispatcher
            ->expects(static::exactly(2))
            ->method('dispatch')
            ->withConsecutive(
                [
                    ToggleEvents::FEATURE_IS_ACTIVE_PRE,
                    new PreFeatureEvent('feature_foo', $context)
                ],
                [
                    ToggleEvents::FEATURE_IS_ACTIVE_POST,
                    new PostFeatureEvent(
                        'feature_foo',
                        true,
                        $context,
                        'config'
                    )
                ]
            );

        $stashBag->add($cookieStash = $this->createMock(StashInterface::class));
        $cookieStash
            ->method('isActive')
            ->with('feature_foo', $context)
            ->willReturn(false);

        $stashBag->add($configStash = $this->createMock(StashInterface::class));
        $configStash
            ->method('getName')
            ->willReturn('config');
        $configStash
            ->method('isActive')
            ->with('feature_foo', $context)
            ->willReturn(true);

        $stashBag->add($customObjectStash = $this->createMock(StashInterface::class));
        $customObjectStash
            ->method('isActive')
            ->with('feature_foo', $context)
            ->willReturn(false);

        static::assertEquals(true, $manager->isActive('feature_foo', $context));
    }
}
