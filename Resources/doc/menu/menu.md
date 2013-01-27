# Change the class used to render the help menu by your

In config.yml set :

````yaml
admingenerator_generator:
    knp_menu_class: YourVendor\YourBackBundle\Menu\AdminMenu
````

To create your class you can use Admingenerator\GeneratorBundle\Menu\DefaultMenu to know how use the css classes

# Extend default knp_menu_theme

In config.yml set :

````yaml
knp_menu:
    twig:
        template: AdmingeneratorGeneratorBundle:KnpMenu:knp_menu_trans.html.twig
````

This theme enables:

* prepending menu items with icons
* appending caret to dropdown menu items
* translation of menu item labels