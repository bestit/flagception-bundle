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
     * The context
     *
     * @var Context
     */
    private $context;

    public function __construct(Context $context = null)
    {
        $this->context = $context ?? new Context();
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
