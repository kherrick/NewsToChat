#!/usr/bin/env php
<?php
$vendor = '/../../vendor';
$autoload = $vendor . '/autoload.php';

if (file_exists(__DIR__ . $autoload)) {
    $loader = require_once __DIR__ . $autoload;
} else {
    echo "You must set up the project dependencies.\n";
    exit(1);
}

system(__DIR__ . $vendor . '/bin/doctrine orm:schema-tool:drop --force');

if (empty($argv[1])) {
    echo "No argument passed. Defaulting to create.\n";
    system(__DIR__ . $vendor .  '/bin/doctrine orm:schema-tool:create');
}

if (!empty($argv[1]) && $argv[1] == 'update') {
    echo "Update argument passed. Updating.\n";
    system(__DIR__ . $vendor .  '/bin/doctrine orm:schema-tool:update --force');
}
