---
layout: documentation
title: How to configure the list controller ?
---

# How to configure the list controller ?

## Title

This parameter allow you to configure the title displayed on the page. 

{% highlight yaml %}
builders:
  list:
    params:
      title: List of all movies
{% endhighlight %}

>**Tip**<br />This field will be parsed by twig so you could use all the power of filters...

## Display

You can choose the columns to show in your list in your yaml

{% highlight yaml %}
builders:
  list:
    params:
      display: [ id, title, is_published, producer, release_date ]
{% endhighlight %}

The display parameter accept the name of the columns from your model.

You can also add columns from one to many relations, for example the producer of the movie.
In this case, we try to call `__toString` method else if you [parameter the field getter](documentation/fields.html#getter)

## Paginate

You can define the number of elements you want to display per page, the default value is 10.

{% highlight yaml %}
builders:
  list:
    params:
      max_per_page: 5
{% endhighlight %}

## Overwrite the rendering of a field

Edit the file : YourBundle/Resources/views/List/index.html

And for the column title overwrite the Twig Block named list_td_column_**title**

{% highlight django %}
{{ "{% extends_admingenerated "NamespaceYourBundle:List:index.html.twig" %}
{% block list_td_column_title %}
    <span style="font-weight:bold">{{ Movie.title }}</span>
{% endblock %}" }}
{% endhighlight %}

## Default sort

You can define the default sort options with the key sort :

{% highlight yaml %}
builders:
  list:
    params:
      sort: [ producer.name, DESC ]
{% endhighlight %}

With the first argument, the column to sort and the second one optionnal the sorting order ASC or DESC (default: ASC)

## Object actions

You can set actions for rows. This action will call the defined controller, giving the id of the rows

{% highlight yaml %}
builders:
  list:
    params:
      object_actions: 
        edit: ~
{% endhighlight %}

Will go to the edit link.

{% highlight yaml %}
builders:
  list:
    params:
      object_actions: 
        delete: 
          confirm: Are you sure to delete {{ Movie.title }} ?
{% endhighlight %}

Will go to edit if you click ok on the confirm box message, and like you can see in this sample you can use twig power in your confirm message

The default route for an object action is  **namespace_prefix**_**bundle_name**_**action_name**

You can change it passing the option route to your action :

{% highlight yaml %}
builders:
  list:
    params:
      object_actions: 
        delete: 
          route: my_custom_route
{% endhighlight %}


