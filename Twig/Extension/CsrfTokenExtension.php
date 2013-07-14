<?php

namespace Admingenerator\GeneratorBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class CsrfTokenExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFilters()
    {
        return array(
            'csrf_token'          => new \Twig_Filter_Method($this, 'getCsrfToken'),
        );
    }

    public function getCsrfToken($intention)
    {
        $token = $this->container->get('form.csrf_provider')->generateCsrfToken($intention);

        return $token;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'admingenerator_csrf';
    }
}
