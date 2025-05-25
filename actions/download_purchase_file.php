<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../includes/database.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../database/classes/purchase.php');

$loggedInUser = Auth::getInstance()->getUser();

if (!$loggedInUser) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if (!isset($_GET['purchase_id']) || !isset($_GET['filename'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

$purchaseId = (int) $_GET['purchase_id'];
$filename = basename($_GET['filename']);

try {
    $purchase = Purchase::getById($purchaseId);
    if (!$purchase) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Purchase not found']);
        exit;
    }

    if ($purchase['user_id'] != $loggedInUser['id']) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'You are not authorized to download files from this purchase']);
        exit;
    }

    if ($purchase['status'] !== 'completed') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Files are only available for completed orders']);
        exit;
    }

    $filePath = __DIR__ . '/../database/assets/purchases/' . $purchaseId . '/' . $filename;

    if (!file_exists($filePath) || !is_file($filePath)) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'File not found']);
        exit;
    }

    $realFilePath = realpath($filePath);
    $expectedDir = realpath(__DIR__ . '/../database/assets/purchases/' . $purchaseId . '/');

    if (!$realFilePath || !$expectedDir || strpos($realFilePath, $expectedDir) !== 0) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Access denied']);
        exit;
    }

    $fileSize = filesize($filePath);
    $mimeType = mime_content_type($filePath);

    $originalFilename = $filename;
    if (preg_match('/^(.+)_[a-f0-9]+(\.[^.]+)$/', $filename, $matches)) {
        $originalFilename = $matches[1] . $matches[2];
    }

    header('Content-Type: ' . $mimeType);
    header('Content-Disposition: attachment; filename="' . $originalFilename . '"');
    header('Content-Length: ' . $fileSize);
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: 0');

    readfile($filePath);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
}
