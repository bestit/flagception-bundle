<?php

namespace Flagception\Tests\FlagceptionBundle\Listener;

use Flagception\Bundle\FlagceptionBundle\Listener\RoutingMetadataSubscriber;
use Flagception\Manager\FeatureManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class RoutingMetadataSubscriberTest
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\Listener
 */
class RoutingMetadataSubscriberTest extends TestCase
{
    /**
     * Test implement interface
     *
     * @return void
     */
    public function testImplementInterface()
    {
        $subscriber = new RoutingMetadataSubscriber($this->createMock(FeatureManagerInterface::class));
        static::assertInstanceOf(EventSubscriberInterface::class, $subscriber);
    }

    /**
     * Test subscribed events
     *
     * @return void
     */
    public function testSubscribedEvents()
    {
        static::assertEquals(
            [KernelEvents::CONTROLLER => 'onKernelController'],
            RoutingMetadataSubscriber::getSubscribedEvents()
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
        $event = $this->createControllerEvent($request);

        $manager = $this->createMock(FeatureManagerInterface::class);
        $manager
            ->expects(static::never())
            ->method('isActive');

        $subscriber = new RoutingMetadataSubscriber($manager);
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
        $event = $this->createControllerEvent($request);

        $manager = $this->createMock(FeatureManagerInterface::class);
        $manager
            ->expects(static::once())
            ->method('isActive')
            ->with('feature_abc')
            ->willReturn(false);

        $subscriber = new RoutingMetadataSubscriber($manager);
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
        $event = $this->createControllerEvent($request);

        $manager = $this->createMock(FeatureManagerInterface::class);
        $manager
            ->expects(static::once())
            ->method('isActive')
            ->with('feature_abc')
            ->willReturn(true);

        $subscriber = new RoutingMetadataSubscriber($manager);
        $subscriber->onKernelController($event);
    }

    /**
     * Create ControllerEvent
     *
     * @param $controller
     *
     * @return ControllerEvent
     */
    private function createControllerEvent($request): ControllerEvent
    {
        return new ControllerEvent(
            $this->createMock(HttpKernelInterface::class),
            [$this, 'testFeatureIsActive'],
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );
    }

    /**
     * Test features are not active
     *
     * @return void
     */
    public function testRouteWithMultipleFeaturesIsNotActive()
    {
        $this->expectException(NotFoundHttpException::class);

        $request = new Request([], [], ['_feature' => ['feature_abc', 'feature_def']]);

        $event = new ControllerEvent(
            $this->createMock(HttpKernelInterface::class),
            function () {
                return new Response();
            },
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $manager = $this->createMock(FeatureManagerInterface::class);
        $manager
            ->expects(static::exactly(2))
            ->method('isActive')
            ->withConsecutive(['feature_abc'], ['feature_def'])
            ->willReturnOnConsecutiveCalls(true, false);

        $subscriber = new RoutingMetadataSubscriber($manager);
        $subscriber->onKernelController($event);
    }

    /**
     * Test features are active
     *
     * @return void
     */
    public function testRouteWithMultipleFeaturesIsActive()
    {
        $request = new Request([], [], ['_feature' => ['feature_abc', 'feature_def']]);

        $event = new ControllerEvent(
            $this->createMock(HttpKernelInterface::class),
            function () {
                return new Response();
            },
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $manager = $this->createMock(FeatureManagerInterface::class);
        $manager
            ->expects(static::exactly(2))
            ->method('isActive')
            ->withConsecutive(['feature_abc'], ['feature_def'])
            ->willReturnOnConsecutiveCalls(true, true);

        $subscriber = new RoutingMetadataSubscriber($manager);
        $subscriber->onKernelController($event);
    }
}
