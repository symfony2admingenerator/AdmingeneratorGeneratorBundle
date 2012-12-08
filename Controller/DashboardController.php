<?php

namespace Admingenerator\GeneratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function welcomeAction()
    {
        return $this->render('AdmingeneratorGeneratorBundle:Dashboard:welcome.html.twig', array(
            'base_admin_template' => $this->container->getParameter('admingenerator.base_admin_template'),
        ));
    }
    
    public function documentationAction($document)
    {
        return $this->render('AdmingeneratorGeneratorBundle:Documentation:'.$document.'.html.twig', array(
            'base_admin_template' => $this->container->getParameter('admingenerator.base_admin_template'),
        ));
    }
}