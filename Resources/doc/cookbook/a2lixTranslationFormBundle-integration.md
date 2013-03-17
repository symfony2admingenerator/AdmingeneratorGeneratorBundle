# a2lixTranslationFormBundle integration
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#7-cookbook

 `a2lixTranslationFormBundle` give you possibility to easily manage the translatable fields of your entity with a new form type: 'a2lix_translations'.

### 1. `a2lixTranslationFormBundle` Installation & Configuration

You can find documentation and example how to make fields translatable in [bundle github page][a2lix-readme] .
[a2lix-readme]: https://github.com/a2lix/TranslationFormBundle

### 2. Integration withs `AdmingeneratorGeneratorBundle`

##### 2.1. stylesheets and javascripts
First you  must connect bundle stylesheets and javascripts. In your `YourBundleNameBundle/Resources/config/yourPrefix-generator.yml` :

```yaml
...
 stylesheets:
    - bundles/a2lixtranslationform/css/a2lix_translation.css
    javascripts:
    - /bundles/a2lixtranslationform/js/a2lix_translation.js
...
```

##### 2.2. Minimal forrn configurations

 In your `YourBundleNameBundle/Resources/config/yourPrefix-generator.yml`


```yaml
...
 fields:  :
   translations:
        formType: a2lix_translations
...
```

And add to your **Edit** and **New** form builders **translations** field.

```yaml
...
    new:
        params:
            title: Title
            display: [title, description,translations]
            actions:
                save: ~
                list: ~
    edit:
        params:
            title: "Edit \"%object%\"|{ %object%: Model.title }|"
            display: [title, description, translations]
            actions:
                save: ~
                list: ~
                delete: ~
```

##### 2.3. Advanced forrn configurations

```yaml
...
 fields:  :
   translations:
        formType: a2lix_translations
        addFormOptions:
         locales: [en, pl]
         required: false
         fields: 
           title:
             label : name
             **OTHER_OPTIONS**       
             locale_options:
               en: 
                 label : Name
               pl:
                 label : Nazwa
           description:
             type: textarea
             **OTHER_OPTIONS** 
             locale_options:
               en: 
                 label : Descripcion
               pl:
                 label : Opis  
...
```

And add to your **Edit** and **New** form builders **translations** field.

```yaml
...
    new:
        params:
            title: Title
            display: [title, description,translations]
            actions:
                save: ~
                list: ~
    edit:
        params:
            title: "Edit \"%object%\"|{ %object%: Model.title }|"
            display: [title, description, translations]
            actions:
                save: ~
                list: ~
                delete: ~
```

##### 2.4. Full  configuration

```yaml
generator: admingenerator.generator.doctrine
params:
    model: Acme\DemoBundle\Entity\YourModel
    namespace_prefix: YourPrefix
    bundle_name: DemoBundle
    fields:  
      translations:
        formType: a2lix_translations
        addFormOptions:
         locales: [en, pl]
         required: false
         fields: 
           title:
             locale_options:
               en: 
                 label : Name
               pl:
                 label : Nazwa
           description:
             locale_options:
               en: 
                 label : Descripcion
               pl:
                 label : Opis  
    stylesheets:
    - bundles/a2lixtranslationform/css/a2lix_translation.css
    javascripts:
    - /bundles/a2lixtranslationform/js/a2lix_translation.js
builders:
    list:
        params:
            title: Title
            display: [title]
            actions:
                new: ~
            object_actions:
                edit: ~
                delete: ~
                show: ~
    filters:
        params:
            display: ~
    new:
        params:
            title: New
            display: [title, description,translations]
            actions:
                save: ~
                list: ~
    edit:
        params:
            title: "Edit \"%object%\"|{ %object%: YourModel.title }|"
            display: [title, description, translations]
            actions:
                save: ~
                list: ~
                delete: ~
    show:
        params:
            title: "Show  \"%object%\"|{ %object%: YourModel.title }|"
            display: [title, description]
            actions:
                list: ~
                new: ~
    delete: ~
```

##### 2.5. Result

![i18n form](https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/images/a2lix-integrations.png)
