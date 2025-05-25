<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../database/classes/purchase.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../includes/auth.php');

header('Content-Type: application/json');

$loggedInUser = Auth::getInstance()->getUser();
if (!$loggedInUser) {
    echo json_encode(['error' => 'Authentication required']);
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['error' => 'Invalid purchase ID']);
    exit();
}

$purchaseId = (int)$_GET['id'];
$purchase = Purchase::getById($purchaseId);

if (!$purchase) {
    echo json_encode(['error' => 'Purchase not found']);
    exit();
}

$service = Service::getServiceById((int)$purchase['service_id']);
$buyer = User::getUserById((int)$purchase['user_id']);
$seller = $service ? User::getUserById($service->getSeller()) : null;

if ($loggedInUser['id'] != $purchase['user_id'] && $loggedInUser['id'] != $service->getSeller() && $loggedInUser['role'] != 'admin') {
    echo json_encode(['error' => 'Access denied']);
    exit();
}

$response = [
    'id' => $purchase['id'],
    'service_id' => $purchase['service_id'],
    'service_name' => $service->getName(),
    'service_price' => $service->getPrice(),
    'buyer_id' => $purchase['user_id'],
    'buyer_name' => $buyer->getName(),
    'seller_id' => $service->getSeller(),
    'seller_name' => $seller->getName(),
    'status' => $purchase['status'],
    'message' => nl2br(htmlspecialchars($purchase['message'] ?? '')),
    'purchased_at' => $purchase['purchased_at']
];

// If purchase is completed, check for available files
if ($purchase['status'] === 'completed') {
    $filesDir = __DIR__ . '/../database/assets/purchases/' . $purchaseId . '/';
    $availableFiles = [];

    if (is_dir($filesDir)) {
        $files = scandir($filesDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && is_file($filesDir . $file)) {
                // Extract original filename if it was stored with unique suffix
                $originalFilename = $file;
                if (preg_match('/^(.+)_[a-f0-9]+(\.[^.]+)$/', $file, $matches)) {
                    $originalFilename = $matches[1] . $matches[2];
                }

                $availableFiles[] = [
                    'filename' => $file,
                    'original_name' => $originalFilename,
                    'size' => filesize($filesDir . $file)
                ];
            }
        }
    }

    $response['available_files'] = $availableFiles;
}

echo json_encode($response);
