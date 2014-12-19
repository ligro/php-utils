<?php

class Action
{
    public static $whitelist = [
        'strlen',
        'json_decode',
        'urlencode',
        'urldecode',
    ];

    public static function run($action, $data)
    {
        if (!$data) {
            throw new Exception('No data');
        }

        if (method_exists(__CLASS__, $action)) {
            return self::$action($data);
        }

        if (self::isWhitelisted($action)) {
            return $action($data);
        }

        throw Exception('Action not found');
    }

    public static function isWhitelisted($action)
    {
        return in_array($action, self::$whitelist);
    }

    public static function json_decode($data)
    {
        $value = json_decode($data);
        if ($error = json_last_error()) {
            throw new Exception($error);
        }

        return $value;
    }
}
