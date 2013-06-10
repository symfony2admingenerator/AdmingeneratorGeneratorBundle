# Conditional actions
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#7-cookbook

### 1. Usage

You may customize list by specifying conditional actions. For example, you may display 
delete button only if user has group "ProductEditor" and Product has no sales yet.

### 2. Support

Conditional actions are avaliable for **Doctrine ORM**, **Doctrine ODM** and **Propel**.

### 3. Configuration

Add conditional function to your `generator.yml` file:

```yaml
  object_actions:
    delete:
      condition:
        function:     canDelete
        parameters:   [ app.user ]
        inverse:      false
```

In this case, the generator's model object must contain a "canDelete" method:

```php
class ProductEntity 
{
      public function canDelete($user) 
      {
            return ($this->countProductSales() == 0 && $user->hasGroup('ProductEditor');
      }
}
```