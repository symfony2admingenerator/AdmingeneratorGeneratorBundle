# CSRF Protection
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#4-generator

### 1. Default protection

Admingenerator protects default actions (New, Edit, Delete) and all batch actions by checking CSRF token. CSRF token variable is added to all list action links.

### 2. Protect your custom actions

If you have any custom actions in your project you should add `csrf_protection` code snippets to generated controller and use them to check CSRF token.

Examples based on `Resources\templates\CommonAdmin\DeleteAction`.

##### in `XxxxxAction/XxxxxBuilderAction.php.twig`

```html+django
{% use '../CommonAdmin/DeleteAction/index.php.twig' %}
{% use '../CommonAdmin/security_action.php.twig' %}

// use csrf_protection code snippets
{% use '../CommonAdmin/csrf_protection.php.twig' %}

<?php

namespace Admingenerated\{{ namespace_prefix }}{{ bundle_name }}\{{ builder.generator.GeneratedControllerFolder }};

use {{ builder.generator.baseController }} as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;

// csrf_protection required use statements
{{ block('csrf_protection_use') }}

{{ block('security_use') }}

class DeleteController extends BaseController
{

    {{- block('index') -}}

    // csrf_protection functions
    {{- block('csrf_check_token') -}}

    {{- block('security_check_with_object') }}
```

##### in `XxxxxAction/index.php.twig`

```html+django
{% use '../CommonAdmin/security_action.php.twig' %}

// use csrf_protection code snippets
{% use '../CommonAdmin/csrf_protection.php.twig' %}

{% block index %}

    public function indexAction($pk)
    {
        // check csrf token
        // throws InvalidCsrfTokenException if token is invalid
        {{ block('csrf_action_check_token') }}
    
        try {
            ${{ builder.ModelClass }} = $this->getObject($pk);

            {{ block('security_action_with_object') }}
```