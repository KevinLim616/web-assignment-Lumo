<?php
session_start();

$_SESSION = [];
session_destroy();

if (isset($_COOKIE['auth_token'])) {
    setcookie('auth_token', '', time() - 3600, '/', '', true, true);
}

header("Location: ./../index.php");
exit;
