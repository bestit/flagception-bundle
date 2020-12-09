<?php

namespace Flagception\Bundle\FlagceptionBundle\Twig;

use Flagception\Bundle\FlagceptionBundle\Event\ContextResolveEvent;
use Flagception\Manager\FeatureManagerInterface;
use Flagception\Model\Context;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * Class ToggleExtension
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\Twig
 */
class ToggleExtension extends AbstractExtension
{
    /**
     * The manager
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
     * ToggleExtension constructor.
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
     * Is feature name active
     *
     * @param string $name
     * @param array $contextValues
     *
     * @return bool
     */
    public function isActive($name, array $contextValues = [])
    {
        $context = new Context();
        foreach ($contextValues as $contextKey => $contextValue) {
            $context->add($contextKey, $contextValue);
        }

        if (null !== $this->eventDispatcher) {
            $contextEvent = $this->eventDispatcher->dispatch(new ContextResolveEvent($name, $context));
            $context = $contextEvent->getContext();
        }

        return $this->manager->isActive($name, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('feature', [$this, 'isActive']),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getTests()
    {
        return [
            new TwigTest('active feature', [$this, 'isActive']),
        ];
    }

    /**
     * Returns the name
     * (needed for supporting twig <1.26)
     *
     * @return string
     */
    public function getName()
    {
        return 'flagception';
    }
}
