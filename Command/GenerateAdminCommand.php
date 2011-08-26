<?php

namespace Admingenerator\GeneratorBundle\Command;


use Admingenerator\GeneratorBundle\Routing\Manipulator\RoutingManipulator;

use Admingenerator\GeneratorBundle\Generator\BundleGenerator;

use Sensio\Bundle\GeneratorBundle\Command\GenerateBundleCommand;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateAdminCommand extends GenerateBundleCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:generate-bundle')
            ->setDescription('Generate a new bundle with admin generated files')
            ->setDefinition(array(
                new InputOption('namespace', '', InputOption::VALUE_REQUIRED, 'The namespace of the bundle to create'),
                new InputOption('dir', '', InputOption::VALUE_REQUIRED, 'The directory where to create the bundle'),
                new InputOption('bundle-name', '', InputOption::VALUE_REQUIRED, 'The optional bundle name'),
                new InputOption('structure', '', InputOption::VALUE_NONE, 'Whether to generate the whole directory structure'),
                new InputOption('format', '', InputOption::VALUE_REQUIRED, 'Do nothing but mandatory for extend', 'annotation'),
            ))
            ->setHelp(<<<EOT
The <info>admin:generate-bundle</info> command helps you generates new admin bundles.

By default, the command interacts with the developer to tweak the generation.
Any passed option will be used as a default value for the interaction
(<comment>--namespace</comment> is the only one needed if you follow the
conventions):

<info>php app/console admin:generate-bundle --namespace=Acme/BlogBundle</info>

Note that you can use <comment>/</comment> instead of <comment>\\</comment> for the namespace delimiter to avoid any
problem.

If you want to disable any user interaction, use <comment>--no-interaction</comment> but don't forget to pass all needed options:

<info>php app/console admin:generate-bundle --namespace=Acme/BlogBundle --dir=src [--bundle-name=...] --no-interaction</info>

Note that the bundle namespace must end with "Bundle".
EOT
            )
        ;
    }
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getDialogHelper();
        $dialog->writeSection($output, 'Welcome to the Symfony2 admin generator');
        $output->writeln('<comment>Create an admingenrator bundle with generate:bundle</comment>');
          
        parent::interact($input, $output);
        
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
        try {
            $ret = $auto ? $routing->addResource($bundle, 'admingenerator') : false;
            if (!$ret) {
                $help = sprintf("        <comment>resource: \"@%s/Resources/Controller/\"</comment>\n        <comment>type:     admingenerator</comment>", $bundle);
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