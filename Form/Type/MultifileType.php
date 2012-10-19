<?php

namespace Admingenerator\GeneratorBundle\Form\Type;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\Extension\Core\Type\FileType;

class MultifileType extends FileType
{
    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['multipart'] = true;
        $view->vars['max_files'] = $options['max_files'];
        $view->vars['accepted'] = $options['accepted'];
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        
        $resolver->setDefaults(array(
            'max_files' => (-1),
            'accepted'  => '',
        ));
        
        $resolver->setAllowedTypes(array(
            'max_files'   => array('integer'),
            'accepted'    => array('string'),
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'multifile';
    }
}