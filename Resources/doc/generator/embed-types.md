# Embed form types
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#4-generator

### 1. Usage

This option allows you to generate form EditType from another generator to use it 
as embeded form (in Collection or Upload widget). This literally tells Admingenerator 
that before generating builders for current generator it needs to generate Edit builder
type from another generator.

### 2. Support

Embed types support **Doctrine ORM**, **Doctrine ODM** and **Propel**.

### 3. Syntax

#### full syntax

The full syntax is `Namespace`:`NameBundle`:`Filename` e.g. `Acme:DemoBundle:Test-generator.yml`
which will look for `Test-generator.yml` file in `AcmeDemoBundle`'s `Resources/config` directory.

#### short syntax

The short syntax is just `Filename` e.g. `Text-generator.yml`. The bundle namespace and name 
are assumed to be the same as current generator. 

```yaml
# this example uses doctrine orm, but it work for all generators
generator:            admingenerator.generator.doctrine
params:
  model:              Acme\GalleryBundle\Entity\Album
  namespace_prefix:   Acme            # short syntax will use this as namespace
  bundle_name:        GalleryBundle   # short syntax will use this as bundle name
  embed_types:
    # short syntax
    - "Image-generator.yml"
    # full syntax
    - "Acme:MediaBundle:Video-generator.yml"

  fields:
    images:
      label:            Images
      dbType:           collection
      formType:         upload
      addFormOptions:
        nameable:         name
        sortable:         position
        editable:         [ name, caption, position ]
        # use generated embed type
        type:             \Acme\GalleryBundle\Form\Type\Image\EditType
        allow_add:        true
        allow_delete:     true
        error_bubbling:   false
        options:
          data_class:     Acme\GalleryBundle\Entity\Image

    videos:
      label:            Videos
      dbType:           collection
      formType:         collection
      addFormOptions:
        widget:         fieldset
        # use generated embed type
        type:           \Acme\MediaBundle\Form\Type\Video\EditType
        allow_add:      true
        allow_delete:   true
        by_reference:   false
        options:
          label:          Image
          data_class:     Avocode\MediaBundle\Entity\Video
```

### 4. Embed generator minimum setup

Embed generator has to be a YAML file with EDIT builder. You must generate admingenerator 
files with `php app/console admin:generate-admin` command first. Then edit the generator 
config:

```yaml
# this example uses doctrine orm, but it work for all generators
generator:          admingenerator.generator.doctrine
params:
  model:            Acme\GalleryBundle\Entity\Image
  namespace_prefix: Acme
  bundle_name:      GalleryBundle
  i18n_catalog:     GalleryImage
  fields:
    name:
      label:         Name
    desc:
      label:         Description
    position:
      formType:  hidden
builders:
  edit:
    params:
      display:    [ name, caption, position ]
      title:      ""  # title has to be defined to avoid errors, but is not used
# other builders are not required
```
