<?php

namespace Admingenerator\GeneratorBundle\Tests\Twig\Extension;

use Admingenerator\GeneratorBundle\Tests\TestCase;

use Admingenerator\GeneratorBundle\Twig\Extension\EchoExtension;

use Symfony\Component\Templating\TemplateNameParser;

use Symfony\Component\Config\FileLocator;

use Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator;

use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;

/**
 * This class test the Admingenerator\GeneratorBundle\Twig\Extension\EchoExtension
 *
 * @author Cedric LOMBARDOT
 */
class EchoExtensionTest extends TestCase
{
    protected static $params;

    public function setUp()
    {
        $object =  new Object();

        self::$params = array(
            'name' => 'cedric',
            'obj'  => $object,
            'arr'  => array('obj' => 'val'),
            'arr_obj' => array('obj' => $object),
            'options_form_collection_type_class' => "array( 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false, 'type' => '\\\Admingenerator\\\PropelDemoBundle\\\Form\\\Type\\\ActorType',)",
            'options_form_collection_type_name' => "array( 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false, 'type' => 'my_formType_name',)",
            'options_form_model' => "array( 'query' => '\\\Admingenerator\\\PropelDemoBundle\\\Model\\\ActorQuery::create()->orderById()',)",
            'options_form_choice_method' => "array( 'choices' => '\\\Admingenerator\\\PropelDemoBundle\\\Model\\\ActorQuery::getMyCustoms()',)",
            'options_form_choice_array' => "array( 'choices' => array('a' => 'b'),)",

        );
    }

