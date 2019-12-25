<?php

namespace Flagception\Bundle\FlagceptionBundle\Profiler;

use Flagception\Bundle\FlagceptionBundle\Activator\TraceableChainActivator;
use Exception;
use Flagception\Decorator\ChainDecorator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class FeatureDataCollector
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\Profiler
 */
class FeatureDataCollector extends DataCollector
{
    /**
     * The profiler chain activator
     *
     * @var TraceableChainActivator
     */
    private $chainActivator;

    /**
     * The chain decorator
     *
     * @var ChainDecorator
     */
    private $chainDecorator;

    /**
     * FeatureDataCollector constructor.
     *
     * @param TraceableChainActivator $chainActivator
     * @param ChainDecorator $chainDecorator
     */
    public function __construct(TraceableChainActivator $chainActivator, ChainDecorator $chainDecorator)
    {
        $this->chainActivator = $chainActivator;
        $this->chainDecorator = $chainDecorator;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, Exception $exception = null)
    {
        $this->data = [
            'summary' => [
                'features' => 0,
                'activeFeatures' => 0,
                'inactiveFeatures' => 0,
                'corruptFeatures' => 0
            ],
            'requests' => [],
            'activators' => [],
            'decorators' => [],
            'trace' => $this->chainActivator->getTrace()
        ];

        // Activators
        foreach ($this->chainActivator->getActivators() as $offset => $activator) {
            $name = $activator->getName();

            $this->data['activators'][$name] = [
                'priority' => ++$offset,
                'name' => $name,
                'requests' => 0,
                'activeRequests' => 0,
                'inactiveRequests' => 0,
            ];
        }

        // Decorators
        foreach ($this->chainDecorator->getDecorators() as $offset => $decorator) {
            $name = $decorator->getName();

            $this->data['decorators'][$name] = [
                'priority' => ++$offset,
                'name' => $name
            ];
        }

        // Analyze trace
        foreach ($this->chainActivator->getTrace() as $trace) {
            if (!isset($this->data['requests'][$trace['feature']])) {
                $this->data['requests'][$trace['feature']] = [
                    'requests' => 0,
                    'activeRequests' => 0,
                    'inactiveRequests' => 0,
                    'activators' => []
                ];
            }

            $featureTrace = &$this->data['requests'][$trace['feature']];
            $featureTrace['requests']++;

            if ($trace['result'] === true) {
                $featureTrace['activeRequests']++;
            } else {
                $featureTrace['inactiveRequests']++;
            }

            foreach ($trace['stack'] as $activator => $result) {
                if ($result === true && !in_array($activator, $featureTrace['activators'], true)) {
                    $featureTrace['activators'][] = $activator;
                }

                $this->data['activators'][$activator]['requests']++;

                if ($result === true) {
                    $this->data['activators'][$activator]['activeRequests']++;
                } else {
                    $this->data['activators'][$activator]['inactiveRequests']++;
                }
            }
        }

        $this->data['summary']['features'] = count($this->data['requests']);
        foreach ($this->data['requests'] as $trace) {
            if ($trace['activeRequests'] > 0 && $trace['inactiveRequests'] === 0) {
                $this->data['summary']['activeFeatures']++;
            } elseif ($trace['inactiveRequests'] > 0 && $trace['activeRequests'] === 0) {
                $this->data['summary']['inactiveFeatures']++;
            } else {
                $this->data['summary']['corruptFeatures']++;
            }
        }
    }

    /**
     * Get all results
     *
     * @return array
     */
    public function getRequests()
    {
        return $this->data['requests'];
    }

    /**
     * Get all activators
     *
     * @return array
     */
    public function getActivators()
    {
        return $this->data['activators'];
    }

    /**
     * Get all decorators
     *
     * @return array
     */
    public function getDecorators()
    {
        return $this->data['decorators'];
    }

    /**
     * Get trace
     *
     * @return array
     */
    public function getTrace()
    {
        return $this->data['trace'];
    }

    /**
     * Get summary
     *
     * @return array
     */
    public function getSummary()
    {
        return $this->data['summary'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'flagception';
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->data = [];
    }
}
