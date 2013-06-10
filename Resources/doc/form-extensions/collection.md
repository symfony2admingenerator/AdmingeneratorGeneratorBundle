# Collection
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#5-form-extensions

### 1. Usage

Collection an extension to the default [collection](http://symfony.com/doc/current/reference/forms/types/collection.html) field type.

It introduces new option `widget` which can be set to *default*, *fieldset* or *table*.

> **Note:** All options described below refer to *fieldset* and *table* views. The default view was unmodidied and may not be working properly.

### 2. Support

Collection widget is avaliable for **Doctrine ORM**, **Doctrine ODM** and **Propel**.

### 3. Configuration

Example configuration:

```yaml
fields:
  items:
    label:            Album items
    dbType:           collection
    formType:         collection
    addFormOptions:
      widget:         table
      sortable:       position  
      type:           \Acme\GalleryBundle\Form\Type\Item\EditType
      allow_add:      true
      allow_delete:   true
      by_reference:   false
      options:
        label:          Add new item
        data_class:     Acme\GalleryBundle\Entity\Item
```

### 4. Options

#### widget

**type:** `string` **default:** `default`

Specify which widget you want to use to display the collection. Possible options:

* `table` for table view
* `fieldset` for grid view
* `default` unmodified collection widget, does not support new features listed here

#### sortable

**type:** `string` **default:** `null`

If specified, enables sorting collection element. Sortable field will be hidden with CSS and used to store sortable position. 

> **Note:** Sortable position is `0-indexed` (first element's index is `0`).

#### new_label

**type:** `string` **default:** `collection.new_label`

New item label.

#### inherited options

See [collection form reference](http://symfony.com/doc/current/reference/forms/types/collection.html#field-options).