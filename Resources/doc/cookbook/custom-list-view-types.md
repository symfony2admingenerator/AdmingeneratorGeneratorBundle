# Custom List view types
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#7-cookbook

Sometimes, you might want to change the way the list view of a specific entity field is rendered. This can be done with a few simple steps!

1. Make sure you know how to overwrite the original templates of the AdminGenerator. See [Extending generator templates][cookbook-9]. 
[cookbook-9]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/cookbook/extending-generator-templates.md

2. In the generator.yml of the entity of choice, add the option `customListView: %viewname%` for the wanted field 
```yaml
# Example
params: 
  fields:
    gender:
      customListView: gender # %viewname%
```

3. Create the custom columm definition by overwriting Resources/templates/CommonAdmin/ListTemplate/Column/customListViewColumns.php.twig. This file now contains an example. 
The blockname must be `column_%viewname%`

Now you can make your own templates which will be rendered only in the listview!