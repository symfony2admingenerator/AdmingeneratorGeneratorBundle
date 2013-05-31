# Usage of different Doctrine Entity Managers
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#4-generator

### 1. Usage

This option allows you to use another Doctrine ORM Entity Manager than the default
by suppling the option in the generator.yml.

### 2. Support

Multiple Entity Managers are only implemented for **Doctrine ORM**.

### 3. Syntax

```yaml
generator:            admingenerator.generator.doctrine
params:
  model:              Acme\GalleryBundle\Entity\Album
  entity_manager: ~                   # use the default Entity Manager from app/config.yml
  namespace_prefix:   Acme            # short syntax will use this as namespace
  bundle_name:        GalleryBundle   # short syntax will use this as bundle name
  fields: ~
```

```yaml
generator:            admingenerator.generator.doctrine
params:
  model:              Acme\GalleryBundle\Entity\Album
  entity_manager:     frontend        # use the "frontend" Entity Manager
  namespace_prefix:   Acme            # short syntax will use this as namespace
  bundle_name:        GalleryBundle   # short syntax will use this as bundle name
  fields: ~
```