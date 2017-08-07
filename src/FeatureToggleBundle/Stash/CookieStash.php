<?php

namespace BestIt\FeatureToggleBundle\Stash;

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
     * {@inheritdoc}
     */
    public function getActiveFeatures(): array
    {
        if (!$request = $this->requestStack->getMasterRequest()) {
            return [];
        }

        if (!$cookie = $request->cookies->get($this->cookieName)) {
            return [];
        }

        return array_map('trim', explode(',', $cookie));
    }
}
