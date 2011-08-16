<?php

namespace Admingenerator\GeneratorBundle\Features\Context;

use Behat\BehatBundle\Context\BehatContext;
use Behat\BehatBundle\Context\MinkContext;
use Behat\Behat\Context\ClosuredContextInterfacen;
use Behat\Behat\Context\TranslatedContextInterfacen;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNoden;
use Behat\Gherkin\Node\TableNode;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Feature context.
 */
class FeatureContext extends MinkContext //MinkContext if you want to test web
{
//
// Place your definition and hook methods here:
//
//    /**
//     * @Given /^I have done something with "([^"]*)"$/
//     */
//    public function iHaveDoneSomethingWith($argument)
//    {
//        $container = $this->getContainer();
//        $container->get('some_service')->doSomethingWith($argument);
//    }
//

}