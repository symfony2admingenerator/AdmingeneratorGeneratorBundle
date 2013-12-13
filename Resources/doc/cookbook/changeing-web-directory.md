# Changeing web directory
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#7-cookbook

### 1. Overview

Some hosts have reserved `web` directory name. Make symfony2 run on such hosts
you must rename `web` directory. In this cookbook you will learn how to rename 
`web` directory to `pub`.

### 2. Configure Symfony2

Add a `symfony-web-dir` under *extra* in your `composer.json`:

```json
    "extra": {
        "symfony-web-dir": "pub"
    },
```

### 3. Configure RobLoach/component-installer

Admingenerator uses RobLoach/component-installer to install third-party assets like 
jquery, jqueryui or twitter bootstrap. To change the web directory add `component-dir` 
under *config* in your `composer.json`:

```json
    "config": {
        "component-dir": "pub/components"
    }
```
