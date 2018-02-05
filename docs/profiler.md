Profiler
-------------------------

Take a look at our new profiler tab:

![Image of Profiler](images/profiler.png)

You can see which feature was activated by which activator. In addition, you can see how many activators were asked until it came to a conclusion.
Here is a small listing which activator belongs to which config:

```yml
# config.yml

flagception:
    features:      
        feature_123:
        
            # The default property belongs to 'array'
            default: false
            
            # The constraint property belongs to 'constraint'
            constraint: 'user_id === 12'     
            
            # The env property belongs to 'environment'
            env: 'FEATURE_123'
            
            # The cookie property belongs to 'cookie'
            cookie: true    
            
        # Contentful fields are defined in Contentful and not in your config
        # The activator called "contentful"
```
