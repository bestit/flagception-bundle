<?php

namespace BestIt\FeatureToggleBundle\Listener;

use BestIt\FeatureToggleBundle\Manager\FeatureManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class RoutingMetadataSubscriber
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Listener
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
     * RoutingMetadataSubscriber constructor.
     *
     * @param FeatureManagerInterface $manager
     */
    public function __construct(FeatureManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Filter by routing metadata
     *
     * @param FilterControllerEvent $event
     *
     * @return void
     * @throws NotFoundHttpException
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!$event->getRequest()->attributes->has(static::FEATURE_KEY)) {
            return;
        }

        $featureName = $event->getRequest()->attributes->get(static::FEATURE_KEY);
        if (!$this->manager->isActive($featureName)) {
            throw new NotFoundHttpException('Feature for this class is not active.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
