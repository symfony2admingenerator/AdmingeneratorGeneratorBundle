# Concurrency lock
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#8-cookbook

### 1. Usage

To protect your models edit action against concurrent modifications during long-running 
business transactions Admingenerator introduced a new generator parameter `concurrency_lock`. 
If enabled, before updateing object, Admingenerator will check if there were any 
modifications in between by compareing object versions. 

### 2. Support

Concurrency lock is avaliable for **Doctrine ORM**, **Doctrine ODM** and **Propel**.

### 3. Setting up

#### Enable in generator.yml

```yaml
# ...
params:
    concurrency_lock:  true
```

#### Add versionable field (Doctrine ORM and Doctrine ODM)

```php
<?php
class Foo
{
    // ...
    /** @Version @Column(type="integer") */
    private $version;

    /**
     * Get version
     *
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }
    // ...
}
```

#### Enable versionable behaviour (Propel)

In the schema.xml, use the `<behavior>` tag to add the versionable behavior to a table: 

```xml
<table name="book">
    <column name="id" required="true" primaryKey="true" autoIncrement="true" type="INTEGER" />
    <column name="title" type="VARCHAR" required="true" />
    <behavior name="versionable" />
</table>
```