<?php

namespace Flagception\Tests\FlagceptionBundle\Listener;

use Doctrine\Common\Annotations\AnnotationReader;
use Flagception\Bundle\FlagceptionBundle\Event\ContextResolveEvent;
use Flagception\Bundle\FlagceptionBundle\Listener\AnnotationSubscriber;
use Flagception\Manager\FeatureManagerInterface;
use Flagception\Model\Context;
use Flagception\Tests\FlagceptionBundle\Fixtures\Helper\AnnotationTestClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class AnnotationSubscriberTest
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\Listener
 */
class AnnotationSubscriberTest extends TestCase
{
    /**
     * Test implement interface
     *
     * @return void
     */
    public function testImplementInterface()
    {
        $manager = $this->createMock(FeatureManagerInterface::class);
        $subscriber = new AnnotationSubscriber(new AnnotationReader(), $manager);

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
            [KernelEvents::CONTROLLER => 'onKernelController',],
            AnnotationSubscriber::getSubscribedEvents()
        );
    }

    /**
     * Test controller is closure
     *
     * @return void
     */
    public function testControllerIsClosure()
    {
        $manager = $this->createMock(FeatureManagerInterface::class);
        $manager->expects(static::never())->method('isActive');

        $event = $this->createControllerEvent(
            function () {
            }
        );

        $subscriber = new AnnotationSubscriber(new AnnotationReader(), $manager);
        $subscriber->onKernelController($event);
    }

    /**
     * Test on class with active feature
     *
     * @return void
     */
    public function testOnClassIsActive()
    {
        $manager = $this->createMock(FeatureManagerInterface::class);
        $manager->method('isActive')->with('feature_abc')->willReturn(true);

        $event = $this->createControllerEvent([
            new AnnotationTestClass(),
            'normalMethod'
        ]);

        $subscriber = new AnnotationSubscriber(new AnnotationReader(), $manager);
        $subscriber->onKernelController($event);
    }

    /**
     * Test on class with inactive feature
     *
     * @return void
     */
    public function testOnClassIsInactive()
    {
        $this->expectException(NotFoundHttpException::class);

        $manager = $this->createMock(FeatureManagerInterface::class);
        $manager->method('isActive')->with('feature_abc')->willReturn(false);

        $event = $this->createControllerEvent([
            new AnnotationTestClass(),
            'normalMethod'
        ]);

        $subscriber = new AnnotationSubscriber(new AnnotationReader(), $manager);
        $subscriber->onKernelController($event);
    }

    /**
     * Test on method with active feature
     *
     * @return void
     */
    public function testOnMethodIsActive()
    {
        $manager = $this->createMock(FeatureManagerInterface::class);
        $manager
            ->method('isActive')
            ->withConsecutive(['feature_abc'], ['feature_def'])
            ->willReturnOnConsecutiveCalls(true, true);

        $event = $this->createControllerEvent([
            new AnnotationTestClass(),
            'invalidMethod'
        ]);

        $subscriber = new AnnotationSubscriber(new AnnotationReader(), $manager);
        $subscriber->onKernelController($event);
    }

    /**
     * Test on method with inactive feature
     *
     * @return void
     */
    public function testOnMethodIsInactive()
    {
        $this->expectException(NotFoundHttpException::class);

        $manager = $this->createMock(FeatureManagerInterface::class);
        $manager
            ->method('isActive')
            ->withConsecutive(['feature_abc'], ['feature_def'])
            ->willReturnOnConsecutiveCalls(true, false);

        $event = $this->createControllerEvent([
            new AnnotationTestClass(),
            'invalidMethod'
        ]);

        $subscriber = new AnnotationSubscriber(new AnnotationReader(), $manager);
        $subscriber->onKernelController($event);
    }

    /**
     * Test event is dispatched when event dispatcher exists
     *
     * @return void
     */
    public function testEventIsDispatchedWhenEventDispatcherExists()
    {
        $context = new Context();
        $manager = $this->createMock(FeatureManagerInterface::class);
        $manager->method('isActive')->with('feature_abc', $context)->willReturn(true);

        $listener = function (ContextResolveEvent $event) use ($context) {
            $this->assertSame('feature_abc', $event->getFeature());
            $this->assertNotSame($event->getContext(), $context);
            $event->setContext($context);
            $this->assertSame($event->getContext(), $context);
            return $event;
        };

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addListener(ContextResolveEvent::class, $listener);

        $event = $this->createControllerEvent([
            new AnnotationTestClass(),
            'normalMethod'
        ]);

        $subscriber = new AnnotationSubscriber(new AnnotationReader(), $manager, $eventDispatcher);
        $subscriber->onKernelController($event);
    }

    /**
     * Create ControllerEvent
     *
     * @param $controller
     *
     * @return ControllerEvent
     */
    private function createControllerEvent($controller): ControllerEvent
    {
        return new ControllerEvent(
            $this->createMock(HttpKernelInterface::class),
            $controller,
            new Request(),
            HttpKernelInterface::MASTER_REQUEST
        );
    }
}
