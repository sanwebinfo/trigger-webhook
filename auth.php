<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);

header('Content-Type: application/json');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000; includeSubDomains');
header('X-Robots-Tag: noindex, nofollow', true);

require_once __DIR__ . '/./api/store.php';
(new DevCoder\DotEnv(__DIR__ . '/.env'))->load();

$valid_username = getenv('USERNAME');
$hashed_password = getenv('PASSWORD');

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function verify_credentials($username, $password, $valid_username, $hashed_password) {
    return $username === $valid_username && password_verify($password, $hashed_password);
}

if (!isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo json_encode(['error' => 'Authentication required'], JSON_UNESCAPED_UNICODE);
    exit();
} else {

    $username = sanitize_input($_SERVER['PHP_AUTH_USER']);
    $password = sanitize_input($_SERVER['PHP_AUTH_PW']);

    if (!verify_credentials($username, $password, $valid_username, $hashed_password)) {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        echo json_encode(['error' => 'Invalid credentials'], JSON_UNESCAPED_UNICODE);
        exit();
    }
}

$_SESSION['logged_in'] = true;
$_SESSION['username'] = $username;

header('Location: /');
exit();

?>