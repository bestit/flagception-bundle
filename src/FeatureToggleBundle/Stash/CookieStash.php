<?php

namespace BestIt\FeatureToggleBundle\Stash;

use BestIt\FeatureToggleBundle\Model\Context;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class CookieStash
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Stash
 */
class CookieStash implements StashInterface
{
    /**
     * The request stack
     *
     * @var RequestStack
     */
    private $requestStack;

    /**
     * The cookie name
     *
     * @var string
     */
    private $cookieName;

    /**
     * CookieStash constructor.
     *
     * @param RequestStack $requestStack
     * @param string $cookieName
     */
    public function __construct(RequestStack $requestStack, string $cookieName)
    {
        $this->requestStack = $requestStack;
        $this->cookieName = $cookieName;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'cookie';
    }

    /**
     * @inheritdoc
     */
    public function isActive(string $name, Context $context): bool
    {
        if (!$request = $this->requestStack->getMasterRequest()) {
            return false;
        }

        if (!$cookie = $request->cookies->get($this->cookieName)) {
            return false;
        }

        return in_array($name, array_map('trim', explode(',', $cookie)), true);
    }
}
