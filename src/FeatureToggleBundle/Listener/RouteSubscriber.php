<?php

namespace BestIt\FeatureToggleBundle\Listener;

use BestIt\FeatureToggleBundle\Manager\FeatureManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class RouteSubscriber
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Listener
 */
class RouteSubscriber implements EventSubscriberInterface
{
    /**
     * Attribute name
     */
    const TAG = '_feature';

    /**
     * The feature manager
     *
     * @var FeatureManagerInterface
     */
    private $manager;

    /**
     * RouteSubscriber constructor.
     *
     * @param FeatureManagerInterface $manager
     */
    public function __construct(FeatureManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Filter in route annotation
     *
     * @param FilterControllerEvent $event
     *
     * @return void
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!$event->getRequest()->attributes->has(static::TAG)) {
            return;
        }

        $feature = $event->getRequest()->attributes->get(static::TAG);
        if (!$this->manager->isActive($feature)) {
            throw new NotFoundHttpException(sprintf('Feature `%s` is not active', $feature));
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
