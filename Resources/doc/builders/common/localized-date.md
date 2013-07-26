# Localized date
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/builders/list

Admingenerator comes with a few configuration options allowing you to control 
the outputted date format. 

```yaml
admingenerator_generator:
    twig:
        use_localized_date:           false
    # by default the localized date is disabled thats why default settings 
    # use PHP date()'s format if you enable localized date make sure to change 
    # these to ISO 8601 compatible format!
        date_format:                  "Y-m-d"         # for ISO 8601 "yyyy-MM-dd"
        datetime_format:              "Y-m-d H:i:s"   # for ISO 8601 "yyyy-MM-dd HH:mm:ss"
        localized_date_format:        "medium"
        localized_datetime_format:    "medium"
```

> **Note:** these apply only to `list` and `show` builders. These settings have no effect on
`edit` and `new` forms.

> **WARNING!** Internally localized date is handled by [IntlDateFormatter class][intl-date-formatter], 
which uses [ISO 8601][iso-8601] formats instead of PHP date()'s formats. Make sure to set the correct
`date_format` and `datetime_format` if you enable localized date!

[intl-date-formatter]: http://www.php.net/manual/en/intldateformatter.format.php
[iso-8601]: http://framework.zend.com/manual/1.12/en/zend.date.constants.html#zend.date.constants.selfdefinedformats

### Description

##### date fields

If `use_localized_date` is enabled, the date field will be rendered as:

```html+django
{{ my_date|localizeddate(localized_date_format, "none", null, null, date_format) }}
```

Otherwise the date field will be rendered as:

```html+django
{{ my_date|date(date_format) }}
```

Where `date_format` is equal to `format` option for that field (if defined) or will 
fallback to `admingenerator_generator.twig.date_format` setting.

##### datetime fields

If `use_localized_date` is enabled, the datetime field will be rendered as:

```html+django
{{ my_date|localizeddate(localized_datetime_format, localized_datetime_format, null, null, datetime_format) }}
```

Otherwise the date field will be rendered as:

```html+django
{{ my_date|date(date_format) }}
```

Where `datetime_format` is equal to `format` option for that field (if defined) or will 
fallback to `admingenerator_generator.twig.datetime_format` setting.

### Options

##### use_localized_date

**type**: `bool`, **default**: `false`

If true, enables transforming dates into their localized versions.

##### date_format

**type**: `string`, **default**: `Y-m-d`

If `use_localized_date` is false, use formats for PHP's `date()` function, otherwise use 
[ISO 8601][iso-8601] formats.

##### datetime_format

**type**: `string`, **default**: `Y-m-d H:i:s`

If `use_localized_date` is false, use formats for PHP's `date()` function, otherwise use 
[ISO 8601][iso-8601] formats.

##### localized_date_format

**type**: `string`, **default**: `medium`

Internally, we're useing [Twig Intl Extension][twig-intl-ext]. Possible options are:

```php
<?php
array(
  'none'    => IntlDateFormatter::NONE,
  'short'   => IntlDateFormatter::SHORT,
  'medium'  => IntlDateFormatter::MEDIUM,
  'long'    => IntlDateFormatter::LONG,
  'full'    => IntlDateFormatter::FULL,
)
```

[twig-intl-ext]: https://github.com/fabpot/Twig-extensions/blob/master/lib/Twig/Extensions/Extension/Intl.php

##### localized_datetime_format

**type**: `string`, **default**: `medium`

Internally, we're useing [Twig Intl Extension][twig-intl-ext]. Possible options are:

```php
<?php
array(
  'none'    => IntlDateFormatter::NONE,
  'short'   => IntlDateFormatter::SHORT,
  'medium'  => IntlDateFormatter::MEDIUM,
  'long'    => IntlDateFormatter::LONG,
  'full'    => IntlDateFormatter::FULL,
)
```
