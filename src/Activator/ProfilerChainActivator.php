<?php

namespace Flagception\Bundle\FlagceptionBundle\Activator;

use Flagception\Bundle\FlagceptionBundle\Model\Result;
use Flagception\Activator\FeatureActivatorInterface;
use Flagception\Bundle\FlagceptionBundle\Bag\FeatureResultBag;
use Flagception\Model\Context;

/**
 * Class ProfilerChainActivator
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\Activator
 */
class ProfilerChainActivator implements FeatureActivatorInterface
{
    /**
     * Ordered array of feature activators
     *
     * @var FeatureActivatorInterface[]
     */
    private $bag = [];

    /**
     * The result bag
     *
     * @var FeatureResultBag
     */
    private $resultBag;

    /**
     * ProfilerChainActivator constructor.
     *
     * @param FeatureResultBag $resultBag
     */
    public function __construct(FeatureResultBag $resultBag)
    {
        $this->resultBag = $resultBag;
    }

    /**
     * Add activator
     *
     * @param FeatureActivatorInterface $activator
     *
     * @return void
     */
    public function add(FeatureActivatorInterface $activator)
    {
        $this->bag[] = $activator;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'profiler_chain';
    }

    /**
     * {@inheritdoc}
     */
    public function isActive($name, Context $context)
    {
        $result = false;
        foreach ($this->bag as $activator) {
            if ($activator->isActive($name, $context) === true) {
                $result = true;
            }

            $this->resultBag->add(new Result($name, $result, $context, $activator->getName()));

            if ($result === true) {
                break;
            }
        }

        return $result;
    }
}
