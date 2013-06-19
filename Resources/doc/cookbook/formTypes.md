# Form Types - Tips and Tricks
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#8-cookbook

## 1. Customize form options with PHP logic

If you need to customize your form options using some PHP logic, you have to overwrite function `getFormOption` in
the generated form type.

```php
protected function getAdditionalRenderParameters(); // No parameters
```

Prototype is:
```php
protected function getFormOption($name /*field name*/, array $formOptions /*generated form options*/);
```

This function **must** return an array.
By default, it returns pre-configured options.

> **Note:** use this only if you need some PHP logic (QueryBuilder is a good example). Otherwise, generator.yml file is
the good location to configure a field (using `addFormOptions` parameter).

Example:
```php
    /**
     * (non-PHPdoc)
     * @see \Admingenerated\ApplicationAdminBundle\Form\BaseProductType\FiltersType::getFormOption()
     */
    protected function getFormOption($name, array $formOptions)
    {
        switch($name) {
            case 'category':
                $formOptions['query_builder'] = function (CategoryRepository $er) {
                    return $er->getQueryBuilderForFind();
                };
                break;
        }

        return $formOptions;
    }
```