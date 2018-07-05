Activators
-------------------------
We use 'activators' for resolve the feature state. This bundle contains the `ConfigActivator` which fetch
your features from the config.yml. But sometimes you want to fetch the feature state from another source. eg. from a remote server. You can create your own
activators with a few lines.

Just create a service class, implements `FeatureActivatorInterface`, tag it with `flagception.activator` and an optional
priority tag. The feature manager iterate through all activators and check the state with the `isActive` method until one activator 
returns true. If an activator returns true, no further activators will be requested.

This bundle supports [autoconfiguration](https://symfony.com/blog/new-in-symfony-3-3-service-autoconfiguration) for `FeatureActivatorInterface` from Symfony 3.3.

Example class to activate all features for admins:
```php
# AdminActivator.php

class AdminActivator implements FeatureActivatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        # Return an unqiue name for this activator
        return 'admin';
    }

    /**
     * @var string $name The requested feature name (eg. 'feature_123')
     * @var Context $context The context object which all key / values
     */
    public function isActive($name, Context $context)
    {
        # Always return true if the user role contain 'ROLE_ADMIN'
        return in_array('ROLE_ADMIN', $context->get('user_roles'), true);
    }
}
```

The service declaration:
```yml
flagception.activator.config_activator:
    class: Flagception\Bundle\FlagceptionBundle\Activator\ConfigActivator
    arguments:
        - '@flagception.constraint.constraint_resolver'
    tags:
        - { name: flagception.activator, priority: 100 }
```

Now we declare one feature in our config (ConfigActivator) and disabled it. The manager will check the ConfigActivator 
which return false (see config.yml). After that, the manager will call the AdminActivator - which return true for admins.
```yml
# config.yml

flagception:
    features:      
        feature_123:
            default: false 
```
