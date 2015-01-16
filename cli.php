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

function getAction()
{
    do {
        $action = readline("action > ");
        if ($action === '') {
            echo implode("\n", Action::$whitelist), "\n\n";

        } else if (in_array($action, ['q', 'quit'])) {
            return false;

        } else if ($action) {
            readline_add_history($action);
            if (!Action::check($action)) {
                echo "Action not found\n";
                $action = '';
            }
        }
    } while ($action === '');

    return $action;
}

function getData()
{
    return readline("data > ");
}

function run($action, $data)
{
    try {
        $value = Action::run($action, $data);
        var_export($value);
        echo "\n";

    } catch (Exception $e) {
        echo $e->getMessage(), "\n";
    }
}

if (count($argv) > 1) {
    run(array_pop($argv), getData());
    exit(0);
}

$readlineFile = '/tmp/phputils-action-history';

// init read line
readline_info('readline_name', 'action');
is_file($readlineFile) and readline_read_history($readlineFile);

// TODO
//readline_completion_function();

while (true) {
    $action = getAction();
    if ($action === false) {
        break;
    }
    run($action, getData());
}

readline_write_history($readlineFile);

