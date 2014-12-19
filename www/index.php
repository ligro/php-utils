<?php

require_once dirname(__DIR__) . '/action.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;
$data = isset($_REQUEST['data']) ? $_REQUEST['data'] : null;

try {
    if ($action) {
        $value = Action::run($action, $data);
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

require_once __DIR__ . '/view.phtml';
