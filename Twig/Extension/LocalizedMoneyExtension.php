<?php

namespace Admingenerator\GeneratorBundle\Twig\Extension;

use Symfony\Component\Form\Extension\Core\DataTransformer\MoneyToLocalizedStringTransformer;

class LocalizedMoneyExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            'localized_money' => new \Twig_Function_Method($this, 'getLocalizedMoney'),
            'currency_sign'   => new \Twig_Function_Method($this, 'getCurrencySign'),
        );
    }

    /**
     * @param float  $value       Money amount.
     * 
     * @param string $currency    This can be any 3 letter ISO 4217 code. You 
     * can also set this to false to hide the currency symbol.
     * 
     * @param string $precision   For some reason, if you need some precision 
     * other than 2 decimal places, you can modify this value. You probably 
     * won't need to do this unless, for example, you want to round to the 
     * nearest dollar (set the precision to 0).
     * 
     * @param string $grouping    This value is used internally as the 
     * NumberFormatter::GROUPING_USED value when using PHP's NumberFormatter 
     * class. Its documentation is non-existent, but it appears that if you set 
     * this to true, numbers will be grouped with a comma or period (depending 
     * on your locale): 12345.123 would display as 12,345.123.
     * 
     * @param string $divisor     If, for some reason, you need to divide your 
     * starting value by a number before rendering it to the user, you can use 
     * the divisor option.
     *
     * @return string Localized money
     */
    public function getLocalizedMoney($value, $currency = 'EUR', $precision = 2, $grouping = true, $divisor = 1)
    {
        $locale = \Locale::getDefault();
        
        $format = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        $pattern = $format->formatCurrency('123', $currency);
        
        $dt = new MoneyToLocalizedStringTransformer($precision, $grouping, null, $divisor);
        $transformed_value = $dt->transform($value);
        
        preg_match('/^([^\s\xc2\xa0]*)[\s\xc2\xa0]*123(?:[,.]0+)?[\s\xc2\xa0]*([^\s\xc2\xa0]*)$/u', $pattern, $matches);
        
        if (!empty($matches[1])) {
            $localized_money = $matches[1].' '.$transformed_value;
        } elseif (!empty($matches[2])) {
            $localized_money = $transformed_value.' '.$matches[2];
        } else {
            $localized_money = $transformed_value;
        }
        
        return $localized_money;
    }

    /**
     * @param string $currency    This can be any 3 letter ISO 4217 code. You 
     * can also set this to false to return the general currency symbol.
     *
     * @return string Currency sign
     */
    public function getCurrencySign($currency = false)
    {
        $locale = \Locale::getDefault();
        
        $format = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        $pattern = $format->formatCurrency('123', $currency);
        
        preg_match('/^([^\s\xc2\xa0]*)[\s\xc2\xa0]*123(?:[,.]0+)?[\s\xc2\xa0]*([^\s\xc2\xa0]*)$/u', $pattern, $matches);
        
        if (!empty($matches[1])) {
            $currency_sign = $matches[1];
        } elseif (!empty($matches[2])) {
            $currency_sign = $matches[2];
        } else {
            $currency_sign = '¤';
        }
        
        return $currency_sign;
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
