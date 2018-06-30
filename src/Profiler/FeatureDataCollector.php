<?php

namespace Flagception\Bundle\FlagceptionBundle\Profiler;

use Flagception\Activator\FeatureActivatorInterface;
use Flagception\Bundle\FlagceptionBundle\Activator\ProfilerChainActivator;
use Exception;
use Flagception\Decorator\ChainDecorator;
use Flagception\Decorator\ContextDecoratorInterface;
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
     * @var ProfilerChainActivator
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
     * @param ProfilerChainActivator $chainActivator
     * @param ChainDecorator $chainDecorator
     */
    public function __construct(ProfilerChainActivator $chainActivator, ChainDecorator $chainDecorator)
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
            'requests' => $this->chainActivator->getRequestLog(),
            'activators' => array_map(function (FeatureActivatorInterface $activator) {
                return $activator->getName();
            }, $this->chainActivator->getActivators()),
            'decorators' => array_map(function (ContextDecoratorInterface $decorator) {
                return $decorator->getName();
            }, $this->chainDecorator->getDecorators()),
        ];
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
