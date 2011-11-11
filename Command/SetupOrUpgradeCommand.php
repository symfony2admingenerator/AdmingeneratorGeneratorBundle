<?php

namespace Admingenerator\GeneratorBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Sensio\Bundle\GeneratorBundle\Manipulator\KernelManipulator;

class SetupOrUpgradeCommand extends ContainerAwareCommand
{

    /**
     * @see Command
     */
    protected function configure()
    {
        $this
        ->setDefinition(array(
            new InputOption('gitOrDeps', '', InputOption::VALUE_REQUIRED, 'The checkout mode', 'deps'),
            new InputOption('usePropel', '', InputOption::VALUE_REQUIRED, 'Use the propel Orm', 'yes'),
            new InputOption('useDoctrineORM', '', InputOption::VALUE_REQUIRED, 'Use the Doctrine Orm', 'yes'),
        ))
        ->setDescription('Help you to install, update and configure your AdminGeneratorGeneratorBundle')
        ->setHelp(<<<EOT
The <info>admin:setup</info> command helps you to install, update and configure your AdminGeneratorGeneratorBundle
EOT
        )
        ->setName('admin:setup')
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getDialogHelper();
        $dialog->writeSection($output, 'Welcome to the Symfony2 admin generator setup');

        $gitOrDeps = $dialog->askAndValidate($output, $dialog->getQuestion('Are you using deps file or git submodules (deps, git)', $input->getOption('gitOrDeps')),  function ($gitOrDeps) { if (!in_array($gitOrDeps, array('deps', 'git'))) { throw new \RuntimeException('You have to choice beetwen git or deps'); } return $gitOrDeps; } , false, $input->getOption('gitOrDeps'));
        $input->setOption('gitOrDeps', $gitOrDeps);

        $dialog->writeSection($output, 'Check required deps');
        $this->setupDeps($this->getRequiredDeps(), $input, $output);

        $dialog->writeSection($output, 'The ORM/ODM you want');

        $usePropel = $dialog->askAndValidate($output, $dialog->getQuestion('Wan\'t you to use Propel ORM ?', $input->getOption('usePropel')),  function ($usePropel) { if (!in_array($usePropel, array('yes', 'no'))) { throw new \RuntimeException('You have to choice beetwen yes or no'); } return $usePropel; } , false, $input->getOption('usePropel'));
        if ('yes' == $usePropel) {
            $this->setupDeps($this->getPropelDeps(), $input, $output);
        }

        $useDoctrineORM = $dialog->askAndValidate($output, $dialog->getQuestion('Wan\'t you to use Doctrine ORM ?', $input->getOption('useDoctrineORM')),  function ($useDoctrineORM) { if (!in_array($useDoctrineORM, array('yes', 'no'))) { throw new \RuntimeException('You have to choice beetwen yes or no'); } return $usePropel; } , false, $input->getOption('useDoctrineORM'));
        if ('yes' == $useDoctrineORM) {
            $this->setupDeps($this->getDoctrineOrmDeps(), $input, $output);
        }
    }

    protected function setupDeps($dependencies, InputInterface $input, OutputInterface $output)
    {
        foreach ($dependencies as $deps => $params) {
            $isCloned = file_exists($this->getContainer()->getParameter('kernel.root_dir').'/../vendor/'.$params['path']);
            $output->writeln(sprintf("<info>%s</info> is cloned ? [%s]", $deps,
                $isCloned ? '<comment>OK</comment>' : '<error>KO</error>'));
            if (!$isCloned) {
                if ($input->getOption('gitOrDeps') == 'deps') {
                    $this->addDeps($output, $deps, $params);
                } else {
                    $this->addSubmodule($output, $deps, $params);
                }
            }

            // Autoload
            if (isset($params['autoloadKey'])) {
                $output->writeln(sprintf("<info>%s</info> is autoloaded ? [%s]", $deps,
                    $this->isAutoloaded($params['autoloadKey']) ? '<comment>OK</comment>' : '<error>KO</error>'));

                if (!$this->isAutoloaded($params['autoloadKey'])) {
                    $this->addToAutoload($output, $params['autoloadKey'], $params['autoloadPath']);
                }
            }

            // Kernel
            if (isset($params['isBundle']) && $params['isBundle']) {
                $output->writeln(sprintf("<info>%s</info> is loaded in kernel ? [%s]", $deps,
                $this->isInKernel($deps) ? '<comment>OK</comment>' : '<error>KO</error>'));

                if (!$this->isInKernel($deps)) {
                    $this->updateKernel($dialog, $input, $output, $this->getContainer()->get('kernel'), $params['namespace'], $deps);
                }
            }
        }
    }

    protected function addSubmodule(OutputInterface $output, $deps, $params)
    {
        $output->writeln('Add submodule : '.$deps);
        system('cd '.$this->getContainer()->getParameter('kernel.root_dir').'/../ && git submodule add '.$params['git'].' vendor'.$params['path']);

        if (isset($params['version'])) {
            system('cd '.$this->getContainer()->getParameter('kernel.root_dir').'/../vendor'.$params['path'].' && git co '.$params['version']);
        }
    }

