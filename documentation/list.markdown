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