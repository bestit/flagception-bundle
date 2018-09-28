Roles
-------------------------
You can use [Symfony user roles](https://symfony.com/doc/current/security.html) to enable a feature for one or more roles.
To prevent a breaking change, this activator is currently disabled by default. Please note
that `symfony/security-bundle` must be installed if you want to use it.

```yml
# config.yml

flagception:
    features:      
        # Only one role
        feature_123:
            roles: ROLE_ADMIN
            
        # Multiple roles
        feature_456:
            roles: ROLE_ADMIN,ROLE_MOD
        
        # Multiple roles (alternative formatting)
        feature_789:
            roles:
                - ROLE_ADMIN
                - ROLE_MOD
                
     # Role settings         
     role:
         
         # Enable role activator (default: false)
         enable: true
         
         # Priority of this activator (default: 250)
         priority: 250
```
