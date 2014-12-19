#!/usr/bin/env php
<?php

require_once __DIR__ . '/action.php';

$usage = "{$argv[0]} [-h] [--help] action\n";

if (isset($argv['-h']) || isset($argv['--help'])) {
    echo $usage;
    exit(1);
}

if (count($argv) > 2) {
    echo "bad number of arguments.\n$usage";
    exit (1);
}

if (count($argv) == 1) {
    do {
        $action = readline("action > ");
        if ($action == '?') {
            echo implode("\n", Action::$whitelist);
        }
    } while ($action == '?');
} else {
    $action = array_pop($argv);
}

if ($action == 'quit') {
    exit(0);
}

$data = readline("data > ");

try {

    $value = Action::run($action, $data);
    var_export($value);
    echo "\n";

} catch (Exception $e) {
    echo $e->getMessage();
}

