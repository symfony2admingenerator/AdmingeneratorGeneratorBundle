# Single Upload
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#5-form-extensions

### 1. Usage

SingleUpload is a widget for [file](http://symfony.com/doc/current/reference/forms/types/file.html).

#### Features:

* Display file name
* Display file size (browsers with HTML5 File API support only)
* Preview images or show filetype icon instead
* Nameable
* ~~Deletable~~ (not yet implemented)

#### Requirements:

* [VichUploaderBundle](https://github.com/dustin10/VichUploaderBundle)
* ~~(Optional)~~ [AvalancheImagineBundle](https://github.com/avalanche123/AvalancheImagineBundle)

### 2. Support

Single Upload widget is avaliable for **Doctrine ORM**, **Doctrine ODM** and **Propel**.

### 3. Configuration

Upload widget configuration:

```yaml
cover:
  label:            Album cover
  formType:         single_upload
  dbType:           string
  addFormOptions:
    nameable:       coverLabel
    data_class:     Symfony\Component\HttpFoundation\File\File
```

### 4. Options

#### nameable

**type:** `string` **default:** `null`

If specified, enables nameable behavior and uses this property to store file display name.

#### deleteable

**type:** `string` **default:** `false`

Not yet implemented. In future setting this to true will allow deleteing file.

#### maxWidth

**type:** `integer` **default:** `320`

Maximum preview image width. If image is bigger it will be scaled down to fit.

#### maxHeight

**type:** `integer` **default:** `180`

Maximum preview image height. If image is bigger it will be scaled down to fit.

#### minWidth

**type:** `integer` **default:** `16`

Minimum preview image width. If image is smaller it will be scaled up to fit.

#### minHeight

**type:** `integer` **default:** `16

Minimum preview image height. If image is smaller it will be scaled up to fit.

#### previewImages

**type:** `boolean` **default:** `true`

If false, images will not be previewed. Defaults to true.

#### previewAsCanvas

**type:** `boolean` **default:** `true`

By default, preview images are displayed as [canvas](https://developer.mozilla.org/en/HTML/canvas) elements if supported by the browser.
Set this option to false to always display preview images as *img* elements.

#### thumbnailFilter

**type:** `string` **default:** `null`

By default, thumbnail images are displayed with width and height attribute only. However, you may want to generate thumbnail images to reduce bandwidth usage. This option lets you specify [AvalancheImagineBundle](https://github.com/avalanche123/AvalancheImagineBundle) filter name.
If null, no filter will be applied. Requires setting configuration option `admingenerator_generator.thumbnail_generator` (see section 5 below).

### 5. Use AvalancheImagineBundle to display thumbnails (optional)

To enable useing AvalancheImagineBundle for thumbnail generation just edit `config.yml`:

```yaml
admingenerator_generator:
    thumbnail_generator:  avalanche  # default null
```

### 6. Special thanks

Special thanks to [FileSquare](http://www.filesq.com)  for `Filetypeicons.com` licensed under [Creative Commons Attribution-ShareAlike 3.0 Hong Kong License](http://creativecommons.org/licenses/by-sa/3.0/hk/). 
This bundle uses icons created by [loostro](https://github.com/loostro) and based upon `Filetypeicons.com`. They are released under the same license as the original work (you **must** credit FileSquare, you **don't have** to credit [loostro](https://github.com/loostro)).
