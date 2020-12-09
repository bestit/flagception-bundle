<?php

namespace Flagception\Bundle\FlagceptionBundle\Listener;

use Flagception\Bundle\FlagceptionBundle\Event\ContextResolveEvent;
use Flagception\Manager\FeatureManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class RoutingMetadataSubscriber
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\Listener
 */
class RoutingMetadataSubscriber implements EventSubscriberInterface
{
    /**
     * Feature key for routing annotation
     *
     * @var string
     */
    const FEATURE_KEY = '_feature';

    /**
     * The feature manager
     *
     * @var FeatureManagerInterface
     */
    private $manager;

    /**
     * The event dispatcher
     *
     * @var ?EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * RoutingMetadataSubscriber constructor.
     *
     * @param FeatureManagerInterface $manager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(FeatureManagerInterface $manager, EventDispatcherInterface $eventDispatcher = null)
    {
        $this->manager = $manager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Filter by routing metadata
     *
     * @param ControllerEvent $event
     *
     * @return void
     * @throws NotFoundHttpException
     */
    public function onKernelController(ControllerEvent $event)
    {
        if (!$event->getRequest()->attributes->has(static::FEATURE_KEY)) {
            return;
        }

        $featureNames = (array) $event->getRequest()->attributes->get(static::FEATURE_KEY);
        foreach ($featureNames as $featureName) {
            $context = null;
            if (null !== $this->eventDispatcher) {
                $contextEvent = $this->eventDispatcher->dispatch(new ContextResolveEvent($featureName));
                $context = $contextEvent->getContext();
            }
            if (!$this->manager->isActive($featureName, $context)) {
                throw new NotFoundHttpException('Feature for this class is not active.');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
