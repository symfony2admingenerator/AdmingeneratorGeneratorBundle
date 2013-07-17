# Credentials for actions and fields
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#8-security

### 1. Description

Credentials allow you to protect actions and fields depending on your own logic (using security context, or object status,
or whatever you can think about).

### 2. Requirements

Credentials check in admingenerator are based on [JMSSecurityExtraBundle][jms-securityextrabundle-documentation] and 
`Expressions`. You need to activate them in your config file:

```yaml
    # config.yml
    jms_security_extra:
        expressions: true
```

### 3. Usage and configuration

Now, you can easily protect any action or field using the parameter `credentials` in your `generator.yml`.
Credentials should be a valid `Expression` string as described in the [JMSSecurityExtraBundle Expressions documentation][jms-securityextrabundle-expressions].
You can so, for example, easily protect any action or field using roles expression:

```yaml
  object_actions:
    delete:
      credentials: 'hasRole("ROLE_ADMIN")'
  fields:
    myField:
      credentials: 'hasRole("ROLE_USER")'
```

If all available native Expressions are not enough, you can create your own `Security Function` as described in
[JMSSecurityExtraBundle "Creating your own Expression function" documentation][jms-securityextrabundle-expression-function].
Example:

1- Create your service

```php
<?php

namespace Acme\DemoBundle\Security;

use Symfony\Component\DependencyInjection\ContainerInterface;
use JMS\DiExtraBundle\Annotation as DI;

/** @DI\Service */
class MyObjectAccessEvaluator
{
    private $container;

    /**
     * @DI\InjectParams({
     *     "container" = @DI\Inject("service_container"),
     * })
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /** @DI\SecurityFunction("isActivatedByUser") */
    public function isActivatedByUser(MyObject $myObject = null)
    {
        // Your own logic.
        // Must return a boolean value
    }
}
```

2- Use your new function in the configuration:

```yaml
    # On actions
    object_actions:
        delete:
            credentials: 'isActivatedByUser(object)'
    # Or on a field
    fields:
        my_field:
            credentials: 'isActivatedByUser()'
```


[jms-securityextrabundle-documentation]: http://jmsyst.com/bundles/JMSSecurityExtraBundle
[jms-securityextrabundle-expressions]: http://jmsyst.com/bundles/JMSSecurityExtraBundle/master/expressions
[jms-securityextrabundle-expression-function]: https://github.com/schmittjoh/JMSSecurityExtraBundle/blob/master/Resources/doc/cookbook/creating_your_own_expression_function.rst