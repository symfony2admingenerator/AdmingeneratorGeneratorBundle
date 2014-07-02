<?php

namespace Admingenerator\GeneratorBundle\Filter;

use Symfony\Component\Form\FormTypeInterface;

/**
 * Holds filter configuration
 * 
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class FilterConfig
{
    protected $fieldName;
    
    protected $filterType;
    
    protected $formType;
    
    protected $formOptions;
    
    /**
     * @param string                    $fieldName      Field name
     * @param string                    $filterType     Filter type
     * @param string|FormTypeInterface  $formType       Filter form type.
     * @param array                     $formOptions    Filter form options.
     */
    public function __construct($fieldName, $filterType, $formType, array $formOptions = array())
    {
        $this->fieldName    = $fieldName;
        $this->filterType   = $filterType;
        $this->formType     = $formType;
        $this->formOptions  = $formOptions;
    }
    
    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }
    
    /**
     * @return string
     */
    public function getFilterType()
    {
        return $this->filterType;
    }
    
    /**
     * @return string|FormTypeInterface
     */
    public function getFormType()
    {
        return $this->formType;
    }
    
    /**
     * @return array
     */
    public function getFormOptions()
    {
        return array_merge($this->formOptions, array('required' => true));
    }
}
