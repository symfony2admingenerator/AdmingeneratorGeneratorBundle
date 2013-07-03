# Menu
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#8-cookbook

### 1. Configuration

First of all, enable translation of KnpMenu - add the following lines to `app/config/config.yml`:

```yaml
knp_menu:
    twig:
        template: AdmingeneratorGeneratorBundle:KnpMenu:knp_menu_trans.html.twig
```

This step introduces the following template features: 

* prepending menu items with icons
* appending caret to dropdown menu items
* translation of menu item labels

Change the file namespace so that it will reflect the current location of the file.

Your menu class extends AdmingeneratorMenuBuilder so you will also need to add the following line:

```php
use Admingenerator\GeneratorBundle\Menu\AdmingeneratorMenuBuilder;
```

### 2. Installation

By default Admingenerator base templates render the [default menu][default-builder].

This is done in `Resources\base_admin_navbar.html.twig` in **menu** block:

```html+django
{% block menu %}{{ knp_menu_render('AdmingeneratorGeneratorBundle:DefaultMenu:navbarMenu') }}{% endblock %}
```

#### Create new menu builder

To overwrite this, you need to [create][create-builder] a new menu builder class. To make things 
easier Admingenerator ships a [base][extend-builder] class which you can extend (see 
[default][default-builder] menu builder to an example).

[create-builder]: https://github.com/KnpLabs/KnpMenuBundle/blob/master/Resources/doc/index.md#method-a-the-easy-way-yay
[extend-builder]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Menu/AdmingeneratorMenuBuilder.php
[default-builder]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Menu/DefaultMenuBuilder.php

#### Overwrite menu block

When you have your builder class ready, simply overwrite the **menu** block to render your class:

```html+django
{% block menu %}{{ knp_menu_render('AcmeDemoBundle:MyBuilder:myMenu') }}{% endblock %}
```

> **Note**: `Resources\base_admin_navbar.html.twig` template is included by `base_admin` and 
`base_login` templates. To overwrite **menu** block simply create a new base template that
extends default admingenerator base template and in there customize your **menu** block.
Remember to change the `admingenerator_generator.base_admin_template` parameter to use
your custom base template!

### 3. Example

#### Example override in your custom base_admin template

```html+django
{% block navbar %}
{% embed 'AdmingeneratorGeneratorBundle::base_admin_navbar.html.twig' %}
    {% block menu %}{{ knp_menu_render('AcmeDemoBundle:MyBuilder:navbarMenu') }}{% endblock %}
{% endembed %}
{% endblock navbar %}

```

#### Example menu in builder class

```php
public function navbarMenu(FactoryInterface $factory, array $options)
{
    // create root item
    $menu = $factory->createItem('root');
    // set id for root item, and class for nice twitter bootstrap style
    $menu->setChildrenAttributes(array('id' => 'main_navigation', 'class' => 'nav'));

    // add links $menu
    $this->addLinkURI($menu, 'Item1', 'http://www.google.com');
    $this->addLinkRoute($menu, 'Item2', 'Your_RouteName');

    // add dropdown to $menu
    $dropdown = $this->addDropdown($menu, 'Item 3', true);

    // add header to $dropdown
    $this->addHeader($dropdown, 'Heading');

    // add links to $dropdown
    $this->addLinkRoute($dropdown, 'Subitem 3.1', 'Your_RouteName');
    $this->addLinkURI($dropdown, 'Subitem 3.2', 'http://www.google.com');
    
    // add divider to $dropdown
    $this->addDivider($dropdown);

    // add more links to $dropdown
    $this->addLinkRoute($dropdown, 'Subitem 3.3', 'Your_RouteName');

    return $menu;
}
```