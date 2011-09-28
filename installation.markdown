---
layout: base
title: Installation
---

# How to install

You have to way to method to install the project :

## With the AdmingeneratorIpsum project ##

The [AdmingeneratorIpsum](https://github.com/cedriclombardot/AdmingeneratorIpsum) is a symfony2-standard wich have dependencies for all the admingenerators for each ORM & ODMs.

This is the installation recommanded if you want to try the bundle in 2 minutes 

{% highlight bash %}
> git clone git://github.com/cedriclombardot/AdmingeneratorIpsum.git
> cd AdmingeneratorIpsum
> ./bin/vendors install
> ./rebuild.sh
{% endhighlight %}

## On a symfony2 project ##

{% highlight bash %}
git submodule add git://github.com/cedriclombardot/AdmingeneratorGeneratorBundle.git vendor/bundles/Admingenerator/GeneratorBundle
{% endhighlight %}

Register it in the `autoload.php` file:

{% highlight php %}
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    'Admingenerator'    => array(__DIR__.'/../src', __DIR__.'/../vendor/bundles'),
));
{% endhighlight %}

Add it to the `AppKernel` class:

{% highlight php %}
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
{% endhighlight %}

### Install Pagerfanta 

{% highlight bash %}
git submodule add http://github.com/whiteoctober/Pagerfanta.git vendor/pagerfanta
git submodule add http://github.com/whiteoctober/WhiteOctoberPagerfantaBundle.git vendor/bundles/WhiteOctober/PagerfantaBundle
{% endhighlight %}

Register it in the `autoload.php` file:

{% highlight php %}
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    'WhiteOctober\PagerfantaBundle' => __DIR__.'/../vendor/bundles',
    'Pagerfanta'                    => __DIR__.'/../vendor/pagerfanta/src',
));
{% endhighlight %}

Add it to the `AppKernel` class:

{% highlight php %}
$bundles[] = new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
{% endhighlight %} 

### Install KnpMenuBundle 

{% highlight bash %}
git submodule add https://github.com/knplabs/KnpMenuBundle.git vendor/bundles/Knp/Bundle/MenuBundle
git submodule add https://github.com/knplabs/KnpMenu.git vendor/KnpMenu
{% endhighlight %}

Register it in the `autoload.php` file:

{% highlight php %}
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    'Knp'       => __DIR__.'/../vendor/bundles',
    'Knp\Menu'  => __DIR__.'/../vendor/KnpMenu/src'
));
{% endhighlight %}

Add it to the `AppKernel` class:

{% highlight php %}
$bundles[] = new Knp\Bundle\MenuBundle\KnpMenuBundle();
{% endhighlight %} 

Don't forget to configure it to use twig in `config.yml`:

{% highlight yaml %}
knp_menu:
    twig: true
{% endhighlight %}

### Install SensioGeneratorBundle

{% highlight bash %}
git submodule add git://github.com/sensio/SensioGeneratorBundle.git vendor/bundles/Sensio/Bundle/GeneratorBundle
{% endhighlight %}

Register it in the `autoload.php` file:

{% highlight php %}
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    'Sensio\Bundle'     => __DIR__.'/../vendor/bundles',
));
{% endhighlight %}

Add it to the `AppKernel` class:

{% highlight php %}
$bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle(),
{% endhighlight %}

### Setup the Model Manager you want

At this step, you'll have to install the Model Manager you want (Doctrine ORM, Doctrine ODM and/or Propel).
E.g. with Doctrine, you'll have to setup the Doctrine2FixtureBundle bundle.

#### Install Doctrine2FixtureBundle & Create the database

{% highlight bash %}
php app/console doctrine:database:create
php app/console doctrine:schema:create
php app/console doctrine:fixtures:load  
{% endhighlight %}

### Install Assetic (Optionnal see without assetic part)

{% highlight bash %}
git submodule add git://github.com/symfony/AsseticBundle.git vendor/bundles/Symfony/Bundle/AsseticBundle
git submodule add git://github.com/kriswallsmith/assetic.git vendor/assetic
{% endhighlight %}

Register it in the `autoload.php` file:

{% highlight php %}
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    'Assetic'           => __DIR__.'/../vendor/assetic/src',
));
{% endhighlight %}

Add it to the `AppKernel` class:

{% highlight php %}
$bundles[] = new Symfony\Bundle\AsseticBundle\AsseticBundle(),
{% endhighlight %}

Configure the routing in `app/config/routing.yml`:

{% highlight yaml %}
_assetic:
    resource: .
    type: assetic
{% endhighlight %}

To run assets you also need to install `sass` & `compass`:

{% highlight bash %}
sudo gem install compass # https://github.com/chriseppstein/compass
sudo gem install sass
{% endhighlight %}

Configure Assetic:

{% highlight yaml %}
assetic:
    filters:
        cssrewrite: ~
        sass: 
            bin: /var/lib/gems/1.8/gems/sass-3.1.7/bin/sass
            compass: /var/lib/gems/1.8/gems/compass-0.11.5/bin/compass
{% endhighlight %}


### Without Assetic

Configure your config.yml to use the assetic less template

{% highlight yaml %}
admingenerator_generator:
    base_admin_template: AdmingeneratorGeneratorBundle::base_admin_assetic_less.html.twig
{% endhighlight %}

### With or without assetic 

Publish assets:

{% highlight bash %}
php app/console assets:install web/
{% endhighlight %}

### Last step

Configure the dev environment:

{% highlight yaml %}
admingenerator_generator:
    overwrite_if_exists: true
{% endhighlight %}
