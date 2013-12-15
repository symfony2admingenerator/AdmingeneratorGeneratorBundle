<?php

namespace Admingenerator\GeneratorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
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
            new InputOption('useDoctrineODM', '', InputOption::VALUE_REQUIRED, 'Use the Doctrine Odm', 'yes'),
            new InputOption('skin', '', InputOption::VALUE_REQUIRED, 'The skin you want to use', 'default'),
            new InputOption('useAssetic', '', InputOption::VALUE_REQUIRED, 'Do you want to use the assetic theme', 'yes'),
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

        $usePropel = $dialog->askAndValidate($output, $dialog->getQuestion('Do you want to use the Propel ORM?', $input->getOption('usePropel')),  function ($usePropel) { if (!in_array($usePropel, array('yes', 'no'))) { throw new \RuntimeException('You have to choose between yes and no'); } return $usePropel; } , false, $input->getOption('usePropel'));
        $input->setOption('usePropel', $usePropel);
        if ('yes' == $usePropel) {
            $this->setupDeps($this->getPropelDeps(), $input, $output);
        }
        $output->writeln('');

        $useDoctrineORM = $dialog->askAndValidate($output, $dialog->getQuestion('Do you want to use the Doctrine ORM?', $input->getOption('useDoctrineORM')),  function ($useDoctrineORM) { if (!in_array($useDoctrineORM, array('yes', 'no'))) { throw new \RuntimeException('You have to choose between yes and no'); } return $useDoctrineORM; } , false, $input->getOption('useDoctrineORM'));
        $input->setOption('useDoctrineORM', $useDoctrineORM);
        if ('yes' == $useDoctrineORM) {
            $this->setupDeps($this->getDoctrineOrmDeps(), $input, $output);
        }
        $output->writeln('');

        $useDoctrineODM = $dialog->askAndValidate($output, $dialog->getQuestion('Do you want to use the Doctrine ODM for MondoDB?', $input->getOption('useDoctrineODM')),  function ($useDoctrineODM) { if (!in_array($useDoctrineODM, array('yes', 'no'))) { throw new \RuntimeException('You have to choose between yes and no'); } return $useDoctrineODM; } , false, $input->getOption('useDoctrineODM'));
        $input->setOption('useDoctrineODM', $useDoctrineODM);
        if ('yes' == $useDoctrineODM) {
            $this->setupDeps($this->getDoctrineOdmDeps(), $input, $output);
        }
        $output->writeln('');

        $dialog->writeSection($output, 'The skin you want to use');
        $skin = $dialog->askAndValidate($output, $dialog->getQuestion('Which skin you want (default, active_admin, own)?', $input->getOption('skin')),  function ($skin) { if (!in_array($skin, array('default', 'active_admin', 'own'))) { throw new \RuntimeException('You have to choose between default, active_admin, own'); } return $skin; } , false, $input->getOption('skin'));

        $help = '';
        if ('own' == $skin) {
            $help ='YourThemeBundle::base_admin.html.twig' ;
        } else {
            $help = 'AdmingeneratorGeneratorBundle';

            if ('active_admin' == $skin) {
                $this->setupDeps($this->getActiveAdminDeps(), $input, $output);

                $help = 'AdmingeneratorActiveAdminThemeBundle';
            }

            $useAssetic = $dialog->askAndValidate($output, $dialog->getQuestion('Do you want to use the assetic theme (requires the sass and compass gems)?', $input->getOption('useAssetic')),  function ($useAssetic) { if (!in_array($useAssetic, array('yes', 'no'))) { throw new \RuntimeException('You have to choose between yes and no'); } return $useAssetic; } , false, $input->getOption('useAssetic'));
            $input->setOption('useAssetic', $useAssetic);
            if ('yes' == $useAssetic) {
                $this->setupDeps($this->getAsseticDeps(), $input, $output);
                $help .= '::base_admin.html.twig';
            } else {
                $help .= '::base_admin_assetic_less.html.twig';
            }

        }

        $dialog->writeSection($output, 'Now you have to work ;)');
        $output->writeln(array(
            '',
            'Edit config.yml and configure the section <comment>admingenerator_generator</comment> like that:',
            '<comment>admingenerator_generator</comment>:',
            '<comment>    base_admin_template</comment>: <info>'.$help.'</info>',
            '<comment>    use_propel</comment>: <info>'.(('yes' === $usePropel) ? 'true' : 'false' ).'</info>',
            '<comment>    use_doctrine_orm</comment>: <info>'.(('yes' === $useDoctrineORM) ? 'true' : 'false' ).'</info>',
            '<comment>    use_doctrine_odm</comment>: <info>'.(('yes' === $useDoctrineODM) ? 'true' : 'false' ).'</info>',
            '',
            'In section <comment>knp_menu</comment> :',
            '<comment>knp_menu</comment>:',
            '<comment>    twig</comment>: <info>true</info>',
            '',
            'In JMS security :',
            '<comment>jms_security_extra</comment>:',
            '<comment>    expressions</comment>: <info>true</info>',
        ));

         if (isset($useAssetic) && 'yes' == $useAssetic) {
              $output->writeln(array(
                '',
                'Check your <comment>assetic</comment> configuration ',
                '<comment>assetic</comment>:',
                '<comment>    filters</comment>:',
                '<comment>        compass</comment>: <info>~</info>',
                '<comment>        sass</comment>: <info>~</info>',
              ));
         }
    }

    protected function setupDeps($dependencies, InputInterface $input, OutputInterface $output)
    {
        foreach ($dependencies as $deps => $params) {

            if (isset($params['path'])) {
                $isCloned = file_exists($this->getContainer()->getParameter('kernel.root_dir').'/../vendor/'.$params['path']);
                $output->writeln(sprintf("<info>%s</info> is cloned? [%s]", $deps,
                    $isCloned ? '<comment>OK</comment>' : '<error>KO</error>'));
                if (!$isCloned) {
                    if ($input->getOption('gitOrDeps') == 'deps') {
                        $this->addDeps($output, $deps, $params);
                    } else {
                        $this->addSubmodule($output, $deps, $params);
                    }
                }
            }

            // Autoload
            if (isset($params['autoloadKey'])) {
                $output->writeln(sprintf("<info>%s</info> is autoloaded? [%s]", $deps,
                    $this->isAutoloaded($params['autoloadKey']) ? '<comment>OK</comment>' : '<error>KO</error>'));

                if (!$this->isAutoloaded($params['autoloadKey'])) {
                    $this->addToAutoload($output, $params['autoloadKey'], $params['autoloadPath']);
                }
            }

            // Kernel
            if (isset($params['isBundle']) && $params['isBundle']) {
                $output->writeln(sprintf("<info>%s</info> is loaded in kernel? [%s]", $deps,
                $this->isInKernel($deps) ? '<comment>OK</comment>' : '<error>KO</error>'));

                if (!$this->isInKernel($deps)) {
                    $this->updateKernel($this->getDialogHelper(), $input, $output, $this->getContainer()->get('kernel'), $params['namespace'], $deps);
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
        $autoloadKey = addslashes($autoloadKey);
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

        return strstr($autoload, $autoloadKey) || strstr($autoload, addslashes($autoloadKey));
    }

    protected function isInKernel($bundleName)
    {
        $bundles = $this->getContainer()->get('kernel')->getBundles();

        return isset($bundles[$bundleName]);
    }

    protected function getRequiredDeps()
    {
        return array(
            'TwigGenerator' => array(
                    'git'                 => 'git://github.com/cedriclombardot/TwigGenerator.git',
                    'path'               => '/twig-generator',
                    'autoloadKey'        => 'TwigGenerator',
                    'autoloadPath'       => '/../vendor/twig-generator/src',
        ),
            'twig'       => array(
                     'git'                => 'git://github.com/fabpot/Twig.git',
                     'path'               => '/twig',
                     'version'            => 'v1.2.0',
        ),
            'twig-extensions'       => array(
                     'git'                => 'git://github.com/fabpot/Twig-extensions.git',
                     'path'               => '/twig-extensions',
        ),
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
                     'namespace'          => 'WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle',
                     'isBundle'           => true,
        ),
            'KnpMenuBundle' => array(
                     'git'               => 'git://github.com/KnpLabs/KnpMenuBundle.git',
                     'path'              => '/bundles/Knp/Bundle/MenuBundle',
                     'autoloadKey'       => 'Knp',
                     'autoloadPath'      => '/../vendor/bundles',
                     'namespace'         => 'Knp\Bundle\MenuBundle\KnpMenuBundle',
                     'isBundle'          => true,
        ),
            'KnpMenu' => array(
                     'git'               => 'git://github.com/KnpLabs/KnpMenu.git',
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

    protected function getDoctrineOdmDeps()
    {
        return array_merge( $this->getCommonDoctrineDeps(), array(
            'DoctrineMongoDBBundle' => array(
                     'git'                => 'git://github.com/symfony/DoctrineMongoDBBundle.git',
                     'path'               => '/bundles/Doctrine/Bundle/MongoDBBundle',
                     'namespace'          => 'Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle',
                     'isBundle'           => true,
            ),
            'doctrine-mongodb' => array(
                    'git'                => 'git://github.com/doctrine/mongodb.git',
                    'path'               => '/doctrine-mongodb',
                    'autoloadKey'        => 'Doctrine\MongoDB',
                    'autoloadPath'       => '/../vendor/doctrine-mongodb/lib',
            ),
            'doctrine-mongodb-odm' => array(
                    'git'                => 'git://github.com/doctrine/mongodb-odm.git',
                    'path'               => '/doctrine-mongodb-odm',
                    'autoloadKey'        => 'Doctrine\ODM',
                    'autoloadPath'       => '/../vendor/doctrine-mongodb-odm/lib',
            ),
        ));
    }

    protected function getDoctrineOrmDeps()
    {
        return array_merge( $this->getCommonDoctrineDeps(), array(
            'DoctrineBundle'        => array(
                     'git'                => 'git://github.com/doctrine/DoctrineBundle.git',
                     'path'               => '/bundles/Doctrine/Bundle/DoctrineBundle',
                     'autoloadKey'        => 'Doctrine\Bundle',
                     'autoloadPath'       => '/../vendor/bundles',
                     'namespace'          => 'Doctrine\Bundle\DoctrineBundle\DoctrineBundle',
                     'isBundle'           => true,
            ),
        ));
    }

    protected function getCommonDoctrineDeps()
    {
        return array(
            'doctrine-common' => array(
                    'git'                => 'git://github.com/doctrine/common.git',
                    'path'               => '/doctrine-common',
                    'autoloadKey'        => 'Doctrine\Common',
                    'autoloadPath'       => '/../vendor/doctrine-common/lib',
        ),
            'doctrine-dbal' => array(
                    'git'                => 'git://github.com/doctrine/dbal.git',
                    'path'               => '/doctrine-dbal',
                    'autoloadKey'        => 'Doctrine\DBAL',
                    'autoloadPath'       => '/../vendor/doctrine-dbal/lib',
        ),

            'doctrine-fixtures' => array(
                    'git'                => 'git://github.com/doctrine/data-fixtures.git',
                    'path'               => '/doctrine-fixtures',
                    'autoloadKey'        => 'Doctrine\Common\DataFixtures',
                    'autoloadPath'       => '/../vendor/doctrine-fixtures/lib',
        ),
            'DoctrineFixturesBundle' => array(
                     'git'                => 'git://github.com/doctrine/DoctrineFixturesBundle.git',
                     'path'               => '/bundles/Doctrine/Bundle/FixturesBundle',
                     'namespace'          => 'Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle',
                     'isBundle'           => true,
        ),
            'doctrine' => array(
                    'git'                => 'git://github.com/doctrine/doctrine2.git',
                    'path'               => '/doctrine',
                    'autoloadKey'        => 'Doctrine',
                    'autoloadPath'       => '/../vendor/doctrine/lib',
        ),

        );
    }

    protected function getActiveAdminDeps()
    {
        return array(
            'AdmingeneratorActiveAdminThemeBundle' => array(
                     'git'                => 'git://github.com/cedriclombardot/AdmingeneratorActiveAdminThemeBundle.git',
                     'path'               => '/bundles/Admingenerator/ActiveAdminThemeBundle',
                     'namespace'          => 'Admingenerator\ActiveAdminThemeBundle\AdmingeneratorActiveAdminThemeBundle',
                     'isBundle'           => true,
        ),
        );
    }

    protected function getAsseticDeps()
    {
        return array(
            'AsseticBundle' => array(
                     'git'                => 'git://github.com/symfony/AsseticBundle.git',
                     'path'               => '/bundles/Symfony/Bundle/AsseticBundle',
                     'namespace'          => 'Symfony\Bundle\AsseticBundle\AsseticBundle',
                     'isBundle'           => true,
        ),
            'Assetic' => array(
                     'git'                => 'git://github.com/symfony/AsseticBundle.git',
                     'path'               => '/assetic',
                     'autoloadKey'        => 'Assetic',
                     'autoloadPath'       => '/../vendor/assetic/src',
                     'version'            => 'v1.0.2',
        ),
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
