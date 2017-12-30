Annotation
-------------------------
You can use annotations for checking the feature state in controllers. Just active this in your config:

```yml
# config.yml

flagception:
    features:      
        feature_123:
            default: true
        
    # Use annotation? (optional)
    annotation:
    
        # Enable controller annotation (default: false)
        enable: true
```


We recommend to use the route attribute solution, because using annotations has performance issues.
A `NotFoundHttpException` will be thrown if you request an action or class with inactive feature flag.


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
