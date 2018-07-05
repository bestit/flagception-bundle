Constraint
-------------------------
You have to defining variables and functions if you want to use it in your constraint. 
You can define variables locally or globally. We recommend to define variables always globally.

We use the symfony [expression language](https://symfony.com/doc/current/components/expression_language.html) for
parsing the constraints.

##### Define locally variable
Just fill the second argument of your twig or service method with an array. Beware that the variable only exists
for this one feature request. 

Given we use following constraint:
```yml
# config.yml

flagception:
    features:      
    
        # This feature will only be active, if the current user has id 12
        feature_123:
            default: false
            constraint: 'user_id == 12'     
```

For defining the `user_id`, we just add this as second argument.

In twig:
```twig
{% if feature('feature_123', {'user_id': '12', 'user_role': 'ROLE_ADMIN'}) %}
    {# ... #}
{% endif %}
```

In a service:
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
         $context = new Context();
         $context->add('user_id', 12);
         $context->add('user_role', 'ROLE_ADMIN');
         
         if ($this->manager->isActive('feature_123', $context)) {
             // ...
         }
        // ...
    }
}
```

##### Define globally variable
For adding a global variable, just create a ContextDecorator class and implement `ContextDecoratorInterface`.
You have to create two methods. The `getName` method return the ContextDecorator name and the `decorate` method
will extend the context data with your variables. Remember to tag the service with `flagception.context_decorator`.

This bundle supports [autoconfiguration](https://symfony.com/blog/new-in-symfony-3-3-service-autoconfiguration) for `ContextDecoratorInterface` from Symfony 3.3.

As the feature manager may serializes context data in future (eg. for caching), 
you should not store objects that cannot be serialized (like PDO objects) or you need to provide your own serialize() method.

Example for adding the `user_id`:
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
        $context->add('user_id', $this->user->getId);
        $context->add('user_role', $this->user->getRole());
        
        return $context;
    }
}
```

##### Methods for constraints
We have some methods you can use for your constraint expression. But you can always create own methods if you like (see below).

| Method | Description | Example call |
|--------|----------------------------------------------|----------------------------------|
| date | Use php `date` function with current timestamp | `date("H") > 8 and date("H") < 18` |
| match | Use php `preg_match` with pattern and value. This method return true or false | `match("/foo/i", "FOO") == true` |
| ratio | Random true / false value based on your given ratio between 0 - 1 | `ratio(0.5) == true` |


##### Define own methods
All what you have to do is to [create a provider](http://symfony.com/doc/current/components/expression_language/extending.html)
for the expression language (like you already know from symfony).
Then tag your provider with the `flagception.expression_language_provider` and you can use your function in constraints.

Example provider for creating a format date:
```php
// DateProvider.php

class DateProvider implements ExpressionFunctionProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new ExpressionFunction(
                'date',
                function ($value) {
                    return sprintf('date(%1$s, time())', $value);
                },
                function ($arguments, $str) {
                    return date($str, time());
                }
            ],
        );
    }
}
```

Now you can use `date`:
```yml
flagception:
    features:
        feature_abc:
            default: false
            constraint: 'date("H") > 8 and date("H") < 18'
```
