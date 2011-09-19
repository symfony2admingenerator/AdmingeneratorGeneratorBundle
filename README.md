# Symfony2 Admin Generator: The Real Missing Admin Generator for Symfony2 !

This package is a Symfony2 Admin Generator based on YAML configuration and Twig templating.
It's inspired by [fzaninotto/Propel2](https://github.com/fzaninotto/Propel2).

It actually supports:

* Doctrine ORM
* Doctine ODM
* [Propel](http://github.com/propelorm/Propel)

With almost the same features: 

* A YAML to configure create/edit/delete/list actions
* Manage relations one to one, one to many, many to one and **many to many**
* Easy edit of forms by changing properties of fields in YAML. 
* Configure fieldsets
* Configure more that one field per line
* Change the database column you want to sortOn or filterOn for a field in list
* A complete admin design
* Translated into EN, FR (you can easily contribute to add your own)
* ...

## This bundle in pictures

![Preview of list](https://github.com/cedriclombardot/AdmingeneratorGeneratorBundle/raw/master/Resources/doc/list-preview.png)

![Preview of edit](https://github.com/cedriclombardot/AdmingeneratorGeneratorBundle/raw/master/Resources/doc/edit-preview.png)

## Want to run a test ?

The fastest way to try it is to setup the AdmingeneratorIpsum project: https://github.com/cedriclombardot/AdmingeneratorIpsum.
This is a complete Symfony2 application with this bundle well configured.

## Installation

### Install this bundle

``` bash
git clone git://github.com/cedriclombardot/AdmingeneratorGeneratorBundle.git vendor/bundles/Admingenerator/GeneratorBundle
```

Register it in the `autoload.php` file:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
	'Admingenerator'    => array(__DIR__.'/../src', __DIR__.'/../vendor/bundles'),
));
```

Add it to the `AppKernel` class:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
	$bundles = array(
		// ...
		
		// Admin Generator
		new Admingenerator\GeneratorBundle\AdmingeneratorGeneratorBundle(),
	);
	
	// ...
}
```

### Install Pagerfanta 

``` bash 
git submodule add http://github.com/whiteoctober/Pagerfanta.git vendor/pagerfanta
git submodule add http://github.com/whiteoctober/WhiteOctoberPagerfantaBundle.git vendor/bundles/WhiteOctober/PagerfantaBundle
```

Register it in the `autoload.php` file:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    'WhiteOctober\PagerfantaBundle' => __DIR__.'/../vendor/bundles',
    'Pagerfanta' 					=> __DIR__.'/../vendor/pagerfanta/src',
));
```

Add it to the `AppKernel` class:

``` php
$bundles[] = new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
```   

### Install KnpMenuBundle 

``` bash
git submodule add https://github.com/knplabs/KnpMenuBundle.git vendor/bundles/Knp/Bundle/MenuBundle
git submodule add https://github.com/knplabs/KnpMenu.git vendor/KnpMenu
```

Register it in the `autoload.php` file:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    'Knp'		=> __DIR__.'/../vendor/bundles',
    'Knp\Menu' 	=> __DIR__.'/../vendor/KnpMenu/src'
));
```

Add it to the `AppKernel` class:

``` php
$bundles[] = new Knp\Bundle\MenuBundle\KnpMenuBundle();
```   

Don't forget to configure it to use twig in `config.yml`:

``` yml
knp_menu:
    twig: true
``` 

### Install SensioGeneratorBundle

``` bash
git submodule add git://github.com/sensio/SensioGeneratorBundle.git vendor/bundles/Sensio/Bundle/GeneratorBundle
```

Register it in the `autoload.php` file:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
	'Sensio\Bundle'     => __DIR__.'/../vendor/bundles',
));
```

Add it to the `AppKernel` class:

``` php
$bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle(),
```

### Setup the Model Manager you want

At this step, you'll have to install the Model Manager you want (Doctrine ORM, Doctrine ODM and/or Propel).
E.g. with Doctrine, you'll have to setup the Doctrine2FixtureBundle bundle.

#### Install Doctrine2FixtureBundle & Create the database

``` bash 
php app/console doctrine:database:create
php app/console doctrine:schema:create
php app/console doctrine:fixtures:load	
```

### Install assets

To run assets you need to install sass & compass:

``` bash
sudo gem install compass # https://github.com/chriseppstein/compass
sudo gem install sass
```

Publish assets:

``` bash
php app/console assets:install web/
```

Configure Assetic:

``` yaml
filters:
    cssrewrite: ~
    sass: 
        bin: /var/lib/gems/1.8/gems/sass-3.1.7/bin/sass
        compass: /var/lib/gems/1.8/gems/compass-0.11.5/bin/compass
```

### Last step

Configure the dev environment:

``` yaml
admingenerator_generator:
    overwrite_if_exists: true
```

--------------

Note : The admin theme is from https://github.com/martinrusev/admin-theme

