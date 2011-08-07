<?php

namespace Admingenerator\GeneratorBundle\Generator;


interface GeneratorInterface
{

    /**
     * Set the controller from routing request parameter _controller
     * @param string $controller
     */
    function setController($controller);
}