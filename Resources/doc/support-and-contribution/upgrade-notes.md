# Upgrade notes
----------------------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#2-support-and-contribution

This file lists B/C breaking PRs in reverse chronological order. Each PR contains 
description explaining nature of changes and upgrade notes to help you upgrade your 
project.

## PR [#508][pr508] Custom object actions stub generator

[pr508]: [https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/pull/508]

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
