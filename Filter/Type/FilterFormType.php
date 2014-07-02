<?php

namespace Admingenerator\GeneratorBundle\Filter\Type;

use Admingenerator\GeneratorBundle\Filter\FilterConfig;
use Admingenerator\GeneratorBundle\Filter\FilterItemInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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
        $builder->add('comparison_operator', 'choice', array(
            'choices'   => $this->getComparisonOperators(),
            'required'  => true,
        ));
        
        $builder->add('value', $this->filterConfig->getFormType(), $this->filterConfig->getFormOptions());
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
            'boolean' => array(
                'bool_eq'   => 'filters.boolean.equal',
                'bool_neq'  => 'filters.boolean.not_equal',
            ),
//            'time' => array(
//                '==' => 'The same day',
//                '!=' => 'Not the same day'
//            ),
//            'numeric' => array(
//                '==' => 'Equal',
//                '!=' => 'Not equal',
//                '>>'  => 'Greater than',
//                '>=' => 'Greater than or equal',
//                '<<'  => 'Less than',
//                '<=' => 'Less than or equal'
//            ),
//            'text' => array(
//                '==' => 'Equal',
//                '!=' => 'Not equal',
//                '%%' => 'Containing',
//                '!%' => 'Not containing'
//            ),
//            'collection' => array(
//                '++' => 'Containing',
//                '--' => 'Not containing'
//            ),
//            'model' => array(
//                '==' => 'Equal',
//                '!=' => 'Not equal'
//            )
        );
        
        return array_key_exists($filterType, $comparisonOperators)
               ? $comparisonOperators[$filterType]
               : array();
    }

    public function getName()
    {
        return 'admingenerator_filter_form';
    }
}
