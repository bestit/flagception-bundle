<?php

namespace Tests\BestIt\FeatureToggleBundle\Manager;

use BestIt\FeatureToggleBundle\Bag\ContextDecoratorBag;
use BestIt\FeatureToggleBundle\Bag\StashBag;
use BestIt\FeatureToggleBundle\Decorator\ContextDecoratorInterface;
use BestIt\FeatureToggleBundle\Event\PostFeatureEvent;
use BestIt\FeatureToggleBundle\Event\PreFeatureEvent;
use BestIt\FeatureToggleBundle\Manager\FeatureManager;
use BestIt\FeatureToggleBundle\Manager\FeatureManagerInterface;
use BestIt\FeatureToggleBundle\Model\Context;
use BestIt\FeatureToggleBundle\Stash\ArrayStash;
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
            $this->createMock(ContextDecoratorBag::class),
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
            $contextDecoratorBag = new ContextDecoratorBag(),
            $this->createMock(EventDispatcherInterface::class)
        );

        $contextDecoratorBag->add($contextDecorator = $this->createMock(ContextDecoratorInterface::class));
        $contextDecorator
            ->method('decorate')
            ->with(static::isInstanceOf(Context::class))
            ->willReturnArgument(0);

        $stashBag->add(new ArrayStash());

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
            $contextDecoratorBag = new ContextDecoratorBag(),
            $eventDispatcher = $this->createMock(EventDispatcherInterface::class)
        );

        $contextDecoratorBag->add($contextDecorator = $this->createMock(ContextDecoratorInterface::class));
        $contextDecorator
            ->method('decorate')
            ->with(static::isInstanceOf(Context::class))
            ->willReturnArgument(0);

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
                        'array'
                    )
                ]
            );

        $stashBag->add(new ArrayStash(['feature_foo']));

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
            $contextDecoratorBag = new ContextDecoratorBag(),
            $eventDispatcher = $this->createMock(EventDispatcherInterface::class)
        );

        $contextDecoratorBag->add($contextDecorator = $this->createMock(ContextDecoratorInterface::class));
        $contextDecorator
            ->method('decorate')
            ->with(static::isInstanceOf(Context::class))
            ->willReturnArgument(0);

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
                        'array'
                    )
                ]
            );

        $stashBag->add(new ArrayStash(['feature_bar']));
        $stashBag->add(new ArrayStash(['feature_foo']));

        static::assertEquals(true, $manager->isActive('feature_foo', $context));
    }
}
