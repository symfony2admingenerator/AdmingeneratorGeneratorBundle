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
git submodule add git://github.com/cedriclombardot/AdmingeneratorGeneratorBundle.git vendor/bundles/AdmingeneratorGeneratorBundle
{% endhighlight %}

### Dependencies ###

{% highlight shell %}
git submodule add http://github.com/whiteoctober/Pagerfanta.git vendor/pagerfanta
git submodule add http://github.com/whiteoctober/WhiteOctoberPagerfantaBundle.git vendor/bundles/WhiteOctober/PagerfantaBundle
git submodule add https://github.com/knplabs/KnpMenuBundle.git vendor/bundles/Knp/Bundle/MenuBundle
{% endhighlight %}

### Configure autoload ###

In app/autoload.php

{% highlight php %}
$loader->registerNamespaces(array(
    //Pager
    'WhiteOctober\PagerfantaBundle' => __DIR__.'/../vendor/bundles',
    'Pagerfanta' => __DIR__.'/../vendor/pagerfanta/src',
    
    //Menu
    'Knp'                       => __DIR__.'/../vendor/bundles',
    
    //Admingenerator
    'Admingenerator'        => array(__DIR__.'/../src', __DIR__.'/../vendor/bundles'),
    
));

{% endhighlight %}

### Configure AppKernel ###

In app/AppKernel.php

{% highlight php %}
public function registerBundles()
    {
        $bundles = array(
            new Admingenerator\GeneratorBundle\AdmingeneratorGeneratorBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        );
.....
    
));

{% endhighlight %}

### Configure dev environnment ###

In app/config/config_dev.yml

{% highlight yaml %}
admingenerator_generator:
    overwrite_if_exists: true
{% endhighlight %}

