<?php

class Action
{
    public static $whitelist = [
        'strlen',
        'json_decode',
        'json_decode_array',
        'unserialize',
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

        throw new Exception('Action not found');
    }

    public static function isWhitelisted($action)
    {
        return in_array($action, self::$whitelist);
    }

    public static function json_decode_array($data)
    {
        $value = json_decode($data, true);
        self::_jsonError();
        return $value;
    }
    public static function json_decode($data)
    {
        $value = json_decode($data);
        self::_jsonError();
        return $value;
    }

    protected static function _jsonError()
    {
        if ($code = json_last_error()) {
            switch ($code) {
            case 4:
                $msg = "Syntax error";
                break;
            default:
                $msg = $code;
            }
            throw new Exception($msg, $code);
        }
    }
}
