#!/usr/bin/env php
<?php

set_time_limit(0);

if (!isset($argv[1])) {
    $argv[1] = 0;
}

$vendorDir = __DIR__;
$deps_0 = array(
    array('symfony', 'http://github.com/symfony/symfony', isset($_SERVER['SYMFONY_VERSION']) ? $_SERVER['SYMFONY_VERSION'] : 'origin/master'),
    array('pagerfanta', 'http://github.com/whiteoctober/Pagerfanta.git', 'origin/master'),
    array('WhiteOctober/PagerfantaBundle', 'http://github.com/whiteoctober/WhiteOctoberPagerfantaBundle.git', 'origin/master'),
    array('KnpMenu', 'http://github.com/knplabs/KnpMenu.git', 'origin/master'),
    array('Knp/Bundle/MenuBundle', 'http://github.com/knplabs/KnpMenuBundle.git', 'origin/master'),
    array('symfony/Bundle/AsseticBundle', 'http://github.com/symfony/AsseticBundle.git', 'origin/master'),
    array('assetic', 'http://github.com/kriswallsmith/assetic.git', 'origin/master'),
    array('twig-generator', 'http://github.com/cedriclombardot/TwigGenerator.git', 'origin/master'),
);

$deps_1 = array(
    array('twig', 'http://github.com/fabpot/Twig.git', 'origin/master'),
    array('twig-extensions', 'http://github.com/fabpot/Twig-extensions.git', 'origin/master'),
    array('doctrine-common', 'http://github.com/doctrine/common.git', 'origin/master'),
    array('doctrine', 'http://github.com/doctrine/doctrine2.git', 'origin/master'),
    array('doctrine-dbal', 'http://github.com/doctrine/dbal.git', 'origin/master'),
);

$deps = 'deps_'.$argv[1];

foreach ($$deps as $dep) {
    list($name, $url, $rev) = $dep;

    echo "> Installing/Updating $name\n";

    $installDir = $vendorDir.'/'.$name;
    if (!is_dir($installDir)) {
        system(sprintf('git clone -q %s %s', escapeshellarg($url), escapeshellarg($installDir)));
    }

    system(sprintf('cd %s && git fetch -q origin && git reset --hard %s', escapeshellarg($installDir), escapeshellarg($rev)));
}
