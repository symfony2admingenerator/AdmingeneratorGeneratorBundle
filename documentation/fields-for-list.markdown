---
layout: documentation
title: Fields configuration for list
---

# Fields configuration for list

This options can be setted at the general params level or at the list params level

## Label

The default label is the column name, but you can change it like that : 

{% highlight yaml %}
params:
  fields:
    params:
      producer:
        label: Producer name
{% endhighlight %}

## Sort

The db column to sort by default is the column in your model wich have the same name of your fields. 
But you could want to sort on another table eg for relations or for "virtual columns" (columns wich have only getter but no existance in your db)
You can set option sort_on or sortOn (option are camelized at reading time) 

{% highlight yaml %}
params:
  fields:
    params:
      producer:
        sort_on: producer.name
{% endhighlight %}

If your column contains "." the system understand to add a leftJoin on `producer` and to sort on the joined object on column named `name`

## Getter

The default getter is get*Field*, you can edit it with the `getter` option

{% highlight yaml %}
params:
  fields:
    params:
      producer:
        getter: MyOwn
{% endhighlight %}

Will make call `$movie->getMyOwn()` to render your `producer` column

And of course you can chain getter to make `$movie->getMyOwn()->getLabel()`

{% highlight yaml %}
params:
  fields:
    params:
      producer:
        getter: MyOwn.Label
{% endhighlight %}