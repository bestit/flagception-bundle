<?php

namespace Flagception\Bundle\FlagceptionBundle\Activator;

use Flagception\Activator\ChainActivator;
use Flagception\Model\Context;

/**
 * Class ProfilerChainActivator
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\Activator
 */
class ProfilerChainActivator extends ChainActivator
{
    /**
     * Log for each result from chained activators
     *
     * @var array
     */
    private $requestLog = [];

    /**
     * Get request log
     *
     * @return array
     */
    public function getRequestLog()
    {
        return $this->requestLog;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'chain';
    }

    /**
     * {@inheritdoc}
     */
    public function isActive($name, Context $context)
    {
        if (isset($this->requestLog[$name])) {
            $log = $this->requestLog[$name];
        } else {
            $log = [
                'requests' => 0,
                'activeRequests' => 0,
                'inactiveRequests' => 0,
                'activators' => [],
                'stack' => []
            ];
        }

        $result = false;
        foreach ($this->getActivators() as $activator) {
            if ($activator->isActive($name, $context) === true) {
                $result = true;
            }

            // Log result
            $log['stack'][] = [
                'result' => $result,
                'context' => $context,
                'activator' => $activator->getName()
            ];

            if ($result === true && !in_array($activator->getName(), $log['activators'], true)) {
                $log['activators'][] = $activator->getName();
            }

            if ($result === true) {
                break;
            }
        }

        // Log request
        $log['requests']++;

        if ($result === true) {
            $log['activeRequests']++;
        } else {
            $log['inactiveRequests']++;
        }

        // Save log
        $this->requestLog[$name] = $log;

        return $result;
    }
}
