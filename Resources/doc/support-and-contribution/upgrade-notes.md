# Upgrade notes
----------------------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#2-support-and-contribution

This file lists B/C breaking PRs in reverse chronological order. Each PR contains 
description explaining nature of changes and upgrade notes to help you upgrade your 
project.

## PR [#707][pr707] Remove annotations autoloading

[pr707]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/pull/707

#### Description:

This PR changes the Router Loader behavior from AdmingeneratorBundle. Before the PR, Admingenerator loaded
annotations routes in the same time as the `admingenerator` route type. This implied a dependence to 
FrameworkExtraBundle (not mentionned in composer.json file). After the PR, a `admingenerator` route type
doesn't load annotations route anymore.

#### BC Break:

If you used annotations routes from Controller inside admingenerator controllers folders, you may faced
the BC break: annotations routes are not automatically loaded anymore. You need to explicitly import your routes.
For example if you previously add some Controllers or routes via annotations in an Admingenerated folder you should
now import routes like that:

```yaml
## What you probably already have
AcmeDemoBundle_admin_acme_demo_bundle_Product:
    resource: "@AcmeDemoBundle/Controller/Product/"
    type:     admingenerator
    prefix:   /admin/acme_demo_bundle/Product

## What you should add... if required
AcmeDemoBundle_admin_acme_demo_bundle_Product_annotation:
    resource: "@AcmeDemoBundle/Controller/Product/"
    type:     annotation
```



## PR [#623][pr623] Fix localized date

[pr623]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/pull/623
[pr623doc]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/builders/common/localized-date.md

#### Description:

This PR fixes the way admingenerator uses localizeddate twig filter.

#### B/C breaks:

* `date_format` is not used anymore, instead `format` option of the field is used
* `datetime_format` is not used anymore, instead `format` option of the field is used

#### Upgrade notes

If you used `date_format` or `datetime_format` to control localized date format, please 
refer to [Resources/doc/builders/common/localized_date.md][pr623doc] and update your config.

## PR [#575][pr575] Major bundle refactoring

[pr575]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/pull/575

#### Description:

This PR introduces a few major changes in how this bundle is built and some minor 
fixes. It also introduces some BC breaks which will be explained in detail below.

##### 1. Major change: menu refactoring

[menu-cookbook-entry]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/cookbook/menu.md

Creating and customizing menus has been simplified. For details see the 
[cookbook entry][menu-cookbook-entry].

##### 2. Major change: forms refactoring

[avocode-form-extensions-bundle]: https://github.com/avocode/FormExtensions
[genemu-form-bundle]: https://github.com/genemu/GenemuFormBundle
[vincet-bootstrap-form-bundle]: https://github.com/vincenttouzet/BootstrapFormBundle

All forms have been removed from the bundle. Instead, a new bunlde 
[avocode/form-extensions-bundle][avocode-form-extensions-bundle] has 
been created. The new bundle contains following form types:

* *Bootstrap Collection* family: `collection_fieldset`, `collection_table`
* *Collection Upload*: `collection_upload`
* *Color Picker*: `color_picker`
* *Date Picker*: `date_picker`
* *DateRange Picker*: `daterange_picker`
* *DateTime Picker*: `datetime_picker`
* *Double List* family: `double_list_document`, `double_list_entity`, `double_list_model`
* *Select2* family: `select2_choice`, `select2_country`, `select2_document`, 
`select2_entity`, `select2_language`, `select2_locale`, `select2_model`
`select2_timezone`
* *Single Upload*: `single_upload`
* *Time Picker*: `time_picker`

> **Note:** *EntityPicker* type will not be continued. Use *Select2* type instead.

Some of these form types are based on [genemu/form-bundle][genemu-form-bundle], 
some are based on [vincet/bootstrap-form-bundle][vincet-bootstrap-form-bundle], 
however they all have been tweaked to work *out of the box* with **Admingenerator**.

> **Note:** In future *Chosen*, *Knob* and some of *jQuery ui* form types will be added. 
If you want to propose a new form type or submit an issue regarding forms, please do that in 
[avocode/form-extensions-bundle][avocode-form-extensions-bundle].

Please note that [avocode/form-extensions-bundle][avocode-form-extensions-bundle] 
conficts with [genemu/form-bundle][genemu-form-bundle]. If you need some of 
genemu form type that is not present in avocode bundle, please submit an issue to 
request a new form type.

There were two reasons for this change:

