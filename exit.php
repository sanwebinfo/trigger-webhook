<?php

header('Content-Type: application/json');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000');
header('X-Robots-Tag: noindex, nofollow', true);

session_start();

function sendResponse($status, $message) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode(['status' => $status, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

if (session_status() === PHP_SESSION_ACTIVE) {

    $_SESSION = [];
    session_destroy();
    header('HTTP/1.0 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="My Realm"');
    exit(json_encode(['error' => 'Authentication required'], JSON_UNESCAPED_UNICODE));

} else {
    sendResponse(200, "No active session to logout from.");
}

?>