    protected function addDeps(OutputInterface $output, $deps, $params)
    {
        $output->writeln('Add into deps : '.$deps);
        $fp = fopen($this->getContainer()->getParameter('kernel.root_dir').'/../deps', 'a+');

        $ini =  <<<EOD

[{$deps}]
    git={$params['git']}
    target={$params['path']}
EOD;

        if (isset($params['version'])) {
            $ini .=  <<<EOD
    version={$params['version']}
EOD;
        }

        $ini .= "\n";

        fputs($fp, $ini);
        fclose($fp);

        //Setup using vendors
        system($this->getContainer()->getParameter('kernel.root_dir').'/../bin/vendors install');

    }

    protected function addToAutoload(OutputInterface $output, $autoloadKey, $autoloadPath)
    {
        $output->writeln('Add into autoload '.$autoloadKey);

        $autoloadFile = $this->getContainer()->getParameter('kernel.root_dir').'/autoload.php';
        $autoload  = file_get_contents($autoloadFile);
        $autoload = str_replace('$loader->registerNamespaces(array(', '$loader->registerNamespaces(array('."\n    '{$autoloadKey}' => __DIR__.'{$autoloadPath}'," , $autoload);

        $fp = fopen($autoloadFile, 'w');
        fputs($fp, $autoload);
        fclose($fp);
    }

    protected function isAutoloaded($autoloadKey)
    {
        $autoload  = file_get_contents($this->getContainer()->getParameter('kernel.root_dir').'/autoload.php');

        return preg_match('/\''.addslashes($autoloadKey).'\'/m',$autoload);
    }

    protected function isInKernel($bundleName)
    {
        $bundles = $this->getContainer()->get('kernel')->getBundles();

        return isset($bundles[$bundleName]);
    }

    protected function getRequiredDeps()
    {
        return array(
            'PagerFanta' => array(
                     'git'                => 'git://github.com/whiteoctober/Pagerfanta.git',
                     'path'               => '/pagerfanta',
                     'autoloadKey'        => 'Pagerfanta',
                     'autoloadPath'       => '/../vendor/pagerfanta/src',
        ),
            'WhiteOctoberPagerfantaBundle' => array(
                     'git'                => 'git://github.com/whiteoctober/WhiteOctoberPagerfantaBundle.git',
                     'path'               => '/bundles/WhiteOctober/PagerfantaBundle',
                     'autoloadKey'        => 'WhiteOctober\PagerfantaBundle',
                     'autoloadPath'       => '/../vendor/bundles',
                     'namespace'          => 'WhiteOctober\PagerfantaBundle',
                     'isBundle'           => true,
        ),
            'KnpMenuBundle' => array(
                     'git'               => 'git://github.com/knplabs/KnpMenuBundle.git',
                     'path'              => '/bundles/Knp/Bundle/MenuBundle',
                     'autoloadKey'       => 'Knp',
                     'autoloadPath'      => '/../vendor/bundles',
                     'namespace'          => 'Knp\Bundle\MenuBundle',
                     'isBundle'          => true,
        ),
            'KnpMenu' => array(
                     'git'               => 'git://github.com/knplabs/KnpMenu.git',
                     'path'              => '/KnpMenu',
                     'autoloadKey'       => 'Knp\Menu',
                     'autoloadPath'      => '/../vendor/KnpMenu/src',
        ),

        );
    }

    protected function getPropelDeps()
    {
        return array(
            'PropelBundle' => array(
                     'git'                => 'git://github.com/propelorm/PropelBundle.git',
                     'path'               => '/bundles/Propel/PropelBundle',
                     'autoloadKey'        => 'Propel',
                     'autoloadPath'       => '/../vendor/bundles',
                     'namespace'          => 'Propel\PropelBundle\PropelBundle',
                     'isBundle'           => true,
        ),
            'phing' => array(
                     'git'                => 'git://github.com/Xosofox/phing.git',
                     'path'               => '/phing',
        ),
            'propel' => array(
                     'git'                => 'git://github.com/propelorm/Propel.git',
                     'path'               => '/propel',
        ),
        );
    }

    protected function getDoctrineOrmDeps()
    {
        return array(

        );
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


        $output->writeln('<info>Bundle setuped</info>');
    }


    protected function getDialogHelper()
    {
        $dialog = $this->getHelperSet()->get('dialog');
        if (!$dialog || get_class($dialog) !== 'Sensio\Bundle\GeneratorBundle\Command\Helper\DialogHelper') {
            $this->getHelperSet()->set($dialog = new \Sensio\Bundle\GeneratorBundle\Command\Helper\DialogHelper());
        }

        return $dialog;
    }

    protected function updateKernel($dialog, InputInterface $input, OutputInterface $output, KernelInterface $kernel, $namespace, $bundle)
    {
        $output->writeln('Enabling the bundle inside the Kernel');
        $manip = new KernelManipulator($kernel);
        try {
            if (!$manip->addBundle($namespace)) {
                $reflected = new \ReflectionObject($kernel);

                return array(
                sprintf('- Edit <comment>%s</comment>', $reflected->getFilename()),
                    '  and add the following bundle in the <comment>AppKernel::registerBundles()</comment> method:',
                    '',
                sprintf('    <comment>new %s(),</comment>', $namespace),
                    '',
                );
            }
        } catch (\RuntimeException $e) {
            return array(
            sprintf('Bundle <comment>%s</comment> is already defined in <comment>AppKernel::registerBundles()</comment>.', $namespace),
                '',
            );
        }
    }

}
