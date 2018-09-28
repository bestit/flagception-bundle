<?php

namespace Flagception\Bundle\FlagceptionBundle\Activator;

use Flagception\Activator\ChainActivator;
use Flagception\Model\Context;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Role\Role;

/**
 * Activate features by role
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\Activator
 */
class RoleActivator extends ChainActivator
{
    /**
     * The token storage
     *
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * Allowed roles
     *
     * @var array
     */
    private $roles;

    /**
     * RoleActivator constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param array $roles
     */
    public function __construct(TokenStorageInterface $tokenStorage, array $roles)
    {
        $this->tokenStorage = $tokenStorage;
        $this->roles = $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'role';
    }

    /**
     * {@inheritdoc}
     */
    public function isActive($name, Context $context)
    {
        if (!array_key_exists($name, $this->roles)) {
            return false;
        }

        if (null === $token = $this->tokenStorage->getToken()) {
            return false;
        }

        $roles = array_map(function (Role $role) {
            return $role->getRole();
        }, $token->getRoles());

        return count(array_intersect($this->roles[$name], $roles)) > 0;
    }
}
