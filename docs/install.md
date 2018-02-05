Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require flagception/flagception-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Enable the Bundle (Symfony 2.x / 3.x)
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

            new Flagception\Bundle\FlagceptionBundle\FlagceptionBundle(),
        );

        // ...
    }

    // ...
}
```

Enable the Bundle (Symfony 4.x)
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

<?php

return [
    // ...
    Flagception\Bundle\FlagceptionBundle\FlagceptionBundle::class => ['all' => true],
];
```

Use the bundle
-------------------------
That's all. You can now [use feature flags](usage.md).
