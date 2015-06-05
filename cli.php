#!/usr/bin/env php
<?php

require_once __DIR__ . '/action.php';

class App
{
    protected static $_readlineFile;

    public static function run($argv)
    {
        $usage = "{$argv[0]} [-h] [--help] action\n";

        if (in_array('-h', $argv) || in_array('--help', $argv)) {
            throw new Exception($usage, 1);
        }

        if (count($argv) > 2) {
            throw new Exception("bad number of arguments.\n$usage", 1);
        }

        if (count($argv) > 1) {
            self::_run(array_pop($argv), self::_getData());
            return;
        }

        self::_initCompletion();

        while (true) {
            $action = self::_getAction();
            if ($action === false) {
                break;
            }
            self::_run($action, self::_getData());
        }

        self::_flushHistory();
    }

    protected static function _run($action, $data)
    {
        try {
            $value = Action::run($action, $data);
            var_export($value);
            echo "\n";

        } catch (Exception $e) {
            echo $e->getMessage(), "\n";
        }
    }

    protected static function _getData()
    {
        // todo detect if we are reading from stdin or pipe
        $data= readline("data > ");
        readline_add_history($data);
        return $data;
    }

    protected static function _getAction()
    {
        do {
            $action = readline("action > ");
            if ($action === false) {
                break;
            }

            $action = trim($action);
            if ($action === '') {
                echo implode("\n", Action::$whitelist), "\n\n";

            } else if (in_array($action, ['q', 'quit'])) {
                return false;

            } else if ($action) {
                if (!Action::isWhitelisted($action)) {
                    echo "Action not found\n";
                    $action = '';
                }
            }
        } while ($action === '');

        return $action;
    }

    public static function readlineCompletion()
    {
        return Action::$whitelist;
    }

    protected static function _initCompletion()
    {
        self::$_readlineFile = sys_get_temp_dir() . '/phputils-data-history';

        // init read line
        readline_info('readline_name', 'data');
        readline_completion_function([__CLASS__, 'readlineCompletion']);

        is_file(self::$_readlineFile) and readline_read_history(self::$_readlineFile);
    }

    protected static function _flushHistory()
    {
        readline_write_history(self::$_readlineFile);
    }
}

try {
    App::run($argv);
} catch (Exception $e) {
    exit($e->getCode());
}
