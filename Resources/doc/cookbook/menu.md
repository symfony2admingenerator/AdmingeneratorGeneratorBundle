# Menu
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#7-cookbook

### 1. Configuration

First of all, enable translation of KnpMenu - add the following lines to `app/config/config.yml`:

```yaml
knp_menu:
    twig:
        template: AdmingeneratorGeneratorBundle:KnpMenu:knp_menu_trans.html.twig
```

This step is necessary as the template enables:

* prepending menu items with icons
* appending caret to dropdown menu items
* translation of menu item labels

Then change file namespace so that it will reflect the current location of the file. Your menu class extends AdmingeneratorMenuBuilder so you need to add the following line to your file:

```php
use Admingenerator\GeneratorBundle\Menu\AdmingeneratorMenuBuilder;
```

### 2. Installation

In `/vendor/cedriclombardot/admingenerator-generator-bundle/Admingenerator/GeneratorBundle/Menu` you can find  **DefaultMenuBuilder.php** . You must copy this file in your bundle and in your `config.yml` :
```yaml
admingenerator_generator:
  knp_menu_class: Acme\YourBundleName\Menu\DefaultMenuBuilder
```

### 3. Header menu

In method `createAdminMenu` you can configure your header menu.

If you want to add an item to menu you just need make:

```php
$menu->addChild('News', array('route' => 'Your_RouteName'));
```

If you want to create a dropdown menu you just need make:

```php
$dropdownMenu = $this->addDropdownMenu($menu, 'Menu name',true);
$dropdownMenu->addChild('Item1', array('route' => 'Your_RouteName'));
$dropdownMenu->addChild('Item2', array('route' => 'Your_RouteName'));
```
You can also add a divider between items in the dropdown menu:

```php
$dropdownMenu = $this->addDropdownMenu($menu, 'Menu name',true);

$dropdownMenu->addChild('Item1', array('route' => 'Your_RouteName'));
$this->addDivider($dropdownMenu);
$dropdownMenu->addChild('Item2', array('route' => 'Your_RouteName'));
```

**Full configuration**

```php
public function createAdminMenu(Request $request)
{
    $menu = parent::createAdminMenu($request);

    $menu->addChild('News', array('route' => 'Your_RouteName'));
    $menu->addChild('Article', array('route' => 'Your_RouteName'));

    $dropdownMenu = $this->addDropdownMenu($menu, 'Menu name',true);
    $dropdownMenu->addChild('Item1', array('route' => 'Your_RouteName'));
    $this->addDivider($dropdownMenu);
    $dropdownMenu->addChild('Item2', array('route' => 'Your_RouteName'));

    return $menu;
}
```

### 4. Dashboard sidebar menu

There is no longer a default dashboard provided in the bundle. However, it's possible to create your own dashboard and connect it to the dashboard brand link.

This tutorial shows you how to create and customize your own dashboard with minimum effort.

* Create a new bundle named `MyDashboardBundle`
* Create a new controller `DashboardController` and a corresponding action `welcome` as follows: 

```php

// src/Acme/MyDashboardBundle/Controller/DashboardController.php
<?php

namespace Acme\MyDashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function welcomeAction()
    {
        return $this->render('AcmeMyDashboardBundle:Dashboard:welcome.html.twig', array());
    }
}
```

* Create a new template for the action defined in `src/AcmeMyDashBoardBundle/views/Dashboard/welcome.html.twig`. You might need to replace `extends('AdmingeneratorGeneratorBundle::base_admin_assetic_less.html.twig')` with `extends('AdmingeneratorGeneratorBundle::base_admin.html.twig')` when you want to use the Assetic-aware configuration.

```php
{% extends('AdmingeneratorGeneratorBundle::base_admin_assetic_less.html.twig') %}
{% block body %}
<div class="row-fluid">
      <div class="span3">
        <div class="well sidebar-nav">
          {{ knp_menu_render('dashboard') }}
        </div><!--/.well -->
      </div><!--/span-->
      <div id="dashboard-content" class="span9">
          {% block content %}
          {% endblock %}
      </div><!--/span-->
</div><!--/row-->
{% endblock %}
```
* In the file called `DefaultMenuBuilder.php` created at the previous step you can edit the method called `createDashboardMenu` to customize your dashboard menu as follows.

For add menu header just make:

```php
$this->addNavHeader($menu, 'Group 1');
```

And  parent elements:

```php
$this->addNavLinkRoute($menu, 'Item1', 'Your_routeName');
```

You can add icon near menu item:

```php
$this->addNavLinkRoute($menu, 'Item1', 'Your_routeName')->setExtra('icon', 'icon-list');
```

**Full configuration**

```php
 public function createDashboardMenu(Request $request)
 {
    $menu = $this->factory->createItem('root');
    $menu->setChildrenAttributes(array('id' => 'dashboard_sidebar', 'class' => 'nav nav-list'));
    $menu->setExtra('request_uri', $this->container->get('request')->getRequestUri());
    $menu->setExtra('translation_domain', 'Admingenerator');

    $this->addNavHeader($menu, 'Group 1');
    $this->addNavLinkRoute($menu, 'Item1', 'Your_routeName')->setExtra('icon', 'icon-list');
    $this->addNavLinkRoute($menu, 'Item2', 'Your_routeName')->setExtra('icon', 'icon-bullhorn');
    $this->addNavLinkRoute($menu, 'Item3', 'Your_routeName')->setExtra('icon', 'icon-filter');
    $this->addNavLinkRoute($menu, 'Item4', 'Your_routeName')->setExtra('icon', 'icon-th-large');
    return $menu;
}
```
