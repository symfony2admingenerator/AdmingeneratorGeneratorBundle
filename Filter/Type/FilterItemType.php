<?php

namespace Admingenerator\GeneratorBundle\Filter\Type;

use Admingenerator\GeneratorBundle\Filter\EventListener\FilterItemSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class FilterItemType extends AbstractType
{
    protected $prototypeFieldName;

    /**
     * @param string $prototypeFieldName If specified, forces event listener to use config for given field.
     *                                   Used for creating form prototypes.
     */
    public function __construct($prototypeFieldName = null)
    {
        $this->prototypeFieldName = $prototypeFieldName;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new FilterItemSubscriber($options['filters'], $this->prototypeFieldName));
    }
    
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $field = $form->get('field')->getData();
        $config = $options['filters'][$field];
        $view->vars['field_label'] = $config['label'];
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'filters' => array()
        ));

        $resolver->setAllowedTypes(array(
            'filters' => array('array')
        ));
        
        $resolver->setAllowedValues(array(
            'filters' => function($filters) {
                foreach ($filters as $key => $value) {
                    if (!array_key_exists('field', $value) ||
                        !array_key_exists('label', $value) ||
                        !array_key_exists('filter', $value) ||
                        !array_key_exists('form', $value) ||
                        !array_key_exists('options', $value) ||
                        $key !== $value['field']
                    ) {
                        return false;
                    }
                }
                
                return true;
            },
        ));
    }

    public function getName()
    {
        return 'admingenerator_filter_item';
    }
}
