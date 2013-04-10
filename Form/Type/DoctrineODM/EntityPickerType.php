<?php

namespace Admingenerator\GeneratorBundle\Form\Type\DoctrineODM;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;

class EntityPickerType extends DocumentType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['primaryKey']) {
            $options['builder'] = array_merge($options['builder'], array(
                'primaryKey' => $options['primaryKey'],
            ));
        }

        $view->vars['builder']      = $options['builder'];
        $view->vars['matcher']      = $options['matcher'];
        $view->vars['primaryKey']   = $options['primaryKey'];
        $view->vars['description']  = $options['description'];
        $view->vars['thumb']        = $options['thumb'];
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'exclude'         => null,  // this option is currently unused, patch coming soon
            'builder'         => array(),
            'matcher'         => 'item',
            'primaryKey'      => null,
            'description'     => array(),
            'thumb'           => array(
                'src'     => null,
                'width'   => '32',
                'height'  => '32'
            ),
        ));

        $resolver->setAllowedTypes(array(
            'exclude'         => array('null', 'string'),
            'builder'         => array('array'),
            'matcher'         => array('string'),
            'primaryKey'      => array('string'),
            'description'     => array('array'),
            'thumb'           => array('array'),
        ));

        $resolver->setAllowedValues(array(
            'exclude'       => array( null, 'document', 'collection', 'root' ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'entitypicker';
    }
}
