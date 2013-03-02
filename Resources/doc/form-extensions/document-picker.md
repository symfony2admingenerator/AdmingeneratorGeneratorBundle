# Document Picker
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#5-form-extensions

### 1. Usage

DocumentPicker is a widget for [document field type](https://github.com/doctrine/DoctrineMongoDBBundle/blob/master/Form/Type/DocumentType.php). Instead of standard `<select>` tag it renders two `<input>` tags:

* First input (visible) is used to get text input from user to perform a search query (uses `twitter bootstrap/typeahead` widget).
* The second input (hidden) is used to store chosen entity's identifier value (most likely Document's `id` field, but it can be any unique field).

On form submission the first input's value is ignored.

### 2. Support

DocumentPicker supports only **Doctrine ODM** (document field type is doctrine-odm only).

For **Doctrine ORM** support see [EntityPicker][entity-picker]

[entity-picker]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/form-extensions/entity-picker.md

Propel has a similar field type (model) - creating a similar ModelPicker is planned.

### 3. Configuration

Example "Choose category" configuration:

```yaml
fields:
  category:
    label:            Category
    formType:         documentpicker
    addFormOptions:
      class:          Acme\CatalogBundle\Document\Category
      builder:        { name: name }
      matcher:        item.name
      primaryKey:     id
```

Example collection of related products configuration:

```yaml
fields:
  relatedProducts:
    label:            Related products          
    formType:         collection
# collection options
    addFormOptions:
      type:           documentpicker
      widget:         table
      allow_add:      true
      allow_delete:   true
      by_reference:   false
# document picker options
      options:
        label:        Related product
        class:        AcmeCatalogBundle:Product
        exclude:      collection
        builder:      { name: name, category: category }
        matcher:      item.name
        primaryKey:   id
        description:
          - { content: item.category, class: muted }
```

### 4. Options

#### builder

**type:** `Object` **default:** `{}`

Used to build proxy object passed to the template. If you want to use a field in `matcher`, `thumb` or `description` options, you need to map it to the proxy object first.

#### matcher

**type:** `string` **default:** `item.name`

Javascript expression against which query will be matched. Can be something as simple as `(item.name)` or more complex expression like `(item.title + ' by ' + item.author)`. To access proxy object's properties you need to map them first in `builder` option.

#### primaryKey

**type:** `string` **default:** `null`

Specifies document's primaryKey field name. Primary key is autoloaded into builder proxy object as `item.primaryKey`.

#### thumb.src

**type**: `string` **default:** `null`

(Optional) Used to display thumbnail image next to related query results. If `null` then thumbnail will not be displayed. Can be something as simple as `(item.thumb)` or more complex expression like `('http://www.acme.com/article/thumb/' + item.id)`. To access proxy object's properties you need to map them first in `builder` option.

#### thumb.width

**type**: `string` **default:** `32`

Thumbnail image width (in pixels).

#### thumb.height

**type**: `string` **default:** `32`

Thumbnail image height (in pixels).

#### description

**type**: `array` **default:** `array()`

(Optional) Used to display additional information about the result. Each entry represents one line.

```yaml
description:
  - { content: item.category, class: muted }
```

You may specify class for each line individually. See `Emphasis classes` section in [Base CSS/Typography](http://twitter.github.com/bootstrap/base-css.html#typography).

#### ~~exclude~~ 

> **Note:** this options is not working yet

**type**: `string` **default:** `null`

~~Used mainly for collections of document picker widgets. If specified excludes items from search results. Possible exclude strategies:~~

* ~~`document` strategy for simple document picker widgets - allows to exclude document picker's parent form~~
* ~~`collection` strategy for collection of document picker widgets - allows to exclude already selected items~~
* ~~`root` stragegy for nested forms - allows to exclude *'root'* form~~

#### inherited options

See [document form api](https://github.com/doctrine/DoctrineMongoDBBundle/blob/master/Form/Type/DocumentType.php).
