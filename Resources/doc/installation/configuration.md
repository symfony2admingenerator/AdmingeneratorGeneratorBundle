# Configuration
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#1-installation

### 1. Twig section

Default configuration is:

```yaml
admingenerator_generator:
    twig:
        use_form_resources: true
        use_localized_date: false
        date_format: Y-m-d
        datetime_format: Y-m-d H:i:s
        localized_date_format: medium
        localized_datetime_format: medium
        number_format:
            decimal: 0
            decimal_point: .
            thousand_separator: ,
```

`use_form_resources`

By default, `AdmingeneratorGeneratorBundle` adds its own form theme to your application based on files `AdmingeneratorGeneratorBundle:Form:fields.html.twig` and `AdmingeneratorGeneratorBundle:Form:widgets.html.twig`. Depending on value of `admingenerator_generator.twig.use_form_resources` parameter and `twig.form.resources` one, you can modify this behavior:

* if `admingenerator_generator.twig.use_form_resources` is false, nothing will be change to `twig.form.resources` value;
* if `admingenerator_generator.twig.use_form_resources` is true and `twig.form.resources` doesn't contain `AdmingeneratorGeneratorBundle:Form:fields.html.twig`, resources `AdmingeneratorGeneratorBundle:Form:fields.html.twig` and `AdmingeneratorGeneratorBundle:Form:widgets.html.twig` are added into `twig.form.resources` right after `form_div_layout.html.twig`. If `form_div_layout.html.twig` is not in `twig.form.resources` values are unshifted;
* if `AdmingeneratorGeneratorBundle:Form:fields.html.twig` is already in `twig.form.resources` nothing will be change;

This permits you to control how `AdmingeneratorGeneratorBundle` modify form theming in your application. If you want to use another bundle for form theming (like `MopaBoostrapBundle`) you should probably define this parameter as false.

> **Note:** take care that if you are in this case, don't forget to add `AdmingeneratorGeneratorBundle:Form:widgets.html.twig` if you don't provide your own implementation.

*To complete*

### 2. Full configuration

```yaml
admingenerator_generator:
    ## Global
    use_doctrine_orm: false
    use_doctrine_odm: false
    use_propel: false
    overwrite_if_exists: false
    base_admin_template: AdmingeneratorGeneratorBundle::base_admin.html.twig
    knp_menu_class: Admingenerator\GeneratorBundle\Menu\DefaultMenuBuilder
    thumbnail_generator:  # null
    ## Twig and Templates
    twig:
        use_form_resources: true
        use_localized_date: false
        date_format: Y-m-d
        datetime_format: Y-m-d H:i:s
        localized_date_format: medium
        localized_datetime_format: medium
        number_format:
            decimal: 0
            decimal_point: .
            thousand_separator: ,
    templates_dirs: []
    ## Stylesheet
	stylesheets: [] # array of {path: path_to_stylesheet, media: all}
	javascripts: [] # array of {path: path_to_javascript, route: route_name, routeparams: [value1, value2]}
```
