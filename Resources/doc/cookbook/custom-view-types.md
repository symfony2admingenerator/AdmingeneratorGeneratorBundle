# Custom View types
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#7-cookbook

Sometimes, you might want to change the way the list or show view of a specific entity field is rendered. This can be done with a few simple steps!

1. Create a twig html template with your custom view block. Your block needs to start with `column_` and a viewname of your choice. We use gender as example
```twig
#example custom_blocks.html.twig
{% block column_gender %}
  {% spaceless %}
    {% if(field_value == 'm') %}
      <i class="icon-male"></i>
    {% elseif(field_value == 'f') %}
      <i class="icon-female"></i>
    {% else %}
      <i class="icon-unknown"></i>
    {% endif %}
  {% endspaceless %}
{% endblock %}
```

2. Specify the location of the custom template file in the `generator.yml` by using the `custom_blocks` param in the general, edit or show builder
```yaml
#example for the show and list builder
params:
    custom_blocks: AcmeDemoBundle:Form:custom_blocks.html.twig
```
```yaml
#example for just the show builder
builder:
    show:
        params:
            custom_blocks: Acme:Form:custom_blocks.html.twig
```

3. In the generator.yml, add the option `customView: %viewname%` for the wanted field 
```yaml
# Example
params: 
    fields:
        gender:
             customView: gender # %viewname%
```

Now you can make your own templates which will be rendered only in the specified list/show views!