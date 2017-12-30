<?php

namespace Flagception\Bundle\FlagceptionBundle\Profiler;

use Flagception\Bundle\FlagceptionBundle\Bag\FeatureResultBag;
use Exception;
use Flagception\Bundle\FlagceptionBundle\Model\ResultGroup;
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
     * The feature result bag
     *
     * @var FeatureResultBag
     */
    private $resultBag;

    /**
     * FeatureDataCollector constructor.
     *
     * @param FeatureResultBag $resultBag
     */
    public function __construct(FeatureResultBag $resultBag)
    {
        $this->resultBag = $resultBag;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, Exception $exception = null)
    {
        $grouped = [];

        foreach ($this->resultBag->all() as $result) {
            if (array_key_exists($result->getFeatureName(), $grouped)) {
                $group = $grouped[$result->getFeatureName()];
            } else {
                $group = new ResultGroup($result->getFeatureName());
            }

            if ($result->isActive() && $activator = $result->getActivator()) {
                $group->addActivator($activator);
            }

            if ($result->isActive()) {
                $group->increaseActive();
            } else {
                $group->increaseInactive();
            }

            $grouped[$result->getFeatureName()] = $group;
        }

        $this->data = [
            'results' => $this->resultBag->all(),
            'groupedResults' => $grouped
        ];
    }

    /**
     * Get all results
     *
     * @return FeatureResultBag
     */
    public function getResults()
    {
        return $this->data['results'];
    }

    /**
     * Get all grouped results / features
     *
     * @return ResultGroup[]
     */
    public function getGroupedResults()
    {
        return $this->data['groupedResults'];
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
        $this->resultBag->clear();
    }
}
