# Run tests

To run test properly, you have to setup this dependencies :

## behat :

```shell
    git submodule add git://github.com/Behat/Behat.git vendor/Behat/Behat
    git submodule add git://github.com/Behat/Gherkin.git vendor/Behat/Gherkin
    git submodule add git://github.com/Behat/Mink.git vendor/Behat/Mink
    git submodule add git://github.com/Behat/BehatBundle.git vendor/Behat/BehatBundle
    git submodule add git://github.com/Behat/MinkBundle.git vendor/Behat/MinkBundle
```

In your AppKernel

```php
   if (in_array($this->getEnvironment(), array('test'))) {
        $bundles[] = new Behat\BehatBundle\BehatBundle();
        $bundles[] = new Behat\MinkBundle\MinkBundle();
   }
```

In your autoload.php

```php
    //Behat
    'Behat\\BehatBundle'    => __DIR__.'/../vendor/',
    'Behat\\MinkBundle'     => __DIR__.'/../vendor/',
    'Behat\\Behat'          => __DIR__.'/../vendor/Behat/Behat/src',
    'Behat\\SahiClient'     => __DIR__.'/../vendor/Behat/SahiClient/src',
    'Behat\\Mink'           => __DIR__.'/../vendor/Behat/Mink/src',
    'Behat\\Gherkin'        => __DIR__.'/../vendor/Behat/Gherkin/src',
```

Run tests

```shell
    php app/console -e=test behat
```