* seperate two completetly diffrent functionals *admin generateing* and *form types* as 
both of them are complex and mixing them in one bundle just caused confusion and 
errors
* introduce unlimited nesting of form types - if you use *Bootstrap Collection* form 
you can embed nested forms inside (even other *Bootstrap Collection* forms!)

##### 3. Major change: assets refactoring

[robloach-component-installer]: https://github.com/RobLoach/component-installer

Third-party assets like Twitter Bootstrap, jQuery, jQuery UI, HTML5shiv or 
Elusive Icons have been removed from the bundle. Instead, dependencies to 
[robloach/component-installer][robloach-component-installer] and compatible 
shim repositories have been added.

Elusive icon font has been replaced by FontAwesome icon font, because the latter 
has a shim repository compatible with *component-installer* and has many cool 
features like:

* rotateing icons
* animated spinning icons
* stacked icons
* larger icon classes

##### 4. Minor change: actions refactoring

Generic, object and batch action templates have been moved out of `EditBuilder` 
and `ListBuilder` directory to `CommonAdmin` since their templates are the same. 
Some template block names have changed.

##### 5. Minor change: allow enableing multiple ORMs 

Multiple ORM support has been dropped around the time when EntityPicker widget 
was introduced. Since all form types have been moved out of this bundle, and the 
bundle structure has been fixed, multiple ORMs feature has been reintroduced!

##### 6. Minor change: expose configuration for default form types

Since the forms have been moved out of this bundle, the default form types had 
to be changed to symfony default forms. However, you will probably be interesed 
to choose which database form types should be used to edit which database field 
types. This PR allows such configuration.

##### 7. Minor change: expose configuration for default filter types

For the same reasons as form types, now you can also configure which form types 
should be used to filter which database field types.

##### 8. Minor change: unify base admin templates

Since `base_admin` and `base_login` templates are now exacly the same, the latter 
was removed to make it easier to manage updates of this file in future PRs.

#### B/C breaks:

 - Base MenuBuilder class has changed (renamed methods)
 - All form types removed from this bundle
 - All third-party assets removed from this bundle
 - Default form types have changed
 - Default filter types have changed

#### Upgrade notes

These upgrade notes will describe in detail what changes your project requires. 
However, if you follow them and still have problems, feel free to submit an issue, 
I'll try to answer as soon as possible.

Remove these (old) bundle configurations:

* `admingenerator_generator.knp_menu_class`
* `admingenerator_generator.thumbnail_generator`
* `admingenerator_generator.use_genemu_form_fallback`

After composer update run these commands:

* `php app/console cache:clear`
* `php app/console assetic:dump`

##### 1. Upgrade menu:

First read about new menu structure [in cookbook][menu-cookbook-entry]. 

> **Note:** Your menu builder no longer requires `Knp\Menu\FactoryInterface` 
injected into constructor and translation domain instead of being passed down 
the menu tree is being held in protected class property `$translation_domain`.

To upgrade your menu you must apply following changes to all your menus:

* rename all `addNavHeader` method calls to `addHeader`
* rename all `addNavLinkURI` method calls to `addLinkURI`
* rename all `addNavLinkRoute` method calls to `addLinkRoute`
* rename all `addDropdownMenu` method calls to `addDropdown`

Then edit your base template and overwrite the `menu` block inside 
the `navbar` block to render menu from your builder class.

E.g. if your builder class is in `Your/CustomBundle/Menu/CustomBuilder.php`:

```html+django
{% extends 'AdmingeneratorGeneratorBundle::base_admin.html.twig' %}

{% block navbar %}
{% embed 'AdmingeneratorGeneratorBundle::base_admin_navbar.html.twig' %}
    {% block menu %}
        {{ knp_menu_render('YourCustomBundle:YourBuilder:navbarMenu') }}
    {% endblock %}
{% endembed %}
{% endblock navbar %}
```

##### 2. Upgrade forms:

Since all forms have been moved to `avocode/form-extensions-bundle` you must add 
it to your `composer.json`:

```json
    "require": {
        // ...
        "avocode/form-extensions-bundle": "dev-master"
    }
```

And then enable the bundle in your `AppKernel.php`:

```php
<?php
    // AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Avocode\FormExtensionsBundle\AvocodeFormExtensionsBundle(),
        );
    }
```

To make `avocode/form-extensions-bundle` forms work, you need to edit your base 
template, and include static and dynamic stylesheets and javascripts. 

