Quickstart
-------------------------
You can define your features in your config files. Just give your feature a name and the active or inactive flag.

Minimal example with two features:

```yml
flagception:

    # Your Features (optional you left it empty)
    features:
    
        # Feature name as key
        feature_123:
            # Default flag if inactive or active (default: false)
            default: true
            
        feature_abc:
            default: false
```

Now you can check the feature state in twig templates, controllers or services.

##### Twig usage
```twig
{% if feature('feature_123') %}
    {# Execute if feature is active ... #}
{% endif %}
```

##### Service usage
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
     * Service id: flagception.manager.feature_manager
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

##### Controller usage
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

##### Annotation usage
```php
# FooController.php

use Flagception\Bundle\FlagceptionBundle\Annotations\Feature;

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

If you request an action with inactive feature flag, you will get a `NotFoundHttpException`.

Take a look to the detail documentation for [Twig](twig.md), [Route](twig.md) or [Annotation](twig.md) usage.

Constraint usage
-------------------------
In some cases will you need more instead of a simple true / false. So you can define constraints to enable or disable a feature.
A constraint should return true or false.

An example:

```yml
# config.yml

flagception:
    features:      
    
        # This feature will only be active, if the current user has id 12
        feature_123:
            default: false
            constraint: 'user_id === 12'     
            
        # This feature will only be active, if the user_role array contains "ROLE_ADMIN"
        feature_abc:
            default: false
            constraint: '"ROLE_ADMIN" in user_role'   
                    
        # This feature will only be active between 8am and 6pm.
        # OR if the user_role array contains "ROLE_ADMIN"
        feature_abc:
            default: false
            constraint: '(date("H") > 8 and date("H") < 18) or "ROLE_ADMIN" in user_role'
```

You can extend constraints with your own variables and functions. Read the [constraint documentation](constraint.md) for more details.
