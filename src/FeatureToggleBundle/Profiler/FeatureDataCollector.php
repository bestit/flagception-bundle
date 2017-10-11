<?php

namespace BestIt\FeatureToggleBundle\Profiler;

use BestIt\FeatureToggleBundle\Bag\StackBag;
use BestIt\FeatureToggleBundle\Model\StackGroup;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class FeatureDataCollector
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Profiler
 */
class FeatureDataCollector extends DataCollector
{
    /**
     * The stack bag
     *
     * @var StackBag
     */
    private $stackBag;

    /**
     * FeatureDataCollector constructor.
     *
     * @param StackBag $stackBag
     */
    public function __construct(StackBag $stackBag)
    {
        $this->stackBag = $stackBag;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, Exception $exception = null)
    {
        $grouped = [];

        foreach ($this->stackBag->all() as $stack) {
            $group = $grouped[$stack->getFeatureName()] ?? new StackGroup($stack->getFeatureName());

            if ($stashName = $stack->getStashName()) {
                $group->addStash($stashName);
            }

            if ($stack->isActive()) {
                $group->increaseActive();
            } else {
                $group->increaseInactive();
            }

            $grouped[$stack->getFeatureName()] = $group;
        }

        $this->data = [
            'stack' => $this->stackBag,
            'grouped' => $grouped
        ];
    }

    /**
     * Get all stacks
     *
     * @return StackBag
     */
    public function getStack(): StackBag
    {
        return $this->data['stack'];
    }

    /**
     * Get all grouped stacks / features
     *
     * @return StackGroup[]
     */
    public function getGrouped(): array
    {
        return $this->data['grouped'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'best_it_feature_toggle.profiler.feature_data_collector';
    }
}