```html+django
{% block stylesheets %}
    {{ parent() }}

    {% include 'AvocodeFormExtensionsBundle::stylesheets.html.twig' %}
    {% if form is defined %}
        {{ afe_form_stylesheet(form) }}
    {% endif %}
{% endblock %}

{% block javascripts %}
    {{ parent() }}

    {% include 'AvocodeFormExtensionsBundle::javascripts.html.twig' %}
    {% if form is defined %}
        {{ afe_form_javascript(form) }}
    {% endif %}
{% endblock %}
```

> **Note:** In this example we are useing assetic. If you don't want to use it then 
include `AvocodeFormExtensionsBundle::stylesheets_assetic_less.html.twig` and 
`AvocodeFormExtensionsBundle::javascripts_assetic_less.html.twig` instead.

> **Note:** By default Admingenerator controllers pass the form to the template 
in variable `form`, so we're adding dynamic javascript and stylesheets by calling 
`{{ form_stylesheet(form) }}` and `{{ form_javascript(form) }}`. If you pass 
your form in another variable, change the function calls too.

Now, you'll have to modify all your generator files:

[collection-upload-doc]: https://github.com/avocode/FormExtensions/blob/master/Resources/doc/collection-upload/overview.md
[image-manipulator-doc]: https://github.com/avocode/FormExtensions/blob/master/Resources/doc/cookbook/image-manipulator.md
[upload-manager-doc]: https://github.com/avocode/FormExtensions/blob/master/Resources/doc/cookbook/upload-manager.md

* `collection` *table* and *fieldset* widgets have become standalone form types.
If you used these, simply change the form type to `collection_table` or 
`collection_fieldset`, and remove the option `widget`.

* `upload` form type has been renamed to `collection_upload`. File Interface has 
been moved to `Avocode\FormExtensionsBundle\Form\Model\UploadCollectionFileInterface`. 
Also, `thumbnailFilter` option was renamed to `previewFilter` and now supports 
both `avalanche/imagine-bundle` and `liip/imagine-bundle`. Read cookbook entry 
[How to use image manipulator with this bundle?][image-manipulator-doc] if you 
want to use this option. If you used `vich/uploader-bundle` to handle file uploads 
you'll have to configure the upload manager option, read the cookbook entry 
[How to use upload manager with this bundle?][upload-manager-doc] 

* `single_upload` form type was updated, but it does not require any changes 
from your. Only `thumbnailFilter` option has been renamed to `previewFilter`. 
Read cookbook entry [How to use image manipulator with this bundle?][image-manipulator-doc] 
if you want to use this option. If you used `vich/uploader-bundle` to handle file 
uploads you'll have to configure the upload manager option, read the cookbook entry 
[How to use upload manager with this bundle?][upload-manager-doc] 

* `entitypicker` form type was removed. Use `afe_select2_entity`, `afe_select2_document` 
or `afe_select2_model` instead.

