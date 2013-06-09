# Conditional actions
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#7-cookbook

## 1. Specify conditional action in generator.yml

Allow generator.yml to supply a conditional function to object actions, along with an "inverse" option on whether the object's conditional result should be inverse.  In the generator.yml, specify the condition like this:

```yaml
  object_actions:
    delete:
      condition:
        function: canDelete
        inverse: false
```

In this case, the generator's model object must contain a "canDelete" method:

```php
class ProductEntity 
{
      public function canDelete() 
      {
            return $this->countProductSales() == 0 ? true : false;
      }
}
```