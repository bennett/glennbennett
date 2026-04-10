<?php
/**
 * Enom API Proxy
 *
 * Tunnels Enom API requests through tsgimh.com's static IP
 * so the Enom IP whitelist doesn't break when the home IP changes.
 *
 * Usage: POST to this script with a Bearer token and the Enom
 * API parameters as JSON. Returns the raw Enom XML response.
 */

// Shared secret — must match ENOM_PROXY_TOKEN in macman .env
$expectedToken = getenv('ENOM_PROXY_TOKEN') ?: 'acd0ab3f7b1a8e83c1403550e17e69f14ee50bfe9e86b45755688a6426f595fc';

// Only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'POST only']);
    exit;
}

// Read request body
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || empty($input['params'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing params']);
    exit;
}

// Auth check — token in body (headers get stripped by ModSecurity on shared hosting)
$token = $input['token'] ?? '';
if ($token !== $expectedToken) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Forward to Enom
$url = 'https://reseller.enom.com/interface.asp?' . http_build_query($input['params']);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    http_response_code(502);
    echo json_encode(['error' => 'Enom request failed: ' . $error]);
    exit;
}

// Return raw XML response with proper content type
header('Content-Type: application/xml');
http_response_code($httpCode);
echo $response;
