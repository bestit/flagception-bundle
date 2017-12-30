<?php

namespace Flagception\Bundle\FlagceptionBundle\Model;

use Flagception\Model\Context;

/**
 * Class Result
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\Model
 */
class Result
{
    /**
     * The feature name
     *
     * @var string
     */
    private $featureName;

    /**
     * Is feature is active
     *
     * @var bool
     */
    private $isActive;

    /**
     * The context
     *
     * @var Context
     */
    private $context;

    /**
     * The activator name
     *
     * @var string
     */
    private $activator;

    /**
     * Stack constructor.
     *
     * @param string $featureName
     * @param bool $isActive
     * @param Context $context
     * @param null|string $activator
     */
    public function __construct($featureName, $isActive, Context $context, $activator)
    {
        $this->featureName = $featureName;
        $this->isActive = $isActive;
        $this->context = $context;
        $this->activator = $activator;
    }

    /**
     * Get featureName
     *
     * @return string
     */
    public function getFeatureName()
    {
        return $this->featureName;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * Get context
     *
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Get activator name
     *
     * @return string
     */
    public function getActivator()
    {
        return $this->activator;
    }
}
