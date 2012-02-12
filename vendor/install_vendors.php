#!/usr/bin/env php
<?php

set_time_limit(0);

if (!isset($argv[1])) {
    $argv[1] = 0;
}

$vendorDir = __DIR__;
$deps_0 = array(
    array('symfony', 'git://github.com/symfony/symfony.git', isset($_SERVER['SYMFONY_VERSION']) ? $_SERVER['SYMFONY_VERSION'] : 'origin/master'),
    array('pagerfanta', 'git://github.com/whiteoctober/Pagerfanta.git', 'origin/master'),
    array('WhiteOctober/PagerfantaBundle', 'git://github.com/whiteoctober/WhiteOctoberPagerfantaBundle.git', 'origin/master'),
    array('KnpMenu', 'git://github.com/KnpLabs/KnpMenu.git', 'origin/master'),
    array('Knp/Bundle/MenuBundle', 'git://github.com/KnpLabs/KnpMenuBundle.git', 'origin/master'),
    array('symfony/Bundle/AsseticBundle', 'git://github.com/symfony/AsseticBundle.git', 'origin/master'),
    array('assetic', 'git://github.com/kriswallsmith/assetic.git', 'origin/master'),
    array('twig-generator', 'git://github.com/cedriclombardot/TwigGenerator.git', 'origin/master'),
);

$deps_1 = array(
    array('twig', 'git://github.com/fabpot/Twig.git', 'origin/master'),
    array('twig-extensions', 'git://github.com/fabpot/Twig-extensions.git', 'origin/master'),
    array('doctrine-common', 'git://github.com/doctrine/common.git', 'origin/master'),
    array('doctrine', 'git://github.com/doctrine/doctrine2.git', 'origin/master'),
    array('doctrine-dbal', 'git://github.com/doctrine/dbal.git', 'origin/master'),
);

$deps = 'deps_'.$argv[1];

foreach ($$deps as $dep) {
    list($name, $url, $rev) = $dep;

    echo "> Installing/Updating $name\n";

    $installDir = $vendorDir.'/'.$name;
    if (!is_dir($installDir)) {
        system(sprintf('git clone %s %s', escapeshellarg($url), escapeshellarg($installDir)));
    }

    system(sprintf('cd %s && git fetch -q origin && git reset --hard %s', escapeshellarg($installDir), escapeshellarg($rev)));
}
