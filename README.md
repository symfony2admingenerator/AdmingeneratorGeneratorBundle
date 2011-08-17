# Symfony2 Bundle

This package is a symfony2 admin generator based on YAML conf and twig template

This package is inspired from fzaninotto/Propel2


# Run a test

## Install the bundle in a Symfony2 project src/ dir

## Install Pagerfanta 

```shell 
    git submodule add http://github.com/whiteoctober/Pagerfanta.git vendor/pagerfanta
    git submodule add http://github.com/whiteoctober/WhiteOctoberPagerfantaBundle.git vendor/bundles/WhiteOctober/PagerfantaBundle
```

Register autoload

```php
    // app/autoload.php
    $loader->registerNamespaces(array(
        'WhiteOctober\PagerfantaBundle' => __DIR__.'/../vendor/bundles',
        'Pagerfanta' => __DIR__.'/../vendor/pagerfanta/src',
    ));
```

And load in AppKernel 

```php
   $bundles[] = new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
```   

## Install the Doctrine2FixtureBundle & create the db

```shell 
	php app/console doctrine:database:create
	php app/console doctrine:schema:create
	php app/console doctrine:fixtures:load	
```

## Configure the bundle

In AppKernel.php

```php
   $bundles[] = new Admingenerator\GeneratorBundle\AdmingeneratorGeneratorBundle();
   $bundles[] = new Admingenerator\DemoBundle\AdmingeneratorDemoBundle();
```

Add route :

```yaml
_admindemo:
     resource: "@AdmingeneratorDemoBundle/Controller/"
     type: admingenerator
     prefix: /admin-demo
```

Install assets :

To run assets you need to install sass & compass

```shell
   sudo gem install compass # https://github.com/chriseppstein/compass
   sudo gem install sass
```

Publish assets

```shell
    php app/console assets:install web/
```

Configure assetic :

```yaml
    filters:
        cssrewrite: ~
        sass: 
            bin: /var/lib/gems/1.8/gems/sass-3.1.7/bin/sass
            compass: /var/lib/gems/1.8/gems/compass-0.11.5/bin/compass
```
Configure the dev environment :


```yaml
    admingenerator_generator:
        overwrite_if_exists: true
```
          
And after you can edit the DemoBundle/Resources/config/generator.yml file to see changes

In your browser :

http://admingen.local/app_dev.php/admin-demo/

--------------

Note : The admin theme is from https://github.com/martinrusev/admin-theme

