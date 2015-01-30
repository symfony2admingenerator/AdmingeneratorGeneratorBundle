# List builder
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#4-generator

Sorry, this has not yet been completely rewritten.

List builder features:

* [Title](common/title.md)
* [Localized date](common/localized-date.md)

### Filters

It's now possible to filter by multiple values for a single field.
Just set your ```formType``` for that field as ```choice``` and then add the ```multiple``` option with ```true``` as value. As example:
```
filters:
    params:
        fields:
            occurrenceWeek:
                formType: choice
                formOptions:
                    required: false
                    multiple: true
                    choices: '\Your\AwesomeBundle\YourAwesomeBundle::getYearWeeks()'
```
