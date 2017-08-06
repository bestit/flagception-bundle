<?php

namespace Tests\BestIt\FeatureToggleBundle\Event;

use BestIt\FeatureToggleBundle\Event\PostFeatureEvent;
use BestIt\FeatureToggleBundle\Model\Context;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class PostFeatureEventTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\Event
 */
class PostFeatureEventTest extends TestCase
{
    /**
     * Test extends base event
     *
     * @return void
     */
    public function testExtendEvent()
    {
        $event = new PostFeatureEvent('feature_abc', true, new Context());

        static::assertInstanceOf(Event::class, $event);
    }

    /**
     * Test get context
     *
     * @return void
     */
    public function testGetContext()
    {
        $event = new PostFeatureEvent('feature_abc', true, $context = new Context());
        $context->add('role', 'ROLE_ADMIN');

        static::assertEquals($context, $event->getContext());
    }

    /**
     * Test get feature
     *
     * @return void
     */
    public function testGetFeature()
    {
        $event = new PostFeatureEvent('feature_abc', true, new Context());

        static::assertEquals('feature_abc', $event->getFeature());
    }

    /**
     * Test is active
     *
     * @return void
     */
    public function testIsActive()
    {
        $event = new PostFeatureEvent('feature_abc', true, new Context());

        static::assertTrue($event->isActive());
    }

    /**
     * Test get stash name is null
     *
     * @return void
     */
    public function testGetStashNameIsNull()
    {
        $event = new PostFeatureEvent('feature_abc', true, new Context());

        static::assertEquals(null, $event->getStashName());
    }

    /**
     * Test get stash name is filled
     *
     * @return void
     */
    public function testGetStashNameIsFilled()
    {
        $event = new PostFeatureEvent('feature_abc', true, new Context(), 'config');

        static::assertEquals('config', $event->getStashName());
    }
}
