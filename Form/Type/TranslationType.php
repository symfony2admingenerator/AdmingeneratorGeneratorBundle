<?php
namespace Admingenerator\GeneratorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Patrick Kaufmann
 */
class TranslationType extends AbstractType
{
    /**
      * {@inheritdoc}
      */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $columns = $options['columns'];
        $dataClass = $options['data_class'];

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(DataEvent $event) use ($builder, $columns, $dataClass) {
            $form = $event->getForm();
            $data = $event->getData();
            if(!$data instanceof $dataClass)
            {
                return;
            }

            //loop over all columns and add the input
            foreach($columns as $column => $options)
            {
                if($options == null) $options = array();

                $type = 'text';
                if(array_key_exists('type', $options))
                {
                    $type = $options['type'];
                }
                $label = $column;
                if(array_key_exists('label', $options))
                {
                    $label = $options['label'];
                }

                $customOptions = array();
                if(array_key_exists('options', $options))
                {
                    $customOptions = $options['options'];
                }
                $options = array(
                    'label' => $label.' '.strtoupper($data->getLocale())
                );

                $options = array_merge($options, $customOptions);

                $form->add($builder->getFormFactory()->createNamed($column, $type, null, $options));
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'translation_type';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return array(
            'columns' => array(),
            'language' => ''
        );
    }
}
