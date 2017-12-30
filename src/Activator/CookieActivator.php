<?php

namespace Flagception\Bundle\FlagceptionBundle\Activator;

use Flagception\Activator\FeatureActivatorInterface;
use Flagception\Model\Context;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class CookieActivator
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\Activator
 */
class CookieActivator implements FeatureActivatorInterface
{
    /**
     * The for cookie enabled features
     *
     * @var array
     */
    private $features;

    /**
     * Cookie name
     *
     * @var string
     */
    private $name;

    /**
     * Cookie separator
     *
     * @var string
     */
    private $separator;

    /**
     * The request stack
     *
     * @var RequestStack
     */
    private $requestStack;

    /**
     * CookieActivator constructor.
     *
     * @param array $features
     * @param string $name
     * @param string $separator
     * @param RequestStack $requestStack
     */
    public function __construct(array $features, $name, $separator, RequestStack $requestStack)
    {
        $this->features = $features;
        $this->name = $name;
        $this->separator = $separator;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'cookie';
    }

    /**
     * {@inheritdoc}
     */
    public function isActive($name, Context $context)
    {
        if (!in_array($name, $this->features, true)) {
            return false;
        }

        if (!$request = $this->requestStack->getMasterRequest()) {
            return false;
        }

        if (!$cookie = $request->cookies->get($this->name)) {
            return false;
        }

        return in_array($name, array_map('trim', explode($this->separator, $cookie)), true);
    }
}
