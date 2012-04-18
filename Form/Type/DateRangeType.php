<?php

namespace Admingenerator\GeneratorBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;


class DateRangeType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
         unset($options['years']);

         $options['from']['required'] = $options['required'];
         $options['to']['required'] = $options['required'];

         if ($options['format']) {
            $options['from']['format'] = $options['format'];
            $options['to']['format'] = $options['format'];
         }


         $builder
               ->add('from', new DateType(), $options['from'])
               ->add('to', new DateType(), $options['to']);
    }

    public function getParent(array $options)
    {
        return 'form';
    }

    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'format'            => null,
            'years'             => range(date('Y'), date('Y') - 120),
            'to'                => null,
            'from'              => null,
            'widget'            => 'choice',
        );

        $options = array_replace($defaultOptions, $options);

        if (is_null($defaultOptions['to'])) {
            $defaultOptions['to'] = array('years' => $defaultOptions['years'], 'widget' => $defaultOptions['widget']);
        }

        if (is_null($defaultOptions['from'])) {
            $defaultOptions['from'] = array('years' => $defaultOptions['years'], 'widget' => $defaultOptions['widget']);
        }

        return $defaultOptions;
    }

    public function getName()
    {
        return 'date_range';
    }
}
