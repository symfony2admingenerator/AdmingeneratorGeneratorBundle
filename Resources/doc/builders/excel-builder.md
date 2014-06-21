# Excel builder
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#4-generator

### Requirements

To use the Excel export make sure you have installed the recommended dependency `liuggio/excelbundle` (version `>= 2.0`) and enabled it in your AppKernel.php (`new Liuggio\ExcelBundle\LiuggioExcelBundle()`). Without this bundle enabled the ExcelAction will not work.

### 1. Usage
The Excel builder provides an Excel export of the list view. The export uses the data that is visible on the screen (and the next pages) and thus uses the filters and scopes selected. You can use object associations by just entering a dot.

### 2. Options

* `display`: The same as in list/new/show/edit, selects the columns to export
* `filename`: Specify the export filename. When null 'admin_export_{list title}' is used
* `filetype`: Default Excel2007. See the [excelbundle documention](https://github.com/liuggio/excelbundle#not-only-excel5) for the possible options.
* `datetime_format`: Specify the DateTime format to be used in the Excel export. Default is `Y-m-d H:i:s`.

### 3. Header titles in Excel

The header titles are fully customizable just as every other block. Just use the `fields` key directly under the builder. This even works for associated fields like `person.fullname`.