> **Note**: other Select2 family form types you can use are: `afe_select2_choice`, 
`afe_select2_language`, `afe_select2_country`, `afe_select2_timezone`, `afe_select2_locale`. 
In future this widget will be documented, for now read more about 
[Select2 options](http://ivaynberg.github.io/select2/) on its official website. 

* `datepicker` form type was removed. Use `afe_date_picker` or `afe_datetime_picker` instead.

> **Note**: In future this form will be documented, for now read more here: 
[Date Picker options](https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/date-picker/overwiev.md)

* `datepicker_range` form type was removed. Use `afe_daterange_picker` instead.

> **Note**: In future this form will be documented, for now read more here: 
[DateRange Picker options](https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/daterange-picker/overwiev.md)

All form types will be soon documented in 
[avocode/form-extensions-bundle][avocode-form-extensions-bundle].

##### 3. Upgrade assets:

All assets that have been removed will be now installed by 
[robloach/component-installer][robloach-component-installer] into `web/components` 
directory.

```html+django
{{ asset('components/jquery/jquery.js') }}
```

You must specify the directory where compoments should be installed, to do that
to add `component-dir` variable under `config` in your `composer.json`:

```json
    "config": {
        "component-dir": "web/components"
    },
```

> **Note:** If you have non-standard web directory name, you have to modify this.
E.g. if your web directory is `/pub` then this line should be 
`"component-dir": "pub/components"`.

##### 4. Upgrade actions:

Action files have been merged and moved:

* `CommonAdmin/EditTemplate/actions.html.twig` and `CommonAdmin/ShowTemplate/actions.html.twig` 
and `CommonAdmin/ListTemplate/actions.html.twig` 
merged as `CommonAdmin/generic_actions.html.twig`
* `CommonAdmin/ListTemplate/object_actions.html.twig` moved to `CommonAdmin/object_actions.html.twig`
* `CommonAdmin/ListTemplate/batch_actions.html.twig` moved to `CommonAdmin/batch_actions.html.twig`

Some block names have changed:

* (Edit Builder) `form_actions` and (ShowBuilder) `form_actions` and 
(ListBuilder and NestedListBuilder) `list_actions` became `generic_actions`
* (ListBuilder and NestedListBuilder) `td_list_action_ActionName` renamed to `generic_action_ActionName`
* (ListBuilder and NestedListBuilder) `list_object_actions` renamed to `object_actions`
* (ListBuilder and NestedListBuilder) `td_list_object_actions_ActionName` renamed to `object_action_ActionName`
* (ListBuilder and NestedListBuilder) `list_batch_actions` renamed to `batch_actions`
* (ListBuilder and NestedListBuilder) `td_list_batch_action_ActionName` renamed to `batch_action_ActionName`

##### 5. Upgrade to multiple ORMs:

If you want to use multiple ORMs, simply enable more than one in bundle's config:

```yaml
admingenerator_generator:
    # you have to enable at least one
    use_doctrine_orm:   true
    use_doctrine_odm:   false
    use_propel:         true
``` 

##### 6. Upgrade configuration for default form types

If you want to set default form types you can do that in bundle's config:

```yaml
admingenerator_generator:
    form_types:
        doctrine_orm:
            datetime:     datetime 
            vardatetime:  datetime 
            datetimetz:   datetime 
            date:         datetime 
            time:         time 
            decimal:      number 
            float:        number 
            integer:      integer 
            bigint:       integer 
            smallint:     integer 
            string:       text 
            text:         textarea
            entity:       entity 
            collection:   collection 
            array:        collection 
            boolean:      checkbox 
        doctrine_odm:
            datetime:     datetime 
            timestamp:    datetime 
            vardatetime:  datetime 
            datetimetz:   datetime 
            date:         datetime 
            time:         time 
            decimal:      number 
            float:        number 
            int:          integer 
            integer:      integer 
            int_id:       integer 
            bigint:       integer 
            smallint:     integer 
            id:           text 
            custom_id:    text 
            string:       text 
            text:         textarea 
            document:     document 
            collection:   collection 
            hash:         collection 
            boolean:      checkbox 
        propel:
            TIMESTAMP:    datetime 
            BU_TIMESTAMP: datetime 
            DATE:         date 
            BU_DATE:      date 
            TIME:         time 
            FLOAT:        number 
            REAL:         number 
            DOUBLE:       number 
            DECIMAL:      number 
            TINYINT:      integer 
            SMALLINT:     integer 
            INTEGER:      integer 
            BIGINT:       integer 
            NUMERIC:      integer 
            CHAR:         text 
            VARCHAR:      text 
            LONGVARCHAR:  textarea 
            BLOB:         textarea 
            CLOB:         textarea 
            CLOB_EMU:     textarea 
            model:        model 
            collection:   collection 
            PHP_ARRAY:    collection 
            ENUM:         choice 
            BOOLEAN:      checkbox 
            BOOLEAN_EMU:  checkbox 
```

> **Note:** in propel configuration if key is all uppercase (like `ENUM`) then it 
will be used to retrieve a constant from `\PropelColumnType`, eg. `\PropelColumnType::ENUM`,
if key is lowercase (or mixed) it will be used literally (eg. `model`)

##### 7. Upgrade configuration for default filter types

If you want to set default filter types you can do that in bundle's config:

```yaml
admingenerator_generator:
    filter_types:
        doctrine_orm:
            text:          text
            boolean:       choice
            collection:    entity
        doctrine_odm:
            hash:          text
            text:          text
            boolean:       choice
            collection:    document
        propel:
            BOOLEAN:       choice
            BOOLEAN_EMU:   choice
            collection:    model
```

If default filter is not set, then default form type will be used.

> **Note:** in propel configuration if key is all uppercase (like `BOOLEAN`) then it 
will be used to retrieve a constant from `\PropelColumnType`, eg. `\PropelColumnType::BOOLEAN`,
if key is lowercase (or mixed) it will be used literally (eg. `collection`)

##### 8. Upgrade base admin templates

Since `base_login` templates have been removed, if you use AdmingeneratorUserBundle, 
you'll have to adapt your configuration. 

If you use a custom `login_template`, then instead of extending 
`AdmingeneratorGeneratorBundle::base_login.html.twig` make it extend 
`AdmingeneratorGeneratorBundle::base_admin.html.twig`.

If you don't use custom `login_template`, then change your AdmingeneratorUserBundle 
configuration `admingenerator_user.login_template` to point to 
`AdmingeneratorGeneratorBundle::base_admin.html.twig`

##### Thats all! 

If you have problems/questions -> feel free to open issues, I'll try to help 
everyone. Also thank you for your patience, I promise this is one of the last 
PRs introduceing BC breaks. A stable 2.3 version very soon!

## PR [#562][pr562] Generated views are splitted in several files

[pr562]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/pull/562

#### Description:

This PR introduces a new file generation stragegy. See discussion 
[#515](https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/issue/515) 
for more information, but most important to know about it is that generated views 
files are now splitted in:

 - 4 files for `Lists`
 - 2 files for `New` and `Edit`

#### B/C breaks:

 - New files are required in your code source
 - Some blocks (in views) have been moved to different files
 - `ListController` organization has changed

#### Upgrade notes
 
For all your generators, you have to generate these new views files. You can do it manually creating
files:

 - filters.html.twig in all your `xxxBundle/Resources/views/yyyList/` directories
 - results.html.twig in all your `xxxBundle/Resources/views/yyyList/` directories
 - row.html.twig in all your `xxxBundle/Resources/views/yyyList/` directories
 - form.html.twig in all your `xxxBundle/Resources/views/yyyNew/`
 and `xxxBundle/Resources/views/yyyEdit/` directories.

or you can also regenerate all files thanks to the Symfony command:

 1. Run `php app/console admin:generate-admin` and re-create bundle structure.
 2. Copy any custom changes from old files
 3. Remove unnecessary copies

> **Note:** When generating bundle structure, if a file already exists, it will be
renamed to `oldname~` before new file is generated. If a copy already exists, an 
incrementing number will be appended to new copy's name (e.g. `oldname~1`).

Second thing to know is that block names have moved:
 
 - `list_td_column_xxxx` are in `xxxList/row.html.twig` file
 - object actions are now in `xxxList/row.html.twig` file
 - `list_thead` is now in `xxxList/results.html.twig` file
 - list form, batch actions and paginator are in `xxxList/results.html.twig` file
 - filters related blocks must be in `xxxList/filters.html.twig` file
 - new and edit forms have moved to a dedicated view in `xxx[Edit|New]/form.html.twig`
 
Of course, you can still have a look on the generated files (in `app/cache/env/admingenerated` directory) to find
your blocks (very few block names have changed).

To finish with views, if you have a custom version of `XxxxBuilderTemplate` have 
a look of the new ones, changes are important (based on includes).

Last thing to merge (only if you overrided default behaviors) is your `ListController`'s:
 - `indexAction` has changed: paginator management is now in a new separated function
 if you never change anything about that, you have nothing to merge.

## PR [#508][pr508] Custom object actions stub generator

[pr508]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/pull/508

#### Description:

This PR introduces a new builder called `actions` which is used to generate 
stubs for custom actions. For more information on actions builder read actions builder 
[documentation](https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/builders/actions-builder.md).

#### B/C breaks:

This PR completely removes DeleteBuilder. Delete object and batch actions 
code has been moved to ActionsBuilder.

#### Upgrade notes:

In every bundle useing `Delete` builder:

1. Remove `YourBundle/Controller/YourModelController/DeleteController.php`
2. Remove `YourBundle/Resources/views/YourModelDelete` directory

In every `generator.yml` useing `Delete` builder:

1. Remove `delete` builder
2. Add `actions` builder

```yaml
# ...

builders:
# ...

# remove delete builder
    delete: ~ 
    
# add actions builder
    actions:
        params:
          object_actions:
              delete: ~
          batch_actions:
              delete: ~
```

> **Note:** This tells actions builder to generate code for `delete` object and 
batch action. You don't need to add any options as `delete` actions are configured
by default.

Generate `Actions` builder bundle structure:

1. Run `php app/console admin:generate-admin` and re-create bundle structure.
2. Copy any custom changes from old files
3. Remove unnecessary copies

> **Note:** When generating bundle structure, if a file already exists, it will be
renamed to `oldname~` before new file is generated. If a copy already exists, an 
incrementing number will be appended to new copy's name (e.g. `oldname~1`).
