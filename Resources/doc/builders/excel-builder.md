# Excel builder
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#4-generator

### 1. Usage
The Excel builder provides an Excel export of the list view. The export uses the data that is visible on the screen (and the next pages) and thus uses the filters and scopes selected.

To use the Excel export make sure you have installes the recommended dependency `liuggio/excelbundle` and enabled it in your AppKernel.php (`new Liuggio\ExcelBundle\LiuggioExcelBundle()`). Without this bundle enabled the ExcelAction will not work.

### 2. Options

* `display`: The same as in list/new/show/edit, selects the columns to export
* `filename`: Specify the export filename. When null 'admin_export_{list title}' is used
* `filetype`: Default Excel2007. See the [excelbundle documention](https://github.com/liuggio/excelbundle#not-only-excel5) for the possible options.