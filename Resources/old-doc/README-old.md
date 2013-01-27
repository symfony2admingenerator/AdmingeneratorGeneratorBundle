<a href="http://knpbundles.com/symfony2admingenerator/AdmingeneratorGeneratorBundle"><img src="http://knpbundles.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/badge"></a>

# Symfony2 Admin Generator: The Real Missing Admin Generator for Symfony2! 

![project status](http://stillmaintained.com/cedriclombardot/AdmingeneratorGeneratorBundle.png) ![build status](https://secure.travis-ci.org/symfony2admingenerator/AdmingeneratorGeneratorBundle.png)

**:warning: This branch work only with the symfony 2.1 for 2.0 use the 2.0 branch :warning:**

This package is a Symfony2 Admin Generator based on YAML configuration and Twig templating.
It's inspired by [fzaninotto/Doctrine2ActiveRecord](https://github.com/fzaninotto/Doctrine2ActiveRecord).

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
* Translated into EN, FR, RU, PT, ES, SL, DE , PL, UA, IT, JA (you can easily contribute to add your own)
* Filters by form & by scopes combinable
* Credentials for actions, columns, and form fields
* ...


## This bundle in pictures

![Preview of list](https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/raw/master/Resources/doc/doc_list.png)

![Preview of edit](https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/raw/master/Resources/doc/doc_edit.png)

## Want to run a test?

The fastest way to try it is to setup the AdmingeneratorIpsum project: https://github.com/symfony2admingenerator/AdmingeneratorIpsum.
This is a complete Symfony2 application with this bundle well configured.

## Installation

### Install this bundle

Using composer

```
"require": {
    "cedriclombardot/admingenerator-generator-bundle": "dev-master"
},
```

php composer.phar update

Or cloning

``` bash
git clone git://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle.git vendor/bundles/Admingenerator/GeneratorBundle
```

Or using deps file

```
[AdmingeneratorGeneratorBundle]
    git=git://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle.git
    target=/bundles/Admingenerator/GeneratorBundle
    version=origin/master
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
In config.yml

``` yaml
admingenerator_generator:
    # choose only one
    use_propel:           false
    use_doctrine_orm:     true
    use_doctrine_odm:     false
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

### Install KnpMenuBundle

``` bash
git submodule add https://github.com/knplabs/KnpMenuBundle.git vendor/bundles/Knp/Bundle/MenuBundle
git submodule add https://github.com/knplabs/KnpMenu.git vendor/KnpMenu
```

or using deps file

```
[MenuBundle]
    git=git://github.com/KnpLabs/KnpMenuBundle.git
    target=/bundles/Knp/Bundle/MenuBundle

[KnpMenu]
    git=git://github.com/KnpLabs/KnpMenu.git
    target=/KnpMenu
```

Register it in the `autoload.php` file:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    'Knp'    	=> __DIR__.'/../vendor/bundles',
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

### Now two ways to continue the setup:

Manually, follow the end of readme, or automatically,

``` bash
php app/console admin:setup
```

### Install TwigGenerator

``` bash
git submodule add http://github.com/cedriclombardot/TwigGenerator.git vendor/twig-generator
```

Register it in the `autoload.php` file:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    'TwigGenerator'         => __DIR__.'/../vendor/twig-generator/src',
));
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

### Configure JMS

In config.yml

``` yaml
jms_security_extra:
     expressions: true
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

### Install Assetic (Optionnal see without assetic part)

``` bash
git submodule add git://github.com/symfony/AsseticBundle.git vendor/bundles/Symfony/Bundle/AsseticBundle
git submodule add git://github.com/kriswallsmith/assetic.git vendor/assetic

```

Register it in the `autoload.php` file:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    'Assetic'           => __DIR__.'/../vendor/assetic/src',
));
```

Add it to the `AppKernel` class:

``` php
$bundles[] = new Symfony\Bundle\AsseticBundle\AsseticBundle(),
```

Configure the routing in `app/config/routing.yml`:

``` yaml
_assetic:
    resource: .
    type: assetic
```

To run assets you also need to install `sass` & `compass`:

``` bash
sudo gem install compass # https://github.com/chriseppstein/compass
sudo gem install sass
```

Configure Assetic:

``` yaml
assetic:
    filters:
        cssrewrite: ~
        sass:
            bin: /var/lib/gems/1.8/gems/sass-3.1.7/bin/sass
            compass: /var/lib/gems/1.8/gems/compass-0.11.5/bin/compass
```


### Without Assetic

Configure your config.yml to use the assetic less template

``` yaml
admingenerator_generator:
    base_admin_template: AdmingeneratorOldThemeBundle::base_admin_assetic_less.html.twig
admingenerator_user:
    login_template: AdmingeneratorOldThemeBundle::base_login_assetic_less.html.twig
```

### With or without assetic

Publish assets:

``` bash
php app/console assets:install web/
```

### Last step for prod:

```
php app/console -env prod cache:warmup
```

### Running tests

Bundle use phpunit framework for testing.
To running test suite please first install needed deps.

Using composer:

```
curl -s http://getcomposer.org/installer | php
php composer.phar install --dev
```

then you can run tests by `phpunit` command

## Need support?

https://groups.google.com/group/symfony2admingenerator

## Sensio Connect

https://connect.sensiolabs.com/club/symfony2admingenerator

--------------

Note: The admin theme is from https://github.com/martinrusev/admin-theme

