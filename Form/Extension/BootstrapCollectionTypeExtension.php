<?php

namespace Admingenerator\GeneratorBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BootstrapCollectionTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('widget', $options['widget']);
        $builder->setAttribute('sortable', $options['sortable']);
        $builder->setAttribute('new_label', $options['new_label']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['widget']           = $form->getConfig()->getAttribute('widget');
        $view->vars['sortable']         = $form->getConfig()->getAttribute('sortable');
        $view->vars['new_label']        = $form->getConfig()->getAttribute('new_label');
    }    
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
      
        $resolver->setDefaults(array(
            'widget'            =>  'default',
            'sortable'          =>  null,
            'new_label'         =>  'collection.new_label'
        ));
        
        $resolver->setAllowedTypes(array(
            'widget'            =>  array('string'),
            'sortable'          =>  array('null', 'string'),
            'new_label'         =>  array('string'),
        ));
        
        $resolver->setAllowedValues(array(
            'widget'      =>  array('default', 'fieldset', 'table'),
        ));
    }

    public function getExtendedType()
    {
        return 'collection';
    }
}
?>