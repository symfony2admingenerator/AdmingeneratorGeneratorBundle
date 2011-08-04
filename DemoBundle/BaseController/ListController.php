<?php

namespace Admingenerator\DemoBundle\BaseController;

use Admingenerator\GeneratorBundle\Controller\Doctrine\BaseController as BaseDoctrineController;
use Symfony\Component\HttpFoundation\Response;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ListController extends BaseDoctrineController
{
    /**
     * @Route("/", name="_admindemo")
     * @Template()
     */
	public function indexAction()
	{
		$Movies = $this
				->getDoctrine()->getEntityManager()
				->getRepository('Admingenerator\DemoBundle\Entity\Movie')
				->findAll();
			
		return array(
			'Movies' => $Movies,
		);
	}
	
	
	
}