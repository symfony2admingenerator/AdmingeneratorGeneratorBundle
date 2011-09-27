---
layout: base
title: "Documentation for AdmigeneratorGeneratorBundle"
---

# Welcome to the AdmigeneratorGeneratorBundle documentation #

### What? ###

The AdmingeneratorGeneratorBundle is an opensource admin-generator for the famous [Symfony2](http://www.symfony.com/) php framework. 

The term "generator" is used on purpose: the bundle actually generates PHP code in the cache directory, so you can easily read it, understand how it works, and overwrite it.

### How does it work? ###

The bundle generates an entire administration based on a simple YAML configuration file. Here is an example generator configuration:

{% highlight yaml %}
generator: admingenerator.generator.doctrine
params:
  model: Admingenerator\DemoBundle\Entity\Movie
  namespace_prefix: Admingenerator
  bundle_name: DemoBundle
  fields: 
    is_published:
      help: If you want to see this content on you website
    actors:
      filterOn: actors.id
    producer:
      label: Producer name
      sort_on: producer.name
      getter: producer.name
      addFormOptions:
        property: name
    release_date:
      formType: birthday 
      addFormOptions:
        years: 
          .range: 
            from: <?php echo date("Y"); ?>

            to: 1950
            step: 1

builders:
  list:
    params:
      title: Here is a beautifull title no  ???
      display: [ id, title, is_published, producer, release_date ]
      max_per_page: 3
      actions:
        new: ~ 
      object_actions:
        edit: ~ 
        delete: ~
  filters: 
    params:
      fields:
        release_date:
          formType: date_range
      display: [ title, is_published, producer, actors, release_date]

  new: 
    params:
      title: You're creating a new movie
      display: [ title, is_published, producer, actors, release_date ]
      actions:
        list: ~
  edit: 
    params:
      title: You're editing the movie "{{ Movie.title }}"
      display: 
        "NONE": [ title, release_date ]
        "Other informations": [ is_published, producer, actors ]
      actions:
        list: ~
  delete: ~
{% endhighlight %}


The YAML configuration allows to customize the form classes, the controllers, and to generate a specific template, of course linked to your model and your ORM or ODM.

### In pictures

![Preview of list](https://github.com/cedriclombardot/AdmingeneratorGeneratorBundle/raw/master/Resources/doc/list-preview.png)

![Preview of edit](https://github.com/cedriclombardot/AdmingeneratorGeneratorBundle/raw/master/Resources/doc/edit-preview.png)