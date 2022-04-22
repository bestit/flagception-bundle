<?php

namespace Flagception\Bundle\FlagceptionBundle\Activator;

use Flagception\Activator\ChainActivator;
use Flagception\Model\Context;

/**
 * Decorate an activator and make it traceable
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\Activator
 */
class TraceableChainActivator extends ChainActivator
{
    /**
     * Trace of this chain activator
     *
     * @var array
     */
    private $trace = [];

    /**
     * {@inheritdoc}
     */
    public function isActive($name, Context $context): bool
    {
        $stack = [];
        $result = false;
        foreach ($this->getActivators() as $activator) {
            if ($activator->isActive($name, $context) === true) {
                $result = $stack[$activator->getName()] = true;

                break;
            }

            $stack[$activator->getName()] = $result;
        }

        $this->trace[] = [
            'feature' => $name,
            'context' => $context,
            'result' => $result,
            'stack' => $stack
        ];

        return $result;
    }

    /**
     * Get trace
     *
     * @return array
     */
    public function getTrace()
    {
        return $this->trace;
    }
}
