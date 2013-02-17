<?php

namespace Admingenerator\GeneratorBundle\Twig\Extension;

class EchoExtension extends \Twig_Extension
{
    protected $loader;
    protected $controller;

    public function __construct(\Twig_LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'echo_twig'       => new \Twig_Function_Method($this, 'getEchoTwig'),
            'echo_block'      => new \Twig_Function_Method($this, 'getEchoBlock'),
            'echo_endblock'   => new \Twig_Function_Method($this, 'getEchoEndBlock'),
            'echo_for'        => new \Twig_Function_Method($this, 'getEchoFor'),
            'echo_endfor'     => new \Twig_Function_Method($this, 'getEchoEndFor'),
            'echo_extends'    => new \Twig_Function_Method($this, 'getEchoExtends'),
            'echo_if'         => new \Twig_Function_Method($this, 'getEchoIf'),
            'echo_if_granted' => new \Twig_Function_Method($this, 'getEchoIfGranted'),
            'echo_else'       => new \Twig_Function_Method($this, 'getEchoElse'),
            'echo_elseif'     => new \Twig_Function_Method($this, 'getEchoElseIf'),
            'echo_endif'      => new \Twig_Function_Method($this, 'getEchoEndIf'),
            'echo_path'       => new \Twig_Function_Method($this, 'getEchoPath'),
            'echo_set'        => new \Twig_Function_Method($this, 'getEchoSet'),
            'echo_trans'      => new \Twig_Function_Method($this, 'getEchoTrans'),
            'echo_twig_assoc' => new \Twig_Function_Method($this, 'getEchoTwigAssoc'),
        );
    }

    public function getFilters()
    {
        return array(
            'as_php'          => new \Twig_Filter_Method($this, 'asPhp'),
            'convert_as_form' => new \Twig_Filter_Method($this, 'convertAsForm'),
        );
    }

    /**
     * Try to convert options of form given as string from yaml to a good object
     *
     * eg type option for collection type
     *
     * @param string $options  the string as php
     * @param string $formType the form type
     *
     * @return string the new options
     */
    public function convertAsForm($options, $formType)
    {
        $options = preg_replace("/'__php\((.+?)\)'/i", '$1', $options, -1, $count);
        
        if ('collection' == $formType || 'upload' == $formType) {
            preg_match("/'type' => '(.+?)'/i", $options, $matches);

            if (count($matches) > 0) {
                $pattern_formtype = '/^\\\\+(([a-zA-Z_]\w*\\\\+)*)([a-zA-Z_]\w*)$/';
                // Sanity check: prepend with "new" and append with "()"
                // only if type option is a Fully qualified name
                if(preg_match($pattern_formtype, $matches[1])) {
                  $options = str_replace("'type' => '".$matches[1]."'", '\'type\' =>  new '.stripslashes($matches[1]).'()', $options);
                }
            }
        }

        if ('model' == $formType) {
            preg_match("/'query' => '(.+?)',/i", $options, $matches);

            if (count($matches) > 0) {
                $options = str_replace("'query' => '".$matches[1]."'", '\'query\' => '.stripslashes($matches[1]), $options);
            }
        }

        if ('choice' == $formType || 'double_list' == $formType) {
            preg_match("/'choices' => '(.+?)',/i", $options, $matches);

            if (count($matches) > 0) {
                $options = str_replace("'choices' => '".$matches[1]."'", '\'choices\' => '.stripslashes($matches[1]), $options);
            }
        }

        if ('form_widget'== $formType) { // For type wich are not strings
            preg_match("/\'(.*)Type/", $options, $matches);

            if (count($matches) > 0) {
                return 'new '.stripslashes($matches[1]).'Type()';
            }
        }

        return $options;
    }

    public function asPhp($variable)
    {
       if (!is_array($variable)) {
           return $this->export($variable);
       }

       $str = $this->export($variable);

       preg_match_all('/[^> ]+::__set_state\(array\((.+),\'loaded/i', $str, $matches);

       if (isset($matches[1][0])) {
           $params = 'return array('.$matches[1][0].')';
           $params = eval($params. '?>');

           $str_param = '';
           foreach ($params as $p) {
               if ('' !== $str_param) {
                   $str_param .= ', ';
               }
               $str_param .= $this->export($p);
           }

           $str = preg_replace("/([^> ]+)::__set_state\(/i", ' new \\\$0', $str);
           $str = str_replace('::__set_state', '', $str);
           $str = str_replace('array('.$matches[1][0].',\'loaded\' => false,  )', $str_param, $str);
       }

       return $str;

    }

    public function export($variable)
    {
        return str_replace(array("\n", 'array (', '     '), array('', 'array(', ''), var_export($variable, true));
    }
    
    /**
     * Reads parameters from subject and removes parameter bag from string.
     * 
     * @return array
     *   [string] -> string for echo trans
     *   [params] -> parameters for echo trans
     * 
     * @return false if subject did not match any of following patterns
     * 
     * ##############################
     * Backwards compability pattern:
     * 
     * replaces twig tags {{ parameter_name }} with parameters.
     * 
     * example: You're editing {{ Book.title }} written by {{ Book.author.name }}!
     * 
     * results in:
     *   string -> You're editing %Book.title% written by %Book.author.name%!
     *   params -> 
     *     [Book.title] -> Book.title
     *     [Book.author.name] -> Book.author.name
     * 
     * ###################################
     * Feature - key-value syntax pattern:
     * |{ %param_key%: param_value, %param_key2%: param_value2, %param_key3%: param_value3 }|
     * 
     * where param_key and param_value consist of any number a-z, A-Z, 0-9 or . (dot) characters
     * 
     * example: You're editing %book% written by %author%!|{ %book%: Book.title, %author%: Book.author.name }|
     * results in:
     *   string -> You're editing %book% written by %author%!
     *   params ->
     *     [book] -> Book.title
     *     [author] -> Book.author.name
     * 
     * example: book.edit.title|{ %book%: Book.title, %author%: Book.author.name }|     * 
     * results in:
     *   string -> book.edit.title
     *   params ->
     *     [book] -> Book.title
     *     [author] -> Book.author.name
     * 
     * ###################################
     * Feature - abbreviated syntax pattern:
     * |{ param_value, param_value2, param_value3 }|
     * 
     * where param_value consists of any number a-z, A-Z, 0-9 or . (dot) characters
     * 
     * example: You're editing %Book.title% written by %Book.author.name%!|{ Book.title, Book.author.name }|
     * results in:
     *   string -> You're editing %Book.title% written by %Book.author.name%!
     *   params ->
     *     [Book.title] -> Book.title
     *     [Book.author.name] -> Book.author.name
     * 
     * example: book.edit.title|{ Book.title, Book.author.name }|
     * results in:
     *   string -> book.edit.title
     *   params ->
     *     [Book.title] -> Book.title
     *     [Book.author.name] -> Book.author.name
     */
    private function getParameterBag($subject) {      
      # Backwards compability - replace twig tags with parameters
      $pattern_bc = '/\{\{\s(?<param>[a-zA-Z0-9.]+)\s\}\}+/';
      
      if (preg_match_all($pattern_bc, $subject, $match_params)) {
        $string = preg_filter($pattern_bc, '%\1%', $subject);

        $param = array();
        foreach($match_params['param'] as $value) { $param[$value] = $value; }

        return array(
            'string' => $string,
            'params' => $param
        );
      }
      
      # Feature - read key/value syntax parameters
      $pattern_string = '/^(?<string>[^|]+)(?<parameter_bag>\|\{(\s?%[a-zA-Z0-9.]+%:\s[a-zA-Z0-9.]+,?\s?)+\s?\}\|)\s*$/';
      $pattern_params = '/(?>(?<=(\|\{\s|.,\s))%(?<key>[a-zA-Z0-9.]+)%:\s(?<value>[a-zA-Z0-9.]+)(?=(,\s.|\s\}\|)))+/';
      
      if ( preg_match($pattern_string, $subject, $match_string) ) {        
          $string = $match_string['string']; 
          $parameter_bag = $match_string['parameter_bag'];  

          $param = array();
          preg_match_all($pattern_params, $parameter_bag, $match_params, PREG_SET_ORDER);

          foreach($match_params as $match) { $param[$match['key']] = $match['value']; }

          return array(
              'string' => $string,
              'params' => $param
          );
      }
      
      # Feature - read abbreviated syntax parameters
      $abbreviated_pattern_string = '/^(?<string>[^|]+)(?<parameter_bag>\|\{(\s?[a-zA-Z0-9.]+,?\s?)+\s?\}\|)\s*$/';
      $abbreviated_pattern_params = '/(?>(?<=(\|\{\s|.,\s))(?<param>[a-zA-Z0-9.]+)(?=(,\s.|\s\}\|)))+?/';
      
      if ( preg_match($abbreviated_pattern_string, $subject, $match_string)) {
          $string = $match_string['string'];
          $parameter_bag = $match_string['parameter_bag'];

          $param = array();
          preg_match_all($abbreviated_pattern_params, $parameter_bag, $match_params);
          
          foreach($match_params['param'] as $value) { $param[$value] = $value; }

          return array(
              'string' => $string,
              'params' => $param
          );
      }
      
      # If subject does not match any pattern, return false
      return false;
    }
    
    public function getEchoTrans($str, array $parameters=array(), $catalog = 'Admingenerator')
    {        
        $echo_parameters=NULL;
        $bag_parameters=array();
        
        if($parameterBag = $this->getParameterBag($str)) {
            $str = $parameterBag['string'];
            $bag_parameters = $parameterBag['params'];
        }
      
        if (!empty($parameters) || !empty($bag_parameters)) {
            $echo_parameters="with {";
            
            foreach ($parameters as $key => $value) { 
              $echo_parameters.= "'%".$key."%': '".$value."',";
            }
            foreach ($bag_parameters as $key => $value) { 
              $echo_parameters.= "'%".$key."%': ".$value.",";
            }
            
            $echo_parameters.="} ";
        }

        return '{% trans '.$echo_parameters.'from "'.$catalog.'" %}'.$str.'{% endtrans %}';
    }

    public function getEchoSet($var, $value, $value_as_string = true)
    {
        if ($value_as_string) {
            return strtr('{% set %%var%% = "%%value%%" %}',array('%%var%%' => $var, '%%value%%' => $value));
        } else {
            return strtr('{% set %%var%% = %%value%% %}',array('%%var%%' => $var, '%%value%%' => $value));
        }
    }

    public function getEchopath($path, $params = null)
    {
        if (null === $params) {
            return strtr('{{ path("%%path%%") }}',array('%%path%%' => $path));
        }

        return strtr('{{ path("%%path%%", %%params%%) }}',array('%%path%%' => $path, '%%params%%'=>$params));
    }

    public function getEchoIfGranted($credentials, $modelName = null)
    {
       if (null === $modelName) {
            return $this->getEchoIf('is_expr_granted(\''.$credentials.'\')');
       }

       return $this->getEchoIf('is_expr_granted(\''.$credentials.'\', '.$modelName.')');
    }

    public function getEchoIf($condition)
    {
        if ( is_bool( $condition ) ) {
            $condition = intval( $condition );
        }

        return str_replace('%%condition%%', $condition, '{% if %%condition%% %}');
    }

    public function getEchoElseIf($condition)
    {
        if ( is_bool( $condition ) ) {
            $condition = intval( $condition );
        }

        return str_replace('%%condition%%', $condition, '{% elseif %%condition%% %}');
    }

    public function getEchoElse()
    {
        return '{% else %}';
    }

    public function getEchoEndIf()
    {
        return '{% endif %}';
    }

    public function getEchoTwig($str)
    {
        return sprintf('{{ %s }}', $str);
    }

    public function getEchoBlock($name)
    {
        return str_replace('%%name%%', $name, '{% block %%name%% %}');
    }

    public function getEchoExtends($name)
    {
        return str_replace('%%name%%', $name, '{% extends "%%name%%" %}');
    }

    public function getEchoEndBlock()
    {
        return '{% endblock %}';
    }

    public function getEchoFor($object, $in)
    {
        return strtr('{% for %%object%% in %%in%% %}', array('%%object%%' => $object, '%%in%%' => $in ));
    }

    public function getEchoEndFor()
    {
        return '{% endfor %}';
    }

    /**
     * Converts an assoc array to a twig array expression (string) .
     * Only in case a value contains '{{' and '}}' the value won't be
     * wrapped in quotes.
     *
     * An array like:
     * <code>
     * $array = array('a' => 'b', 'c' => 'd');
     * </code>
     *
     * Will be converted to:
     * <code>
     * "{ a: 'b', c: 'd' }"
     * </code>
     *
     * @return string The parameters to be used in a URL
     */
    public function getEchoTwigAssoc(array $arr)
    {
        $contents = array();
        foreach ($arr as $key => $value) {
            if (!strstr($value, '{{')
                || !strstr($value, '}}'))
            {
                $value = "'$value'";
            } else {
                $value = trim(str_replace(array('{{', '}}'), '', $value));
            }

            $contents[] = "$key: $value";
        }

        return '{ ' . implode(', ', $contents) . ' }';
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'echo';
    }
}
