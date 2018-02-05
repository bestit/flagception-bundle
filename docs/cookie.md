Cookies
-------------------------
You can test your features with cookies. This is by default
disabled - so you have to enabled it in your config. You can also set a cookie name and a separator.

```yml
# config.yml

flagception:
    features:      
        feature_123:
            default: false
   
    activators:
        # Cookie settings         
        cookie:
            
            # Enable cookie activator (default: false)
            enable: true
            
            # Cookie name - should be a secret key (default: 'flagception')
            name: 'flagception'
            
            # Cookie value separator for using with mutiple features (default: ',')
            separator: ','
```

Now you can set a cookie (eg. in chrome, firefox etc) and set the feature names (with separator) as value:

![Image of Chrome cookies](images/cookie.png)

No matter what is set in the config - if the feature name exists in the cookie, the feature is enabled for you.
With one exception: If a feature should never be enabled via cookie, you can deactivate it:

```yml
# config.yml

flagception:
    features:      
    
        # Activatable via cookie
        feature_123:
            default: false
            
        # Not activatable via cookie
        feature_456:
            cookie: false
   
    # Cookie settings         
    cookie:
        
        # Enable cookie activator (default: false)
        enable: true
        
        # Cookie name - should be a secret key (default: 'flagception')
        name: 'flagception'
        
        # Cookie value separator for using with mutiple features (default: ',')
        separator: ','
```
