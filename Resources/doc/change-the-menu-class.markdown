# Change the class used to render the help menu by your

In config.yml set :

````yaml
admingenerator_generator:
    knp_menu_class: YourVendor\YourBackBundle\Menu\AdminMenu
````


To create your class you can use Admingenerator\GeneratorBundle\Menu\DefaultMenu to know how use the css classes
