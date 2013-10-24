# Extending Generator Templates
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#7-cookbook

Sometimes, you may want to extend/overwrite some of the generator templates in the Resources/templates dir. This is quite easy, but you will have to do some steps

1. First, you will need to add the template you will be using to the admingenerator config
```yaml
# config.yml
admingenerator_generator:
  templates_dirs: [ "%kernel.root_dir%/../app/Resources/AdmingeneratorGeneratorBundle/templates" ]
```

2. Keep in mind that you will at least need **one dir** in the previous specified template directory, namely of the DBAL layer used, so one of [Doctrine, DoctrineODM, Propel]. 
Without this directory, the specified template directory will not be used for extending/overwriting any of the templates, even in the CommonAdmin dir. 

3. Be free to extend/overwrite any template in the Resources/templates dir of the AdminGenerator!

Please note that your own templates might need adjustment when new releases of the AdminGenerator arrive. So if anything breaks after a update and you're using custom templates, please check those first!