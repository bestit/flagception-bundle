<?php

namespace BestIt\FeatureToggleBundle\Manager;

use BestIt\FeatureToggleBundle\Bag\ContextDecoratorBag;
use BestIt\FeatureToggleBundle\Bag\StashBag;
use BestIt\FeatureToggleBundle\Decorator\ContextDecoratorInterface;
use BestIt\FeatureToggleBundle\Event\PostFeatureEvent;
use BestIt\FeatureToggleBundle\Event\PreFeatureEvent;
use BestIt\FeatureToggleBundle\Model\Context;
use BestIt\FeatureToggleBundle\Stash\StashInterface;
use BestIt\FeatureToggleBundle\ToggleEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class FeatureManager
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Manager
 */
class FeatureManager implements FeatureManagerInterface
{
    /**
     * The stash bag
     *
     * @var StashBag
     */
    private $stashBag;

    /**
     * The context decorator bag
     *
     * @var ContextDecoratorBag
     */
    private $contextDecoratorBag;

    /**
     * The event dispatcher
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * FeatureManager constructor.
     *
     * @param StashBag $stashBag
     * @param ContextDecoratorBag $decoratorBag
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        StashBag $stashBag,
        ContextDecoratorBag $decoratorBag,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->stashBag = $stashBag;
        $this->contextDecoratorBag = $decoratorBag;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive(string $name, Context $context = null): bool
    {
        if ($context === null) {
            $context = new Context();
        }

        /** @var ContextDecoratorInterface $decorator */
        foreach ($this->contextDecoratorBag as $decorator) {
            $decorator->decorate($context);
        }

        // Dispatch pre event
        $this->eventDispatcher->dispatch(
            ToggleEvents::FEATURE_IS_ACTIVE_PRE,
            new PreFeatureEvent($name, $context)
        );

        $result = false;
        $stashName = null;

        /** @var StashInterface $stash */
        foreach ($this->stashBag as $stash) {
            if ($stash->isActive($name, $context) === true) {
                $result = true;
                $stashName = $stash->getName();
                break;
            }
        }

        // Dispatch post event
        $this->eventDispatcher->dispatch(
            ToggleEvents::FEATURE_IS_ACTIVE_POST,
            new PostFeatureEvent($name, $result, $context, $stashName)
        );

        return $result;
    }
}
