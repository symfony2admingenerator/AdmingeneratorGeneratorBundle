# Primary Key requirement
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#4-generator

### 1. Usage

This option allows you to specify primary key requirements to make admingenerator routes less greedy.

### 2. Support

Primary Key requirement supports **Doctrine ORM**, **Doctrine ODM** and **Propel**.

### 3. Example

Usage for the default Entity Manager
```yaml
generator:            admingenerator.generator.doctrine
params:
  model:              Acme\GalleryBundle\Entity\Album
  pk_requirement:     \d+
```

Will add a `\d+` requirement to these paths:

* edit path `/{pk}/edit`
* update path `/{pk}/update`
* show path `/{pk}/show}`
* object action path `/{pk}/{action}`