    public function testConvertAsForm()
    {

        $tpls = array(
            'options_form_collection_type_class' => '{{ options_form_collection_type_class|convert_as_form("collection") }}',
            'options_form_collection_type_name' => '{{ options_form_collection_type_name|convert_as_form("collection") }}',
            'options_form_model' => '{{ options_form_model|convert_as_form("model") }}',
            'options_form_choice_method' => '{{ options_form_choice_method|convert_as_form("choice") }}',
            'options_form_choice_array' => '{{ options_form_choice_array|convert_as_form("choice") }}',
        );

        $returns = array(
            'options_form_collection_type_class' => array("array( 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false, 'type' =>  new \Admingenerator\PropelDemoBundle\Form\Type\ActorType(),)", 'convert as form can convert the type option for the collection type'),
            'options_form_collection_type_name' => array("array( 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false, 'type' => 'my_formType_name',)", 'convert as form can convert the type option for the collection type'),
            'options_form_model' => array("array( 'query' => \Admingenerator\PropelDemoBundle\Model\ActorQuery::create()->orderById(),)", 'convert as form can convert the query option for the model type'),
            'options_form_choice_method' => array("array( 'choices' => \Admingenerator\PropelDemoBundle\Model\ActorQuery::getMyCustoms(),)", 'convert as form can convert the choices option for the choice type'),
            'options_form_choice_array' => array("array( 'choices' => array('a' => 'b'),)", 'convert as form can convert the choices option for the choice type'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testAsPhp()
    {
        $tpls = array(
            'string' => '{{ "cedric"|as_php }}',
            'array' => '{{ arr|as_php }}',
        );

        $returns = array(
            'string' => array("'cedric'", 'As php dump well the string'),
            'array' => array("array(  'obj' => 'val',)", 'As php dump well the array'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoTrans()
    {
        $tpls = array(
            'string' => '{{ echo_trans( "foo" ) }}',
            'variable_key' => '{{ echo_trans( name ) }}',
        );

        $returns = array(
             'string' => array('{% trans from "Admingenerator" %}foo{% endtrans %}', 'trans return a good trans tag with string elements'),
             'variable_key' => array('{% trans from "Admingenerator" %}cedric{% endtrans %}', 'trans return a good trans tag with variable as key'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoTransWithParameters()
    {
        $tpls = array(
            'string' => "{{ echo_trans('Display all <b>%foo% %bar%</b> results',{ 'foo': 'foo', 'bar': 'bar' }) }}",
            'variable_key' => '{{ echo_trans( name,{ \'foo\': \'foo\', \'bar\': \'bar\' } ) }}',
        );

        $returns = array(
             'string' => array('{% trans with {\'%foo%\': \'foo\',\'%bar%\': \'bar\',} from "Admingenerator" %}Display all <b>%foo% %bar%</b> results{% endtrans %}', 'trans return a good trans tag with string elements'),
             'variable_key' => array('{% trans with {\'%foo%\': \'foo\',\'%bar%\': \'bar\',} from "Admingenerator" %}cedric{% endtrans %}', 'trans return a good trans tag with variable as key'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoTransWithParameterBag()
    {
        $tpls = array(
            'string_bc' => "{{ echo_trans('You\'re editing {{ Book.title }} written by {{ Book.author.name }}!') }}",
            'string_with_full_param_bag' => "{{ echo_trans('You\'re editing %book% written by %author%!|{ %book%: Book.title, %author%: Book.author.name }|') }}",
            'string_with_abbrev_param_bag' => "{{ echo_trans('You\'re editing %Book.title% written by %Book.author.name%!|{ Book.title, Book.author.name }|') }}",
            'string_with_full_param_bag_and_params' => "{{ echo_trans('You\'re editing %book% written by %foo%!|{ %book%: Book.title }|',{ 'foo': 'foo' }) }}",
            'string_with_abbrev_param_bag_and_params' => "{{ echo_trans('You\'re editing %Book.title% written by %foo%!|{ Book.title }|',{ 'foo': 'foo' }) }}",
        );

        $returns = array(
            'string_bc' => array('{% trans with {\'%Book.title%\': Book.title,\'%Book.author.name%\': Book.author.name,} from "Admingenerator" %}You\'re editing %Book.title% written by %Book.author.name%!{% endtrans %}', 'trans return a good trans tag with string elements'),
            'string_with_full_param_bag' => array('{% trans with {\'%book%\': Book.title,\'%author%\': Book.author.name,} from "Admingenerator" %}You\'re editing %book% written by %author%!{% endtrans %}', 'trans return a good trans tag with string elements'),
            'string_with_abbrev_param_bag' => array('{% trans with {\'%Book.title%\': Book.title,\'%Book.author.name%\': Book.author.name,} from "Admingenerator" %}You\'re editing %Book.title% written by %Book.author.name%!{% endtrans %}', 'trans return a good trans tag with string elements'),
            'string_with_full_param_bag_and_params' => array('{% trans with {\'%foo%\': \'foo\',\'%book%\': Book.title,} from "Admingenerator" %}You\'re editing %book% written by %foo%!{% endtrans %}', 'trans return a good trans tag with string elements'),
            'string_with_abbrev_param_bag_and_params' => array('{% trans with {\'%foo%\': \'foo\',\'%Book.title%\': Book.title,} from "Admingenerator" %}You\'re editing %Book.title% written by %foo%!{% endtrans %}', 'trans return a good trans tag with string elements'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoSet()
    {
        $tpls = array(
            'string' => '{{ echo_set( "foo" , "bar" ) }}',
            'variable_key' => '{{ echo_set( name, "bar" ) }}',
            'variable_value' => '{{ echo_set( "foo", name ) }}',
            'array_key' => '{{ echo_set( arr.obj , "bar" ) }}',
            'array_value' => '{{ echo_set( "foo" , arr.obj ) }}',
            'not_value_as_string' => '{{ echo_set( "foo" , "bar", false ) }}'
        );

        $returns = array(
             'string' => array('{% set foo = "bar" %}', 'Set return a good set tag with string elements'),
             'variable_key' => array('{% set cedric = "bar" %}', 'Set return a good set tag with variable as key'),
             'variable_value' => array('{% set foo = "cedric" %}', 'Set return a good set tag with variable as value'),
             'array_key' => array('{% set val = "bar" %}', 'Set return a good set tag with array element as key'),
             'array_value' => array('{% set foo = "val" %}', 'Set return a good set tag with array element as value'),
             'not_value_as_string' => array('{% set foo = bar %}', 'Set return a good set tag with false for option value_as_string'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoPath()
    {
        $tpls = array(
            'string' => '{{ echo_path( "foo" ) }}',
            'variable' => '{{ echo_path( name ) }}',
            'array' => '{{ echo_path( arr.obj ) }}',
            'string_filtered' => '{{ echo_path( "foo", null, ["foo", "bar"] ) }}',
            'variable_filtered' => '{{ echo_path( name, null, ["foo", "bar"] ) }}',
            'array_filtered' => '{{ echo_path( arr.obj, null, ["foo", "bar"] ) }}',
        );

        $returns = array(
             'string' => array('{{ path("foo") }}', 'Path return a good Path tag with string elements'),
             'variable' => array('{{ path("cedric") }}', 'Path return a good Path tag with variable'),
             'array' => array('{{ path("val") }}', 'Path return a good Path tag with array element'),
             'string_filtered' => array('{{ path("foo")|foo|bar }}', 'Path return a good Path tag with string elements and filters'),
             'variable_filtered' => array('{{ path("cedric")|foo|bar }}', 'Path return a good Path tag with variable and filters'),
             'array_filtered' => array('{{ path("val")|foo|bar }}', 'Path return a good Path tag with array element and filters'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoIf()
    {
        $tpls = array(
            'string' => '{{ echo_if ( "a = b" ) }}',
            'variable' => '{{ echo_if ( name ~ " = \'cedric\'" ) }}',
            'array' => '{{ echo_if ( arr.obj ) }}',
            'boolean_true' => '{{ echo_if ( true ) }}',
            'boolean_false' => '{{ echo_if ( false ) }}',
        );

        $returns = array(
             'string' => array('{% if a = b %}', 'If return a good If tag with string elements'),
             'variable' => array('{% if cedric = \'cedric\' %}', 'If return a good If tag with variable'),
             'array' => array('{% if val %}', 'If return a good If tag with array element'),
             'boolean_true' => array('{% if 1 %}', 'If return a good If tag with boolean true variable'),
             'boolean_false' => array('{% if 0 %}', 'If return a good If tag with boolean false variable'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoIfGranted()
    {
        $tpls = array(
            'simple'  => '{{ echo_if_granted ( "hasRole(\'ROLE_A\')" ) }}',
            'complex' => '{{ echo_if_granted ( "hasRole(\'ROLE_A\')\') or (hasRole(\'ROLE_B\') and hasRole(\'ROLE_C\')" ) }}',
            'with_object' => '{{ echo_if_granted ( "hasRole(\'ROLE_A\')", \'modelName\' ) }}',
        );

        $returns = array(
            'simple'  => array('{% if is_expr_granted(\'hasRole(\'ROLE_A\')\') %}', 'If granted work with a simple role'),
            'complex' => array('{% if is_expr_granted(\'hasRole(\'ROLE_A\')\') or (hasRole(\'ROLE_B\') and hasRole(\'ROLE_C\')\') %}', 'If granted work with a complex role expression'),
            'with_object' => array('{% if is_expr_granted(\'hasRole(\'ROLE_A\')\', modelName) %}', 'If granted work with an object'),
        );

        $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoElseIf()
    {
        $tpls = array(
            'string' => '{{ echo_elseif ( "a = b" ) }}',
            'variable' => '{{ echo_elseif ( name ~ " = \'cedric\'" ) }}',
            'array' => '{{ echo_elseif ( arr.obj ) }}',
            'boolean_true' => '{{ echo_elseif ( true ) }}',
            'boolean_false' => '{{ echo_elseif ( false ) }}',
        );

        $returns = array(
             'string' => array('{% elseif a = b %}', 'Else If return a good Else If tag with string elements'),
             'variable' => array('{% elseif cedric = \'cedric\' %}', 'Else If return a good Else If tag with variable'),
             'array' => array('{% elseif val %}', 'Else If return a good Else If tag with array element'),
             'boolean_true' => array('{% elseif 1 %}', 'Else If return a good Else If tag with boolean true variable'),
             'boolean_false' => array('{% elseif 0 %}', 'Else If return a good Else If tag with boolean false variable'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoElse()
    {
        $tpls = array(
            'empty' => '{{ echo_else() }}',
        );

        $returns = array(
             'empty' => array('{% else %}', 'Else return a good Else tag'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoEndIf()
    {
        $tpls = array(
            'empty' => '{{ echo_endif () }}',
        );

        $returns = array(
             'empty' => array('{% endif %}', 'EndIf return a good EndIf tag'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoTwig()
    {
        $tpls = array(
            'string' => '{{ echo_twig( "cedric" ) }}',
            'variable' => '{{ echo_twig( name ~ ".cedric" ) }}',
            'array' => '{{ echo_twig( arr.obj ) }}',
        );

        $returns = array(
             'string' => array('{{ cedric }}', 'echo return a good echo tag with string elements'),
             'variable' => array('{{ cedric.cedric }}', 'echo return a good echo tag with variable'),
             'array' => array('{{ val }}', 'echo return a good echo tag with array element'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoBlock()
    {
        $tpls = array(
            'string' => '{{ echo_block( "cedric" ) }}',
            'variable' => '{{ echo_block( name ~ "_cedric" ) }}',
            'array' => '{{ echo_block( arr.obj ) }}',
        );

        $returns = array(
             'string' => array('{% block cedric %}', 'EchoBlock return a good block tag with string elements'),
             'variable' => array('{% block cedric_cedric %}', 'EchoBlock return a good block tag with variable'),
             'array' => array('{% block val %}', 'EchoBlock return a good echo block with array element'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoEndBlock()
    {
        $tpls = array(
            'empty' => '{{ echo_endblock() }}',
        );

        $returns = array(
             'empty' => array('{% endblock  %}', 'endblock return a good endblock tag'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoExtends()
    {
        $tpls = array(
            'string' => '{{ echo_extends( "cedric" ) }}',
            'variable' => '{{ echo_extends( name ~ "_cedric" ) }}',
            'array' => '{{ echo_extends( arr.obj ) }}',
        );

        $returns = array(
             'string' => array('{% extends "cedric" %}', 'Extends return a good Extends tag with string elements'),
             'variable' => array('{% extends "cedric_cedric" %}', 'Extends return a good Extends tag with variable'),
             'array' => array('{% extends "val" %}', 'Extends return a good Extends with array element'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoFor()
    {
        $tpls = array(
            'string' => '{{ echo_for( "foo" , "bar" ) }}',
            'variable_key' => '{{ echo_for( name, "bar" ) }}',
            'variable_value' => '{{ echo_for( "foo", name ) }}',
            'array_key' => '{{ echo_for( arr.obj , "bar" ) }}',
            'array_value' => '{{ echo_for( "foo" , arr.obj ) }}',
        );

        $returns = array(
             'string' => array('{% for foo in bar %}', 'for return a good for tag with string elements'),
             'variable_key' => array('{% for cedric in bar %}', 'for return a good for tag with variable as key'),
             'variable_value' => array('{% for foo in cedric %}', 'for return a good for tag with variable as value'),
             'array_key' => array('{% for val in bar %}', 'for return a good for tag with array element as key'),
             'array_value' => array('{% for foo in val %}', 'for return a good for tag with array element as value'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoEndFor()
    {
        $tpls = array(
            'empty' => '{{ echo_endfor() }}',
        );

        $returns = array(
             'empty' => array('{% endfor %}', 'endfor return a good endfor tag'),
        );

       $this->runTwigTests($tpls, $returns);
    }

    public function testGetTwigAssoc()
    {
        $tpls = array(
            'single_value' => "{{ echo_twig_assoc({a: '1'}) }}",
            'several_values' => "{{ echo_twig_assoc({alpha: 'abcde', beta: '12345'}) }}",
            'incomplete_variables' => "{{ echo_twig_assoc({a:'{{ Item.id '}) }}",
            'complete_valiables' => "{{ echo_twig_assoc({a:'{{ Item.id }}'}) }}",
            'mixed_values' => "{{ echo_twig_assoc({a:'{{ Item.id }}', b:'Item.id }}', c: 'abcde'}) }}",
        );

        $returns = array(
            'single_value' => array("{ a: '1' }", 'Append a single hardcoded value to the route'),
            'several_values' => array("{ alpha: 'abcde', beta: '12345' }", 'Several values are appended correctly'),
            'incomplete_variables' => array("{ a: '{{ Item.id ' }", 'Variables with unclosed {{ }} are quoted'),
            'complete_valiables' => array("{ a: Item.id }", 'Variables are passed w/o surrounding {{ }}'),
            'mixed_values' => array("{ a: Item.id, b: 'Item.id }}', c: 'abcde' }", 'Several values in the same expression'),
        );

        $this->runTwigTests($tpls, $returns);
    }

    public function testGetEchoTwigFilter()
    {
        $tpls = array(
            'none'   => '{{ echo_twig_filter( "cedric" ) }}',
            'object_string' => '{{ echo_twig_filter( "cedric", "foo" ) }}',
            'object_array'  => '{{ echo_twig_filter( "cedric", ["foo", "bar"] ) }}',
            'string_string' => '{{ echo_twig_filter( "cedric", "foo", true ) }}',
            'string_array'  => '{{ echo_twig_filter( "cedric", ["foo", "bar"], true ) }}',
        );

        $returns = array(
             'none'   => array('{{ cedric }}', 'echo return a good echo tag with no filters'),
             'object_string' => array('{{ cedric|foo }}', 'echo return a good object echo tag one filter'),
             'object_array'  => array('{{ cedric|foo|bar }}', 'echo return a good object echo tag with multiple filters'),
             'string_string' => array('{{ "cedric"|foo }}', 'echo return a good string echo tag one filter'),
             'string_array'  => array('{{ "cedric"|foo|bar }}', 'echo return a good string echo tag with multiple filters'),
        );

        $this->runTwigTests($tpls, $returns);
    }

    protected function runTwigTests($tpls, $returns)
    {
        $twig = $this->getEnvironment(false, array(), $tpls);

        foreach ($tpls as $name => $tpl) {
            $this->assertEquals($returns[$name][0],
            $twig->loadTemplate($name)
                            ->render(self::$params),
                            $returns[$name][1]);
        }
    }

    protected function getEnvironment($sandboxed, $options, $templates, $tags = array(), $filters = array(), $methods = array(), $properties = array(), $functions = array())
    {
        $loader = new \Twig_Loader_Array($templates);
        $twig = new \Twig_Environment($loader, array_merge(array('debug' => true, 'cache' => false, 'autoescape' => false), $options));

        $locator = new TemplateLocator(new FileLocator(array(__DIR__.'/../Fixtures')));
        $templateNameParser = new TemplateNameParser();
        $loader = new FilesystemLoader($locator, $templateNameParser);

        $twig->addExtension(new EchoExtension($loader));

        return $twig;
    }
}

class Object
{
    public static $called = array('__toString' => 0, 'foo' => 0, 'getFooBar' => 0);

    public function __construct($bar ='bar')
    {

    }

    public static function reset()
    {
        self::$called = array('__toString' => 0, 'foo' => 0, 'getFooBar' => 0);
    }

    public function __toString()
    {
        ++self::$called['__toString'];

        return 'foo';
    }

    public function foo()
    {
        ++self::$called['foo'];

        return 'foo';
    }

    public function getFooBar()
    {
        ++self::$called['getFooBar'];

        return 'foobar';
    }
}
