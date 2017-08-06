<?php

namespace BestIt\FeatureToggleBundle\Listener;

use BestIt\FeatureToggleBundle\Bag\StackBag;
use BestIt\FeatureToggleBundle\Event\PostFeatureEvent;
use BestIt\FeatureToggleBundle\Model\Stack;
use BestIt\FeatureToggleBundle\ToggleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class StackCollectorSubscriber
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Listener
 */
class StackCollectorSubscriber implements EventSubscriberInterface
{
    /**
     * The stack bag
     *
     * @var StackBag
     */
    private $stackBag;

    /**
     * StackCollectorSubscriber constructor.
     *
     * @param StackBag $stackBag
     */
    public function __construct(StackBag $stackBag)
    {
        $this->stackBag = $stackBag;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ToggleEvents::FEATURE_IS_ACTIVE_POST => 'onResult',
        ];
    }

    /**
     * Collect all results
     *
     * @param PostFeatureEvent $event
     *
     * @return void
     */
    public function onResult(PostFeatureEvent $event)
    {
        $stack = new Stack(
            $event->getFeature(),
            $event->isActive(),
            $event->getContext(),
            $event->getStashName()
        );

        if (!$this->stackBag->has($stack)) {
            $this->stackBag->add($stack);
        }
    }
}
