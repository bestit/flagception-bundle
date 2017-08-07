<?php

namespace Tests\BestIt\FeatureToggleBundle\Event;

use BestIt\FeatureToggleBundle\Event\FeatureEventInterface;
use BestIt\FeatureToggleBundle\Event\PreFeatureEvent;
use BestIt\FeatureToggleBundle\Model\Context;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class PreFeatureEventTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\Event
 */
class PreFeatureEventTest extends TestCase
{
    /**
     * Test extends base event
     *
     * @return void
     */
    public function testExtendEvent()
    {
        $event = new PreFeatureEvent('feature_abc', new Context());

        static::assertInstanceOf(Event::class, $event);
    }

    /**
     * Test implement event
     *
     * @return void
     */
    public function testImplementEvent()
    {
        $event = new PreFeatureEvent('feature_abc', new Context());

        static::assertInstanceOf(FeatureEventInterface::class, $event);
    }

    /**
     * Test get context
     *
     * @return void
     */
    public function testGetContext()
    {
        $event = new PreFeatureEvent('feature_abc', $context = new Context());
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
        $event = new PreFeatureEvent('feature_abc', new Context());

        static::assertEquals('feature_abc', $event->getFeature());
    }
}
