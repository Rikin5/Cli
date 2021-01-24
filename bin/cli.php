<?php
try {

    require_once __DIR__ . '/../vendor/autoload.php';

    $commandManager = new Cli\CommandManager($argv);


} catch (Exceptions\CliException $e) {
    echo 'Error: ' . $e->getMessage();
}