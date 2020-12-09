<?php

namespace Flagception\Bundle\FlagceptionBundle\Event;

use Flagception\Model\Context;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class ContextResolveEvent
 *
 * @author Antonio J. García Lagar <aj@garcialagar.es>
 * @package Flagception\Bundle\FlagceptionBundle\Listener
 */
class ContextResolveEvent extends Event
{
    /**
     * The feature
     *
     * @var string
     */
    private $feature;

    /**
     * The context
     *
     * @var Context
     */
    private $context;

    public function __construct(string $feature, Context $context = null)
    {
        $this->feature = $feature;
        $this->context = $context ?? new Context();
    }

    public function getFeature(): string
    {
        return $this->feature;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function setContext(Context $context): void
    {
        $this->context = $context;
    }
}
