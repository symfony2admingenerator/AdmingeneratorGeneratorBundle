<?php

namespace Admingenerator\GeneratorBundle\Twig\Extension;

use Symfony\Component\Form\Extension\Core\DataTransformer\MoneyToLocalizedStringTransformer;

class LocalizedMoneyExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'currency' => new \Twig_Filter_Method($this, 'currency'),
        );
    }

    /**
     * @param float  $value     Money amount
     * @param string $options   Money options
     *
     * @return string Localized money
     */
    public function currency($value, $options)
    {
        $format = new \NumberFormatter('en', \NumberFormatter::CURRENCY);
        $pattern = $format->formatCurrency('123', $options['currency']);
        
        $dt = new MoneyToLocalizedStringTransformer($options['precision'], $options['grouping'], null, $options['divisor']);
        $transformed_value = $dt->transform($value);
        
        preg_match('/^([^\s\xc2\xa0]*)[\s\xc2\xa0]*123(?:[,.]0+)?[\s\xc2\xa0]*([^\s\xc2\xa0]*)$/u', $pattern, $matches);
        
        if (!empty($matches[1])) {
            $localized_money = $transformed_value.' '.$matches[1];
        } elseif (!empty($matches[2])) {
            $localized_money = $transformed_value.' '.$matches[2];
        } else {
            $localized_money = $transformed_value;
        }
        
        return $localized_money;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'localized_money';
    }
}
