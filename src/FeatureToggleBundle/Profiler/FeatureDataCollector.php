<?php

namespace BestIt\FeatureToggleBundle\Profiler;

use BestIt\FeatureToggleBundle\Bag\StackBag;
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
        $this->data = [
            'features' => $this->stackBag
        ];
    }

    /**
     * Get all features
     *
     * @return StackBag
     */
    public function getFeatures(): StackBag
    {
        return $this->data['features'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'best_it_feature_toggle.profiler.feature_data_collector';
    }
}
