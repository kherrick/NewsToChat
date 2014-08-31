#!/usr/bin/env php
<?php
if (!$bootstrap = require_once __DIR__ . '/../bootstrap.php') {
    die('You must set up the project dependencies.');
}

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
