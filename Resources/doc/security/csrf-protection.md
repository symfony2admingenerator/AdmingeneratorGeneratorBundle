# CSRF Protection
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#9-security

### 1. Default protection

New and Edit views contain CSRF token protected forms. By default Admingenerator has only one object and batch action protected by CSRF token - Delete action. 

### 2. Generate CSRF token for your custom actions

To protect List view object and batch actions such as Delete we have introduced new property `$csrf_protected` which defaults to `false`. Enable CSRF protection for your custom object or batch action add this to action class:

```php
// Example taken from Generator/Action/Object/DeleteAction.php
class DeleteAction extends Action
{
    public function __construct($name, BaseBuilder $builder)
    {
        parent::__construct($name, $type = 'object');

        $this->setIcon('icon-remove');
        $this->setLabel('action.object.delete.label');
        $this->setConfirm('action.object.delete.confirm');
        
        // add this line to enable CSRF token protection for List view
        $this->setCsrfProtected(true);
```

This will add a `data-csrf-token="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"` to your action's button. When the button is clicked Jquery script will change this into POST request and send it.

### 3. Check CSRF token in actions controller

Sending CSRF token is not enough though. We need to check if a valid CSRF token was provided. To do that we must modify our actions controller.

> **Note:** in this example I'll show how to modify actions controller template. If you're not generateing your action, you'll need to hard copy code from `Resources/templates/CommonAdmin/csrf_protection.php.twig` to your action's controller class. **Remember to replace template variables!**

Examples based on `Resources\templates\CommonAdmin\DeleteAction`.

> **Note:** added lines are marked with comments

##### in `XxxxxAction/XxxxxBuilderAction.php.twig`

```html+django
{% use '../CommonAdmin/DeleteAction/index.php.twig' %}
{% use '../CommonAdmin/security_action.php.twig' %}

// use csrf_protection template code snippets
{% use '../CommonAdmin/csrf_protection.php.twig' %}

<?php

namespace Admingenerated\{{ namespace_prefix }}{{ bundle_name }}\{{ builder.generator.GeneratedControllerFolder }};

use {{ builder.generator.baseController }} as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;

// add csrf_protection required use statements to controller class
{{ block('csrf_protection_use') }}

{{ block('security_use') }}

class DeleteController extends BaseController
{

    {{- block('index') -}}

    // add csrf_protection functions to controller class
    {{- block('csrf_check_token') -}}

    {{- block('security_check_with_object') }}
```

##### in `XxxxxAction/index.php.twig`

```html+django
{% use '../CommonAdmin/security_action.php.twig' %}

// use csrf_protection template code snippets
{% use '../CommonAdmin/csrf_protection.php.twig' %}

{% block index %}

    public function indexAction($pk)
    {
        // add csrf_protection check token to action
        {{ block('csrf_action_check_token') }}
    
        try {
            ${{ builder.ModelClass }} = $this->getObject($pk);

            {{ block('security_action_with_object') }}
```
