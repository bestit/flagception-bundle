# bestit/feature-toggle-bundle

[![Total Downloads](https://poser.pugx.org/bestit/feature-toggle-bundle/downloads.png)](https://packagist.org/packages/bestit/feature-toggle-bundle)
[![Latest Stable Version](https://poser.pugx.org/bestit/feature-toggle-bundle/v/stable.png)](https://packagist.org/packages/ebestit/feature-toggle-bundle)

The feature toggle bundle provide a very simple way to enable or disable parts of your code.


Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require bestit/feature-toggle-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            \BestIt\FeatureToggleBundle\BestItFeatureToggleBundle(),
        );

        // ...
    }

    // ...
}
```

Step 3: Configure
-------------------------
You can define your features in your config files. Just give your feature a name and the active or inactive flag.
All here defined features are saved in the `ConfigStash`.

```yml
# config.yml

best_it_feature_toggle:

    # Your Features for ConfigStash (optional you left it empty)
    features:      
        
        # Feature name as key
        feature_123:
        
            # Flag if inactive or active (default: inactive)
            active: true
            
        feature_abc:
            active: false
        feature_456:
            active: false
        feature_789:
            active: true
    
    # CookieStash (optional)
    cookie_stash:           
        
        # Enable cookie stash (default: false)
        active: true       
                                     
        # Cookie name (default: best_it_feature_toggle)                             
        name: 'your_feature_toogle_name'

```

Step 4: Stashes
-------------------------
You do not need to set all features in your config.yml. You can use stash services which return active features. This bundle 
include two stashes:

__ConfigStash__:
Returns active features from your config.yml.

__CookieStash__: 
Returns active features from your current cookie. Example: If you set a cookie with the name `your_feature_toogle_name` and 
the value `feature_abc|feature_456`, both features will be active - even if the features are inactive the config.


You can add your own stash. Just implement the `StashInterface` and add the tag `best_it_feature_toggle.stash`. 
You can add the "priority" attribute to control which stash should be asked first (high to low). Example:

```yml
# services.yml

    best_it_feature_toggle.stash.config_stash:
        class: BestIt\FeatureToggleBundle\Stash\ConfigStash
        arguments:
            - '@best_it_feature_toggle.bag.feature_bag'
        tags:
            - { name: best_it_feature_toggle.stash, priority: 100 }
```

Step 5: How to use
-------------------------
Now you can control in twig, you services or in your controllers if a feature should be displayed or not.

#### Controller usage
```php
# FooController.php

use BestIt\FeatureToggleBundle\Annotations\Feature;

/**
 * @Feature("feature_123")
 */
class FooController
{

    /**
     * @Feature("feature_789")
     */
    public function barAction()
    {
    }

    public function fooAction()
    {
    }
}
```

#### Twig usage
```twig
{% if feature('feature_123') %}
    {# ... #}
{% endif %}
```
or
```twig
{% if 'feature_123' is active feature %}
    {# ... #}
{% endif %}
```

#### Service usage
```php
# FooService.php

class FooService
{
    /**
     * @var FeatureManagerInterface
     */
    private $manager;

    /**
     * @param FeatureManagerInterface $manager
     */
    public function __construct(FeatureManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    
    public function do()
    {
        // ...
        if ($this->manager->isActive('feature_123')) {
            // ...
        }
        // ...
    }
}
```
