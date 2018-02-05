Route
-------------------------
You can use route attributes for checking the feature state in controllers. This is per default activated.
You can enable or disable it via the config.

```yml
# config.yml

flagception:
    features:      
        feature_123:
            default: true
        
    # Use route attributes? (optional)
    routing_metadata:
    
        # Enable controller annotation (default: true)
        enable: true
```

If route metadata is enabled, you can define the feature name in your route attributes.
A `NotFoundHttpException` will be thrown if you request an action or class with inactive feature flag.

```php
// src/AppBundle/Controller/BlogController.php
// src/Controller/BlogController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BlogController extends Controller
{
    /**
     * @Route("/blog/{page}", defaults={"_feature": "feature_123"})
     */
    public function listAction($page)
    {
        // ...
    }

    /**
     * @Route("/blog/{slug}")
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

# Symfony 3.4 / 4.0
blog_list:
    path:       /blog/{page}
    controller: AppBundle:Blog:list
    defaults:   { _feature: 'feature_789' }  
    
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

    <!-- Symfony 3.4 / 4.0 -->
    <route id="blog_list" path="/blog/{page}">
        <controller>AppBundle:Blog:list</controller>
        <default key="_feature">feature_123</requirement>
    </route>
    
    <!-- ... -->
</routes>
```
