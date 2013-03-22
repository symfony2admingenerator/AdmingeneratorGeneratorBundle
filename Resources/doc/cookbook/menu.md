# Menu
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#7-cookbook

### 1. Configuration

First of all don't forget enable translation of KnpMenu - add following lines to `app/config/config.yml`:

```yaml
knp_menu:
    twig:
        template: AdmingeneratorGeneratorBundle:KnpMenu:knp_menu_trans.html.twig
```

This is necessary because this template enables

* prepending menu items with icons
* appending caret to dropdown menu items
* translation of menu item labels

### 2. Installation 


In `/vendor/cedriclombardot/admingenerator-generator-bundle/Admingenerator/GeneratorBundle/Menu` you can find  **DefaultMenuBuilder.php** . You must copy this file in your bundle and in your `config.yml` :
```yaml
admingenerator_generator:
  knp_menu_class: Acme\YourBundleName\Menu\DefaultMenuBuilder
```
### 3. Header menu

In method `createAdminMenu` you can configure your header menu.

If you want add item to menu you just need make:

```php
$menu->addChild('News', array('route' => 'Your_RouteName'));
```

If you want create dropdown menu you just need make:


```php
$dropdownMenu = $this->addDropdownMenu($menu, 'Menu name',true);
$dropdownMenu->addChild('Item1', array('route' => 'Your_RouteName'));
$dropdownMenu->addChild('Item2', array('route' => 'Your_RouteName'));
```

You can add divider between items in dropdown menu:

```php
$dropdownMenu = $this->addDropdownMenu($menu, 'Menu name',true);

$dropdownMenu->addChild('Item1', array('route' => 'Your_RouteName'));
$this->addDivider($dropdownMenu);
$dropdownMenu->addChild('Item2', array('route' => 'Your_RouteName'));
```

And **full configuration** :


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

In method `createDashboardMenu` you can configure your dashboard menu. 

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

And **full configuration** :


```php
 public function createDashboardMenu(Request $request)
 {
    $menu = $this->factory->createItem('root');
    $menu->setChildrenAttributes(array('id' => 'dashboard_sidebar', 'class' => 'nav nav-list'));
    $menu->setExtra('request_uri', $this->container->get('request')->getRequestUri());
    $menu->setExtra('translation_domain', 'Admingenerator');

    $this->addNavHeader($menu, 'Group 1');
    $this->addNavLinkRoute($menu, 'Item1', 'Your_routeName')->setExtra('icon', 'icon-list');
    $this->addNavLinkRoute($menu, 'Item2', 'Bastion_BackendRoomBundle_Room_list')->setExtra('icon', 'icon-bullhorn');
    $this->addNavLinkRoute($menu, 'Item3', 'Bastion_BackendEventBundle_Event_list')->setExtra('icon', 'icon-filter');
    $this->addNavLinkRoute($menu, 'Item4', 'Bastion_BackendUserEventBundle_UserEvent_list')->setExtra('icon', 'icon-th-large');
    return $menu;
}
```
