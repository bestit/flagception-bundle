Contentful
-------------------------
You can manage your feature toggles via [Contentful](https://www.contentful.com). You only have to define a content type in
Contentful, initiate a [Client](https://packagist.org/packages/contentful/contentful) and map the fields.

This bundle will use the Flagception [Contenftul activator Library](https://packagist.org/packages/flagception/contentful-activator).

Download the Bundle
---------------------------
The activator for Contentful is not included by default. Therefore, you must first insert this via Composer as a dependency.

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require flagception/contentful-activator
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Enable the activator
-------------------------

Then, enable the activator in your config:

```yml
# config.yml

flagception:
    features:      
        feature_123:
            default: false
   
    activators:
        # Contentful settings         
        contentful:
            
            # Enable contentful activator (default: false)
            enable: true
            
            # The service id of your Contentful client id
            client_id: 'contentful.client'                                          # Required
            
            # The Contentful content type key (default: flagception)
            content_type: 'flagception'
            
            # The Contentful field names
            mapping:
            
                # Field name for feature name (default: name)
                name: 'name'
                
                # Field name for feature state (default: state)
                state: 'state'
```

If your Contentful model looks like ...

```json
{
  "name": "Feature Management",
  "description": "Features verwalten",
  "displayField": "featureName",
  "fields": [
    {
      "id": "featureName",
      "name": "Feature",
      "type": "Text",
      "localized": false,
      "required": true,
      "validations": [],
      "disabled": false,
      "omitted": false
    },
    {
      "id": "isActive",
      "name": "Aktiv",
      "type": "Boolean",
      "localized": false,
      "required": true,
      "validations": [],
      "disabled": false,
      "omitted": false
    }
  ],
  "sys": {
    "space": {
      "sys": {
        "type": "Link",
        "linkType": "Space",
        "id": "9d8smn39"
      }
    },
    "id": "myFeatureModel",
    "type": "ContentType",
    "createdAt": "2017-12-07T15:54:07.255Z",
    "updatedAt": "2018-01-11T16:08:47.283Z",
    //...
  }
}
```

... your config should look like:

```yml
# config.yml

flagception:
    features:      
        feature_123:
            default: false
   
    activators:     
        contentful:
            enable: true
            client_id: 'contentful.client'
            content_type: 'myFeatureModel'
            mapping:
                name: 'featureName'
                state: 'isActive'
```

Enable caching
-------------------------
This activator will request Contentful for every check. This is not advantageous due to the 
long HTTP communication. Therefore, you can set up a cache for this activator. Identical feature queries are 
then loaded from the cache instead of being retrieved from Contentful. All you have to do is specify a cache 
pool and cache interval.
                         
By default, the cache is disabled to avoid a BC.

Example:

```yml
# config.yml

flagception:
    features:      
        feature_123:
            default: false
   
    activators:     
        contentful:
            enable: true
            # ...
            cache:
            
                # Enable the cache option (default: false)
                enable: true
                
                # Set cache pool (default: cache.app)
                pool: cache.app
                
                # Set lifetime for cache in seconds (default: 3600)
                lifetime: 3600
```
