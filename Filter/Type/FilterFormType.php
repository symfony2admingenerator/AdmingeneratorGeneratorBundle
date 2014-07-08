<?php

namespace Admingenerator\GeneratorBundle\Filter\Type;

use Admingenerator\GeneratorBundle\Filter\FilterConfig;
use Admingenerator\GeneratorBundle\Filter\FilterItemInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class FilterFormType extends AbstractType implements FilterItemInterface
{
    protected $filterConfig;
    
    /**
     * @param FilterConfig $filterConfig Filter form configuration.
     */
    public function __construct(FilterConfig $filterConfig)
    {
        $this->filterConfig = $filterConfig;
    }
    
    /**
     * @return FilterConfig
     */
    public function getFilterConfig()
    {
        return $this->filterConfig;
    }
    
    /**
     * @param FormBuilderInterface  $builder
     * @param array                 $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($choices = $this->getComparisonOperators()) {
            $builder->add('comparison_operator', 'choice', array(
                'choices'   => $choices,
                'required'  => true,
                'translation_domain' => 'Admingenerator'
            ));
            
            $builder->add('value', $this->filterConfig->getFormType(), $this->filterConfig->getFormOptions());
        }
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['fieldLabel'] = $this->filterConfig->getFieldLabel();
    }
    
    /**
     * Get comparison operators
     * 
     * @return array
     */
    public function getComparisonOperators()
    {
        $filterType = $this->filterConfig->getFilterType();
        
        $comparisonOperators = array(
            'boolean'       => array(
                'bool_is'       => 'filters.boolean.is'
            ),
           'date'           => array(
                // unit: days
               'date_eq'        => 'filters.date.equal',
               'date_!eq'       => 'filters.date.not_equal',
               'date_gt'        => 'filters.date.greater_than',
               'date_gte'       => 'filters.date.greater_than_equal',
               'date_lt'        => 'filters.date.less_than',
               'date_lte'       => 'filters.date.less_than_equal'
           ),
           'time'           => array(
                // unit: seconds
               'time_eq'        => 'filters.time.equal',
               'time_!eq'       => 'filters.time.not_equal',
               'time_gt'        => 'filters.time.greater_than',
               'time_gte'       => 'filters.time.greater_than_equal',
               'time_lt'        => 'filters.time.less_than',
               'time_lte'       => 'filters.time.less_than_equal'
           ),
           'datetime'       => array(
                // unit: seconds
               'datetime_eq'    => 'filters.datetime.equal',
               'datetime_!eq'   => 'filters.datetime.not_equal',
               'datetime_gt'    => 'filters.datetime.greater_than',
               'datetime_gte'   => 'filters.datetime.greater_than_equal',
               'datetime_lt'    => 'filters.datetime.less_than',
               'datetime_lte'   => 'filters.datetime.less_than_equal'       
            ),
           'numeric'        => array(
               'num_eq'         => 'filters.numeric.equal',
               'num_!eq'        => 'filters.numeric.not_equal',
               'num_gt'         => 'filters.numeric.greater_than',
               'num_gte'        => 'filters.numeric.greater_than_equal',
               'num_lt'         => 'filters.numeric.less_than',
               'num_lte'        => 'filters.numeric.less_than_equal'
           ),
           'text'           => array(
               'text_eq'        => 'filters.text.equal',
               'text_!eq'       => 'filters.text.not_equal',
               'text_like'      => 'filters.text.like',
               'text_!like'     => 'filters.text.not_like'
           ),
           'collection'     => array(
               'arr_contains'   => 'filters.collection.contains',
               'arr_!contains'  => 'filters.collection.not_contains'
           ),
           'model'          => array(
               'model_eq'       => 'filters.model.equal',
               'model_!eq'      => 'filters.model.not_equal'
           )
        );
        
        return array_key_exists($filterType, $comparisonOperators)
               ? $comparisonOperators[$filterType]
               : null;
    }

    public function getName()
    {
        return 'admingenerator_filter_form';
    }
}
