# Command Line
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#8-cookbook

There are two ways to create an admin using console.

## 1. Add an admin on an existing bundle

Imagine you created a bundle before, using symfony standard commands.

[(how to create a bundle?)][symfony-1]

[symfony-1]: http://symfony.com/doc/2.0/book/page_creation.html

now you should generate an admin for this bundle.
use this command to generate an admin on an existing bundle :

``` bash
$ php app/console admin:generate-admin
```

Answer all questions step by step and admingenerator will generate your admin according to your answers.

##### here is an example:

![i18n 
form](https://raw.github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/master/Resources/doc/cookbook/images/console-example.png))


Now you have your admin.

## 2. Generating a bundle using admingenerator

(writing ...)
