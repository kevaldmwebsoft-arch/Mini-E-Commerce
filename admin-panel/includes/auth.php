<?php

function require_login() {
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header("Location: " . dirname(__DIR__) . "/auth/login.php");
        exit;
    }
}

function set_flash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
