# Controllers - Tips and Tricks
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#7-cookbook

### 1. Adding parameters in view from controllers

Sometimes, you may need to transmit some parameters from your *generated* controller to your *generated* view.
You can simply do it by overriding the protected **getAdditionalRenderParameters** function into your bundle. Depending on controller *type*, prototype is different:

In *list* controllers (`ListController` and `NestedListController`) prototype is:
```php
protected function getAdditionalRenderParameters(); // No parameters
```

In *edit*, *show* and *new* controllers (`EditController`, `ShowController` and `NewController`) prototype is:
```php
protected function getAdditionalRenderParameters(\Full\Path\To\Your\Object $Object);
```

This function **must** return an array.
By default, it returns an empty array.

> **Note:** this function is not available in `DeleteController` because it always returns a `RedirectResponse`.
