<?php

namespace BestIt\FeatureToggleBundle\Model;

use BestIt\FeatureToggleBundle\Exception\AlreadyDefinedException;

/**
 * Class Context
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Model
 */
class Context
{
    /**
     * Storage for all context values
     *
     * @var array
     */
    private $storage = [];

    /**
     * Add a context value. The key must be unique and cannot be replaced
     *
     * @param string $name
     * @param mixed $value
     *
     * @return void
     * @throws AlreadyDefinedException
     */
    public function add(string $name, $value)
    {
        if (array_key_exists($name, $this->storage)) {
            throw new AlreadyDefinedException();
        }

        $this->storage[$name] = $value;
    }

    /**
     * Replace a context value
     *
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function replace(string $name, $value)
    {
        $this->storage[$name] = $value;
    }

    /**
     * Get context value of given string or default value
     *
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return $this->storage[$name] ?? $default;
    }

    /**
     * Get all context values (key => value pairs)
     *
     * @return array
     */
    public function all(): array
    {
        return $this->storage;
    }

    /**
     * Has given context value
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->storage) && isset($this->storage[$name]);
    }
}
