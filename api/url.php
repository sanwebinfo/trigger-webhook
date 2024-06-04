<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);

require_once 'config.php';

header('Content-Type: application/json');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000; includeSubDomains');
header('X-Robots-Tag: noindex, nofollow', true);

function sendResponse($status, $message) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode(['status' => $status, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    sendResponse(401, "Authentication required");
}

try {

    $query = "SELECT * FROM webhook";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($data) {
        http_response_code(200);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        sendResponse(404, "Not found");
    }
} catch (PDOException $e) {
    sendResponse(500, "Internal Server Error");
}

?>