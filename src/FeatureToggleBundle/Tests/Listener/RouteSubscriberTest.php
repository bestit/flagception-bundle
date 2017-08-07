<?php

namespace BestIt\FeatureToggleBundle\Tests\Listener;

use BestIt\FeatureToggleBundle\Listener\RouteSubscriber;
use BestIt\FeatureToggleBundle\Manager\FeatureManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class RouteSubscriberTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Tests\Listener
 */
class RouteSubscriberTest extends TestCase
{
    /**
     * Test implement interface
     *
     * @return void
     */
    public function testImplementInterface()
    {
        $subscriber = new RouteSubscriber($this->createMock(FeatureManagerInterface::class));
        static::assertInstanceOf(EventSubscriberInterface::class, $subscriber);
    }

    public function testSubscribedEvents()
    {
        static::assertEquals(
            [KernelEvents::CONTROLLER => 'onKernelController'],
            RouteSubscriber::getSubscribedEvents()
        );
    }

    /**
     * Test request has no feature
     *
     * @return void
     */
    public function testRequestHasNoFeature()
    {
        $request = new Request();

        $event = $this->createMock(FilterControllerEvent::class);
        $event
            ->method('getRequest')
            ->willReturn($request);

        $manager = $this->createMock(FeatureManagerInterface::class);
        $manager
            ->expects(static::never())
            ->method('isActive');

        $subscriber = new RouteSubscriber($manager);
        $subscriber->onKernelController($event);
    }

    /**
     * Test feature is not active
     *
     * @return void
     */
    public function testFeatureIsNotActive()
    {
        $this->expectException(NotFoundHttpException::class);

        $request = new Request([], [], ['_feature' => 'feature_abc']);

        $event = $this->createMock(FilterControllerEvent::class);
        $event
            ->method('getRequest')
            ->willReturn($request);

        $manager = $this->createMock(FeatureManagerInterface::class);
        $manager
            ->expects(static::once())
            ->method('isActive')
            ->with('feature_abc')
            ->willReturn(false);

        $subscriber = new RouteSubscriber($manager);
        $subscriber->onKernelController($event);
    }

    /**
     * Test feature is  active
     *
     * @return void
     */
    public function testFeatureIsActive()
    {
        $request = new Request([], [], ['_feature' => 'feature_abc']);

        $event = $this->createMock(FilterControllerEvent::class);
        $event
            ->method('getRequest')
            ->willReturn($request);

        $manager = $this->createMock(FeatureManagerInterface::class);
        $manager
            ->expects(static::once())
            ->method('isActive')
            ->with('feature_abc')
            ->willReturn(true);

        $subscriber = new RouteSubscriber($manager);
        $subscriber->onKernelController($event);
    }
}
