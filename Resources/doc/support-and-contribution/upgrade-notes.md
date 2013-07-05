# Upgrade notes
----------------------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#2-support-and-contribution

This file lists B/C breaking PRs in reverse chronological order. Each PR contains 
description explaining nature of changes and upgrade notes to help you upgrade your 
project.

## PR [#562][pr562] Generated views are splitted in several files

[pr562]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/pull/562

#### Description:

This PR introduces a new file generation stragegy. See discussion [#515][issue515] for more information,
but most important to know about it is that generated views files are now splitted in:

 - 4 files for ``Lists``
 - 2 files for ``New`` and ``Edit``
 
 [issue515]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/issue/515

#### B/C breaks:

 - New files are required in your code source
 - Some blocks (in views) have been moved to different files
 - ``ListController`` organization has changed

#### Upgrade notes
 
For all your generators, you have to generate these new views files. You can do it manually creating
files:

 - filters.html.twig in all your ``xxxBundle/Resources/views/yyyList/`` directories
 - results.html.twig in all your ``xxxBundle/Resources/views/yyyList/`` directories
 - row.html.twig in all your ``xxxBundle/Resources/views/yyyList/`` directories
 - form.html.twig in all your ``xxxBundle/Resources/views/yyyNew/``
 and ``xxxBundle/Resources/views/yyyEdit/`` directories.

or you can also regenerate all files thanks to the Symfony command:

 1. Run `php app/console admin:generate-admin` and re-create bundle structure.
 2. Copy any custom changes from old files
 3. Remove unnecessary copies

> **Note:** When generating bundle structure, if a file already exists, it will be
renamed to `oldname~` before new file is generated. If a copy already exists, an 
incrementing number will be appended to new copy's name (e.g. `oldname~1`).

Second thing to know is that block names have moved:
 
 - ``list_td_column_xxxx`` are in ``xxxList/row.html.twig`` file
 - object actions are now in ``xxxList/row.html.twig`` file
 - ``list_thead`` is now in ``xxxList/results.html.twig`` file
 - list form, batch actions and paginator are in ``xxxList/results.html.twig`` file
 - filters related blocks must be in ``xxxList/filters.html.twig`` file
 - new and edit forms have moved to a dedicated view in ``xxx[Edit|New]/form.html.twig``
 
Of course, you can still have a look on the generated files (in ``app/cache/env/admingenerated`` directory) to find
your blocks (very few block names have changed).

To finish with views, if you have a custom version of ``XxxxBuilderTemplate`` have 
a look of the new ones, changes are important (based on includes).

Last thing to merge (only if you overrided default behaviors) is your ``ListController``s:
 - ``indexAction`` has changed: paginator management is now in a new separated function
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
