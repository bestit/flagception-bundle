Environment variables
-------------------------
Maybe you want to set a feature flag by an environment variable. In Symfony 3.4 you can set and cast env variables
very simple. But in symfony versions before that, it's not that easy. Therefore, you can set in the config whether the 
active status is to be pulled from an environment variable.

Just give the variable name in the `env` parameter:

```yml
# config.yml

flagception:
    features:      
    
        # This feature check the env var 'FEATURE_NAME_FROM_ENV'
        # setenv('FEATURE_NAME_FROM_ENV=false')
        feature_123:
            env: FEATURE_NAME_FROM_ENV  
```

You can combine all parameter together. First the `default` value is checked.
If the value is false, the value from `env` is checked. If this also returns false, the constraints are checked.

```yml
# config.yml

flagception:
    features:      
        feature_123:
            default: false
            env: FEATURE_NAME_FROM_ENV  
            constraint: 'user_role == ROLE_ADMIN'
```

As alternative, you can use the `%env()%` syntax for the default field:

```yml
# config.yml

flagception:
    features:      
        feature_123:
            default: '%env(FEATURE_NAME_FROM_ENV)%'
```

