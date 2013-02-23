# Upload
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#5-form-extensions

### 1. Usage

Upload is a widget for [collection](http://symfony.com/doc/current/reference/forms/types/collection.html) of files and entities associated with them.

It combines two forms into one:

* collection of already uploaded files
* file input to allow submitting new files

It transforms uploaded files into entities and allows easy manipulation of existing entities.

#### Features:

* Allow multiple file selection
* Drag & drop support
* Copy & paste support
* Display list of selected files
* Remove selected file from the list
* Display file size (browsers with HTML5 File API support only)
* Preview images
* Sort uploaded files
* Additional form fields (like name, description, etc)

#### Requirements:

* [VichUploaderBundle](https://github.com/dustin10/VichUploaderBundle)
* (Optional) [AvalancheImagineBundle](https://github.com/avalanche123/AvalancheImagineBundle)

### 2. Support

Upload supports **Doctrine ORM** and *may* support (untested) Doctrine ODM and Propel.

### 3. Configuration

Upload widget configuration:

```yaml
images:
  label:            Images
  dbType:           collection
  formType:         upload
  addFormOptions:
    nameable:         name
    sortable:         position
    editable:         [ name, description, position ]
    type:             \Acme\GalleryBundle\Form\Type\Image\EditType
    maxNumberOfFiles:           5
    maxFileSize:                500000
    minFileSize:                1000
    acceptFileTypes:            /(\.|\/)(gif|jpe?g|png)$/i
    previewSourceFileTypes:     /^image\/(gif|jpeg|png)$/
    previewSourceMaxFileSize:   250000
    previewMaxWidth:            100
    previewMaxHeight:           100
    previewAsCanvas:            true
    prependFiles:               false
```

### 4. Options

#### nameable

**type:** `string` **default:** `null`

If specified, normalized filenames will be autoloaded into this property upon upload.

#### sortable

**type:** `string` **default:** `null`

If specified, enables sortable behavior and uses this property to store position.

> **Note:** This property must be added to builder in formType and added to **editable** option.

#### editable

**type:** `array` **default:** `[]`

List of editable properties rendered with the form.

> **Note:** Each property must be added to formType builder first.

#### maxNumberOfFiles

**type:** `integer` **default:** `undefined`

This option limits the number of files that are allowed to be uploaded using this widget.
By default, unlimited file uploads are allowed.

> **Note:** The **maxNumberOfFiles** setting acts like an internal counter and will adjust automatically when files are added or removed. That is, if you set it to **2** and add one file, it will be decreased to **1**. If you remove one file from list, it will be increased again.

#### maxFileSize

**type:** `integer` **default:** `undefined`

The maximum allowed file size in bytes, by default unlimited.

> **Note:** This option has only an effect for browsers supporting the [File API](https://developer.mozilla.org/en/DOM/file).

#### minFileSize

**type:** `integer` **default:** `undefined`

The minimum allowed file size, by default undefined (can be 0 bytes).

> **Note:** This option has only an effect for browsers supporting the [File API](https://developer.mozilla.org/en/DOM/file).

#### acceptFileTypes

**type:** `Regular Expression` **default:** `undefined`

If defined the regular expression for allowed file types is matched against either file type or file name as only browsers with support for the File API report the file type.

#### previewSourceFileTypes

**type:** `Regular Expression` **default:** `/^image\/(gif|jpeg|png)$/`

The regular expression to define for which files a preview image is shown, matched against the file type.

> **Note:** Preview images are only displayed for browsers supporting the *URL* or *webkitURL* APIs or the *readAsDataURL* method of the [FileReader](https://developer.mozilla.org/en/DOM/FileReader) interface.

#### previewSourceMaxFileSize

**type:** `integer` **default:** `5000000`

The maximum file size for preview images in bytes.

#### previewMaxWidth

**type:** `integer` **default:** `80`

The maximum width of the preview images.

#### previewMaxHeight

**type:** `integer` **default:** `80`

The maximum height of the preview images.

#### previewAsCanvas

**type:** `boolean` **default:** `true`

By default, preview images are displayed as [canvas](https://developer.mozilla.org/en/HTML/canvas) elements if supported by the browser.
Set this option to false to always display preview images as *img* elements.

#### thumbnailFilter

**type:** `string` **default:** `null`

By default, thumbnail images are displayed with width and height attribute only. However, you may want to generate thumbnail images to reduce bandwidth usage. This option lets you specify [AvalancheImagineBundle](https://github.com/avalanche123/AvalancheImagineBundle) filter name.
If null, no filter will be applied. Requires setting configuration option `admingenerator_generator.thumbnail_generator` (see section 5 below).

#### prependFiles

**type:** `boolean` **default:** `false`

By default, files are appended to the files container.
Set this option to true, to prepend files instead.

> **Important:** These options are just for User Interface and cannot be considered safe (javascript is client-side). Always validate form input data server-side!

### 5. Use AvalancheImagineBundle to display thumbnails (optional)

To enable useing AvalancheImagineBundle for thumbnail generation just edit `config.yml`:

```yaml
admingenerator_generator:
    thumbnail_generator:  avalanche  # default null
```

### 6. Special thanks

Special thanks to [Sebastian Tschan](https://github.com/blueimp)  for `jQuery-File-Upload` licensed under [MIT License](https://github.com/blueimp/jQuery-File-Upload#license).