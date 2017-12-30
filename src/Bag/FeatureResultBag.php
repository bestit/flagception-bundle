<?php

namespace Flagception\Bundle\FlagceptionBundle\Bag;

use Flagception\Bundle\FlagceptionBundle\Model\Result;

/**
 * Class FeatureResultBag
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\Bag
 */
class FeatureResultBag
{
    /**
     * Result array
     *
     * @var Result[]
     */
    private $bag = [];

    /**
     * Add result
     *
     * @param Result $result
     *
     * @return void
     */
    public function add(Result $result)
    {
        $this->bag[] = $result;
    }

    /**
     * Get all results
     *
     * @return Result[]
     */
    public function all()
    {
        return $this->bag;
    }

    /**
     * Clear the bag
     *
     * @return void
     */
    public function clear()
    {
        $this->bag = [];
    }
}
