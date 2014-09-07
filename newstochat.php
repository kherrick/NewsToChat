#!/usr/bin/env php
<?php
if (!$bootstrap = require_once __DIR__ . '/bootstrap.php') {
    die('You must set up the project dependencies.');
}

use Cilex\Application;
use NewsToChat\Command\Maintenance;
use NewsToChat\Command\PushNews;
use NewsToChat\Command\PullNews;

$runtime = new DateTime();
$runtime = $runtime->format('Y-m-d @ H:i:s');

$settings = parse_ini_file(__DIR__ . '/config/settings.ini', true);
$name = $settings['global']['name'];
$sources = $settings['sources'];
$token = $settings['global']['token'];
$version = $settings['global']['version'];

$app = new Application($name, $version);

$app->command(new Maintenance($entityManager, $runtime));
$app->command(new PullNews($entityManager, $runtime, $sources));
$app->command(new PushNews($entityManager, $runtime, $token));

$app->run();
