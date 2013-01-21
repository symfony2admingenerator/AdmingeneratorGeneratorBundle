<?php

namespace Admingenerator\GeneratorBundle\Command;

use Admingenerator\GeneratorBundle\Routing\Manipulator\RoutingManipulator;

use Admingenerator\GeneratorBundle\Generator\BundleGenerator;

use Sensio\Bundle\GeneratorBundle\Command\GenerateBundleCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sensio\Bundle\GeneratorBundle\Command\Validators;

class GenerateAdminAdminCommand extends GenerateBundleCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:generate-admin')
            ->setDescription('Generate admin classes into an existant bundle')
            ->setDefinition(array(
                new InputOption('namespace', '', InputOption::VALUE_REQUIRED, 'The namespace of the bundle to create'),
                new InputOption('dir', '', InputOption::VALUE_REQUIRED, 'The directory where to create the bundle'),
                new InputOption('bundle-name', '', InputOption::VALUE_REQUIRED, 'The optional bundle name'),
                new InputOption('structure', '', InputOption::VALUE_NONE, 'Whether to generate the whole directory structure'),
                new InputOption('format', '', InputOption::VALUE_REQUIRED, 'Do nothing but mandatory for extend', 'annotation'),
                new InputOption('generator', '', InputOption::VALUE_REQUIRED, 'The generator service (propel, doctrine, doctrine_odm)', 'doctrine'),
                new InputOption('model-name', '', InputOption::VALUE_REQUIRED, 'Base model name for admin module, without namespace.', 'YourModel'),
                new InputOption('prefix', '', InputOption::VALUE_REQUIRED, 'The generator prefix ([prefix]-generator.yml)'),

            ))
            ->setHelp(<<<EOT
The <info>admin:generate-admin</info> command helps you generates new admin controllers into an existant bundle.
EOT
            )
        ;
    }
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getDialogHelper();
        $dialog->writeSection($output, 'Welcome to the Symfony2 admin generator');
        $output->writeln('<comment>Create controllers for a generator module</comment>');

        $generator = $dialog->askAndValidate($output,
          $dialog->getQuestion('Generator to use (doctrine, doctrine_odm, propel)', $input->getOption('generator')),
          function ($generator) {
            if (!in_array($generator, array('doctrine','doctrine_odm','propel'))) {
              throw new \RuntimeException('Generator to use have to be doctrine, doctrine_odm or propel');
            }
            return $generator;
          }, false, $input->getOption('generator')
        );
        $input->setOption('generator', $generator);

        $namespace = $dialog->askAndValidate($output,
          $dialog->getQuestion('Bundle namespace', $input->getOption('namespace')),
          array('Sensio\Bundle\GeneratorBundle\Command\Validators', 'validateBundleNamespace'),
          false, $input->getOption('namespace')
        );
        $input->setOption('namespace', $namespace);

        // Model name
        $modelName = $dialog->askAndValidate($output,
          $dialog->getQuestion('Model name', $input->getOption('model-name')),
          function($modelName) {
            if(empty($modelName) || preg_match('#[^a-zA-Z0-9]#', $modelName)) {
              throw new \RuntimeException('Model name should not contain any special characters nor spaces.');
            }
            return $modelName;
          }, false, $input->getOption('model-name')
        );
        $input->setOption('model-name', $modelName);

        // bundle name
        $bundle = $input->getOption('bundle-name') ?: strtr($namespace, array('\\Bundle\\' => '', '\\' => ''));
        $output->writeln(array(
            '',
            'In your code, a bundle is often referenced by its name. It can be the',
            'concatenation of all namespace parts but it\'s really up to you to come',
            'up with a unique name (a good practice is to start with the vendor name).',
            'Based on the namespace, we suggest <comment>'.$bundle.'</comment>.',
            '',
        ));
        $bundle = $dialog->askAndValidate($output, $dialog->getQuestion('Bundle name', $bundle), array('Sensio\Bundle\GeneratorBundle\Command\Validators', 'validateBundleName'), false, $bundle);
        $input->setOption('bundle-name', $bundle);

        // target dir
        $dir = $input->getOption('dir') ?: dirname($this->getContainer()->getParameter('kernel.root_dir')).'/src';
        $output->writeln(array(
            '',
            'The bundle can be generated anywhere. The suggested default directory uses',
            'the standard conventions.',
            '',
        ));
        $dir = $dialog->askAndValidate($output, $dialog->getQuestion('Target directory', $dir), function ($dir) use ($bundle, $namespace) { return Validators::validateTargetDir($dir, $bundle, $namespace); }, false, $dir);
        $input->setOption('dir', $dir);

        // prefix
        $prefix = $dialog->askAndValidate($output, $dialog->getQuestion('Prefix of yaml', $input->getOption('prefix')),  function ($prefix) { if (!preg_match('/([a-z]+)/i', $prefix)) { throw new \RuntimeException('Prefix have to be a simple word'); } return $prefix; } , false, $input->getOption('prefix'));
        $input->setOption('prefix', $prefix);
    }

     /**
     * @see Command
     *
     * @throws \InvalidArgumentException When namespace doesn't end with Bundle
     * @throws \RuntimeException         When bundle can't be executed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getDialogHelper();

        if ($input->isInteractive()) {
            if (!$dialog->askConfirmation($output, $dialog->getQuestion('Do you confirm generation', 'yes', '?'), true)) {
                $output->writeln('<error>Command aborted</error>');

                return 1;
            }
        }

        foreach (array('namespace', 'dir') as $option) {
            if (null === $input->getOption($option)) {
                throw new \RuntimeException(sprintf('The "%s" option must be provided.', $option));
            }
        }

        $namespace = Validators::validateBundleNamespace($input->getOption('namespace'));
        if (!$bundle = $input->getOption('bundle-name')) {
            $bundle = strtr($namespace, array('\\' => ''));
        }
        $bundle = Validators::validateBundleName($bundle);
        $format = Validators::validateFormat($input->getOption('format'));
        $dir = $input->getOption('dir').'/';
        $structure = $input->getOption('structure');

        $dialog->writeSection($output, 'Bundle generation');

        if (!$this->getContainer()->get('filesystem')->isAbsolutePath($dir)) {
            $dir = getcwd().'/'.$dir;
        }

        $generatorName = $input->getOption('generator');
        $modelName = $input->getOption('model-name');

        $generator = $this->getGenerator();
        $generator->setGenerator($generatorName);
        $generator->setPrefix($input->getOption('prefix'));
        $generator->generate($namespace, $bundle, $dir, $format, $structure, $generatorName, $modelName);

        $output->writeln('Generating the bundle code: <info>OK</info>');

        $errors = array();
        $runner = $dialog->getRunner($output, $errors);

        // routing
        $runner($this->updateRouting($dialog, $input, $output, $bundle, $format));

        $dialog->writeGeneratorSummary($output, $errors);
    }

    protected function getGenerator()
    {
        return new BundleGenerator($this->getContainer()->get('filesystem'), __DIR__.'/../Resources/skeleton/bundle');
    }

    protected function updateRouting($dialog, InputInterface $input, OutputInterface $output, $bundle, $format)
    {
        $auto = true;
        if ($input->isInteractive()) {
            $auto = $dialog->askConfirmation($output, $dialog->getQuestion('Confirm automatic update of the Routing', 'yes', '?'), true);
        }

        $output->write('Importing the bundle routing resource: ');
        $routing = new RoutingManipulator($this->getContainer()->getParameter('kernel.root_dir').'/config/routing.yml');
        $routing->setYamlPrefix($input->getOption('prefix'));

        try {
            $ret = $auto ? $routing->addResource($bundle, 'admingenerator') : false;
            if (!$ret) {
                $help = sprintf("        <comment>resource: \"@%s/Controller/%s/\"</comment>\n        <comment>type:     admingenerator</comment>\n", $bundle, ucfirst($input->getOption('prefix')));
                $help .= "        <comment>prefix:   /</comment>\n";

                return array(
                    '- Import the bundle\'s routing resource in the app main routing file:',
                    '',
                    sprintf('    <comment>%s:</comment>', $bundle),
                    $help,
                    '',
                );
            }
        } catch (\RuntimeException $e) {
            return array(
                sprintf('Bundle <comment>%s</comment> is already imported.', $bundle),
                '',
            );
        }
    }
}
