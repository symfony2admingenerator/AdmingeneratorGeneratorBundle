<?php

namespace Admingenerator\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ListController extends Controller
{
    /**
     * @Route("/", name="_admindemo")
     * @Template()
     */
	public function indexAction()
	{
		return array();
	}
	
}