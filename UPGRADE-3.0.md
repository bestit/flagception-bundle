# Upgrade from 2.x to 3.0
The new feature toggle bundle is a complete rework but the public interfaces doesn't change so much.
It use the new [Flagception](https://packagist.org/packages/flagception/flagception) library under the hood.

Generally, the namespace changed to `Flagception\` or `Flagception\Bundle\FlagceptionBundle`.
So all namespaces and service id's are renamed. 

Additional, the composer package renamed from `bestit/feature-toggle-bundle` to `flagception/flagception-bundle`.

FeatureManager
---------------------------
The feature manager interface renamed from `BestIt\FeatureToggleBundle\Manager\FeatureManagerInterface` to `Flagception\Manager\FeatureManagerInterface`.
The default feature manager (`Flagception\Manager\FeatureManager`) is accessible via the service id `flagception.manager.feature_manager`.
Instead of bags it expects a `FeatureActivatorInterface` and a optional `ContextDecoratorInterface` as constructor argument.

Stashes (Activators)
---------------------------
The stashes are renamed to "activators" and implement the `Flagception\Activator\FeatureActivatorInterface` instead of the
`BestIt\FeatureToggleBundle\Stash\StashInterface`. 

The compiler pass tag also changed from `best_it_feature_toggle.stash` to `flagception.activator`. The stash bag was removed - a `ChainActivator` holds
all activator (stashes) now.

Decorators
---------------------------
Decorators implement the renamed `Flagception\Decorator\ContextDecoratorInterface` now (old: `BestIt\FeatureToggleBundle\Decorator\ContextDecoratorInterface`). 
The compiler pass tag changed from `best_it_feature_toggle.context_decorator` to `flagception.context_decorator`.
The decorator bag was removed - a `ChainDecorator` holds all decorators now.

Events
---------------------------
All events are removed. 

Config
---------------------------
A few fields have been renamed.

For example - the old config:
```yml
best_it_feature_toggle:
    features:      
        feature_123:
            active: true
```

The new config (`active` to `default`):
```yml
flagception:
    features:
        feature_123:
            default: true
```

I think every single point listed here will be more confusing than helping. Just have a look at the new and detailed [readme](README.md).
