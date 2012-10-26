# EntityPickerType

### 1. Usage

EntityPicker was designed to replace `<select>` tag. Instead it renders two `<input>` tags.

* First input (visible) is used to get text input from user to perform a search query (uses `twitter bootstrap/typeahead` widget).
* The second input (hidden) is used to store chosen entitie's identifier value (most likely Entitie's `id` field, but it can be any unique field).

On submission the first input's value is ignored.

### 2. Configuration

```yaml
fields:
  song:
    formType:         entitypicker
    addFormOptions:
      class:          Acme\RadioBundle\Entity\Song
      builder:        { pk: id, title: title, album: album.name, artist: album.artist.name, thumb: album.cover }
      matcher:        (item.artist + ' - ' + item.name) ## e.g. Will Smith - Miami
      identifier:     item.pk
      thumb:
        src:          item.thumb
      description:
        - { content: item.album, class: text-info }
        - { content: "('ID: ' + item.pk)", class: muted }
```

### 3. Options

#### class

**type:** `string` **required**

The class of your entity (e.g. AcmeRadioBundle:Song). This can be a fully-qualified class name (e.g. Acme\RadioBundle\Entity\Song) or the short alias name (as shown prior).

#### query_builder

**type:** `Doctrine\ORM\QueryBuilder` or a Closure

If specified, this is used to query the subset of options that should be used for the field. The value of this option can either be a QueryBuilder object or a Closure. If using a Closure, it should take a single argument, which is the EntityRepository of the entity.

#### em

**type:** `string` **default:** `the default entity manager`

If specified, the specified entity manager will be used to load the choices instead of the default entity manager.

#### builder

**type:** `Object` **default:** `{ pk: id, name: name }`

Used to build proxy object passed to the template. If you want to use a field in `matcher`, `identifier`, `thumb` or `description` options, you need to map it to the proxy object first.

#### matcher

**type:** `string` **default:** `item.name`

Javascript expression against which query will be matched. Can be something as simple as `(item.name)` or more complex expression like `(item.title + ' by ' + item.author)`. To access proxy object's properties you need to map them first in `builder` option.

#### identifier

**type:** `string` **default:** `item.pk`

Javascript expression used to return selected item's identifier. Can be something as simple as `(item.pk)` or more complex expression like `(item.title + ' by ' + item.author)`. To access proxy object's properties you need to map them first in `builder` option.

#### thumb.src

**type**: `string` **default:** `null`

(Optional) Used to display thumbnail image next to related query results. If `null` then thumbnail will not be displayed. Can be something as simple as `(item.thumb)` or more complex expression like `('http://www.acme.com/article/thumb/' + item.id)`. To access proxy object's properties you need to map them first in `builder` option.

#### thumb.width

**type**: `string` **default:** `32px`

Thumbnail image width.

#### thumb.height

**type**: `string` **default:** `32px`

Thumbnail image height.

#### description

**type**: `array` **default:** `array()`

(Optional) Used to display additional information about the result. Each entry represents one line.

```yaml
description:
  - { content: item.album, class: text-info }
  - { content: "('ID: ' + item.pk)", class: muted }
```

This config will display two lines of additional information:

* item's Album name (in blue text)
* `ID: x`, where x is item's id (in grey text)

You may specify class for each line individually. See `Emphasis classes` section in [Base CSS/Typography](http://twitter.github.com/bootstrap/base-css.html#typography).

#### 4. Collection of EntityPicker

If you want to select multiple entities you should simply use a collection of EntityPicker fields.

> **Note:** Items already selected are excluded from search results

```yaml
fields:
  tags:                
    formType:         collection
    extras:
      new_label:      Add tag
    addFormOptions:
      type:           entitypicker
      allow_add:      true
      allow_delete:   true
      by_reference:   false
      options:
        class:        Acme\StoreBundle\Entity\Tag
```