# bestit/feature-toggle-bundle

[![Total Downloads](https://poser.pugx.org/bestit/feature-toggle-bundle/downloads.png)](https://packagist.org/packages/bestit/feature-toggle-bundle)
[![Latest Stable Version](https://poser.pugx.org/bestit/feature-toggle-bundle/v/stable.png)](https://packagist.org/packages/bestit/feature-toggle-bundle)

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

            new BestIt\FeatureToggleBundle\BestItFeatureToggleBundle(),
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
        
            # Default flag if inactive or active (default: inactive)
            active: true
            
        feature_abc:
            active: false
            
            # Optional further constraints which should return true (see Step 5)
            constraints:
                    - '"ROLE_ADMIN" in user_role'
                    - 'user_id === 12'
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
        
        # Set feature separator (default: ,)
        separator: ';'
        
    # Use annotation? (optional)
    annotation:
    
        # Enable controller annotation (default: false)
        active: true

    # Use routing metadata? (optional)
    routing_metadata:
    
        # Enable routing metadata (default: false)
        active: true
```

Step 4: Stashes
-------------------------
You do not need to set all features in your config.yml. You can use stash services for checking if a feature is active or not. This bundle 
include two stashes:

__ConfigStash__:
Check the active state against your config.yml.

__CookieStash__: 
Check the active state against your cookie. Example: If you set a cookie with the name `your_feature_toogle_name` and 
the value `feature_abc,feature_456`, both features will be active - even if the features are inactive the config.
You can define the cookie name and separator in your config.yml.

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

Step 5: Context
-------------------------
Maybe you need more than a simple true/false. For example, if you want to use A / B Testing or admins should always see the feature. 
You can therefore specify an (optional) context object and specify any values there. In your stash, you can activate the feature 
depending on the given context values. You can set context values by the isActive method or globally via the ContextDecorator (see step 6 and 7).
In most cases, you should set context values globally (eg. the user group).

Example to use context object in stash:
```php
# AdminStash.php

class AdminStash implements StashInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function isActive(string $name, Context $context): bool
    {
        return in_array('ROLE_ADMIN', $context->get('user_roles'), true);
    }
}
```


#### Use context and constraints with ConfigStash
You can define custom constraints in your config yml which will be checked by the ConfigStash if the default value is false.
Example from above:

```yml
# config.yml

best_it_feature_toggle:
    features:      
        feature_abc:
            active: false
            constraints:
                    - '"ROLE_ADMIN" in user_role'
                    - 'user_id === 12'
                    
                    # Or optional with the context variable and a default value
                    # For exampe: the variable 'customer_number' may not exists (no globally value), then return 0
                    - 'context.get("customer_number", 0) === 12'
```

In this example, the default active state is false. So the ConfgStash execute and evaluate all given constraints until one return true.
It use the [standard symfony expression language](https://symfony.com/doc/current/components/expression_language/syntax.html). You can access the context values by the get method with an optional second default value.
The expression must return true - all other returns will be interpreted as false.

The expression above explained: If the user_role is 'ROLE_ADMIN' or the user_id is 12, then the feature is active - otherwise inactive. 

Step 6: How to use
-------------------------
Now you can control in twig, in your services or in your controllers if a feature should be displayed or not. 
The controller will throw a 404 error if you access an non active feature.

#### Controller usage (via route metadata - recommend)
Remember to activate this in your config. 

```php
// src/AppBundle/Controller/BlogController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BlogController extends Controller
{
    /**
     * @Route("/blog/{page}", name="blog_list", defaults={"_feature": "feature_123"})
     */
    public function listAction($page)
    {
        // ...
    }

    /**
     * @Route("/blog/{slug}", name="blog_show")
     */
    public function showAction($slug)
    {
        // ...
    }
}
```
or via yml

```yml
# app/config/routing.yml
blog_list:
    path:      /blog/{page}
    defaults:  { _controller: AppBundle:Blog:list, _feature: 'feature_789' }  

blog_show:
```

or via xml

```xml
<!-- app/config/routing.xml -->
<?xml version="1.0" encoding="UTF-8" ?>
<routes xmlns="http://symfony.com/schema/routing"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/routing
        http://symfony.com/schema/routing/routing-1.0.xsd">

    <route id="blog_list" path="/blog/{page}">
        <default key="_controller">AppBundle:Blog:list</default>
        <default key="_feature">feature_123</requirement>
    </route>

    <!-- ... -->
</routes>
```

#### Controller usage (with Annotation)
Remember to activate this in your config. This has an performance issue. Better use the route metadata above.

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
or with context
```twig
{% if feature('feature_123', {'role': 'ROLE_ADMIN'}) %}
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
    
    // Optional, you can add context values
    public function doAdmin()
    {
        // ...
        $context = new Context();
        $context->add('role', 'ROLE_ADMIN');
        
        if ($this->manager->isActive('feature_123', $context)) {
            // ...
        }
        // ...
    }
}
```

Step 7: ContextDecorator
-------------------------
Usually you will need the same context values again and again. Then it is better to set the values globally instead of every single request.
Just create a class, implement the `ContextDecoratorInterface` and tag the service with `best_it_feature_toggle.context_decorator`.
You can then expand or customize the context object as you like.

```php
# UserContextDecorator.php

class UserContextDecorator implements ContextDecoratorInterface
{
    private $user;
    
    public function __construct(User $user) 
    { 
        $this->user = $user; 
    }
    
    public function getName(): string
    {
       return 'user_context_decorator';
    }
    
    public function decorate(Context $context): Context
    {
        $context->add('user_is_admin', $this->user->isAdmin());
        
        return $context;
    }
}
```

Step 8: Events
-------------------------
The bundle provides two events if a feature is requested. One before and after the feature was searched for in the stashes.


Credits
-------------------------
Feature toggle profiler icon from https://github.com/ionic-team/ionicons