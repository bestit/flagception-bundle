<?php

namespace BestIt\FeatureToggleBundle\Profiler;

use BestIt\FeatureToggleBundle\Bag\StashBag;
use BestIt\FeatureToggleBundle\Stash\StashInterface;
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
     * The stash bag
     * @var StashBag
     */
    private $stashBag;

    /**
     * FeatureDataCollector constructor.
     *
     * @param StashBag $stashBag
     */
    public function __construct(StashBag $stashBag)
    {
        $this->stashBag = $stashBag;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, Exception $exception = null)
    {
        $features = [];

        /** @var StashInterface $stash */
        foreach ($this->stashBag as $stash) {
            foreach ($stash->getActiveFeatures() as $feature) {
                $features[$feature][] = $stash->getName();
            }
        }

        $this->data = [
            'activeFeatures' => $features
        ];
    }

    /**
     * Get active features and the stashes who activate this
     *
     * @return array
     */
    public function getActiveFeatures(): array
    {
        return $this->data['activeFeatures'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'best_it_feature_toggle.profiler.feature_data_collector';
    }
}
