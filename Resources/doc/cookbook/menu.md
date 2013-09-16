# Menu
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#7-cookbook

### 1. Configuration

By default, Admingenerator sets the `knp_menu.twig.template` parameter to
`AdmingeneratorGeneratorBundle:KnpMenu:knp_menu_trans.html.twig`.

This template enables:

* prepending menu items with icons
* appending caret to dropdown menu items
* translation of menu item labels

If you change the template to a custom one, you will have to copy some lines
from `AdmingeneratorGeneratorBundle:KnpMenu:knp_menu_trans.html.twig` to have 
these features.

### 2. Installation

By default Admingenerator base templates render the [default menu][default-builder].

This is done in `Resources\base_admin_navbar.html.twig` in **menu** block:

```html+django
{% block menu %}{{ knp_menu_render('AdmingeneratorGeneratorBundle:DefaultMenu:navbarMenu') }}{% endblock %}
```

#### Create new menu builder

To overwrite this, you need to [create][create-builder] a new menu builder class. 
To make things easier Admingenerator ships a [base][extend-builder] class which 
you can extend (see [default][default-builder] menu builder to an example).

> **Note**: In case of extending the default builder, your own menu builder has to be defined as a service (see 
[Creating Menus as Services][create-service-builder]).

[create-builder]: https://github.com/KnpLabs/KnpMenuBundle/blob/master/Resources/doc/index.md#method-a-the-easy-way-yay
[create-service-builder]: https://github.com/KnpLabs/KnpMenuBundle/blob/master/Resources/doc/menu_service.md
[extend-builder]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Menu/AdmingeneratorMenuBuilder.php
[default-builder]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Menu/DefaultMenuBuilder.php

#### Overwrite menu block

When you have your builder class ready, simply overwrite the **menu** block to render your class:

```html+django
{% block menu %}{{ knp_menu_render('AcmeDemoBundle:MyBuilder:myMenu') }}{% endblock %}
```

> **Note**: `Resources\base_admin_navbar.html.twig` template is included by `base_admin` 
template. To overwrite **menu** block simply create a new base template that extends 
default admingenerator base template and in there customize your **menu** block.
Remember to change the `admingenerator_generator.base_admin_template` parameter to use
your custom base template!

### 3. Example

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
