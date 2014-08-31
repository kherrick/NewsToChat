#!/usr/bin/env php
<?php

if (!$bootstrap = require_once __DIR__ . '/bootstrap.php') {
    die('You must set up the project dependencies.');
}

use NewsToChat\Command\PushNews;
use NewsToChat\Command\PullNews;

$settings = parse_ini_file(__DIR__ . '/config/settings.ini', true);
$name = $settings['global']['name'];
$version = $settings['global']['version'];
$token = $settings['global']['token'];
$sources = $settings['sources'];
$app = new \Cilex\Application($name, $version);

$app->command(new PushNews($entityManager, $token));
$app->command(new PullNews($entityManager, $sources));
$app->run();
