<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../includes/database.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../database/classes/purchase.php');

header('Content-Type: application/json');

$loggedInUser = Auth::getInstance()->getUser();

if (!$loggedInUser) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_POST['purchase_id']) || empty($_POST['purchase_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Purchase ID is required']);
    exit;
}

$purchaseId = (int) $_POST['purchase_id'];

try {
    $purchase = Purchase::getById($purchaseId);
    if (!$purchase) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Purchase not found']);
        exit;
    }

    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT seller FROM services WHERE id = ?');
    $stmt->execute([$purchase['service_id']]);
    $service = $stmt->fetch();

    if (!$service || $service['seller'] != $loggedInUser['id']) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'You are not authorized to complete this order']);
        exit;
    }

    if ($purchase['status'] === 'completed') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Order is already completed']);
        exit;
    }

    $uploadDir = __DIR__ . '/../database/assets/purchases/' . $purchaseId . '/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            throw new Exception('Failed to create upload directory');
        }
    }

    $uploadedFiles = [];
    $errors = [];

    if (isset($_FILES['completion_files']) && !empty($_FILES['completion_files']['name'][0])) {
        $fileCount = count($_FILES['completion_files']['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            $fileName = $_FILES['completion_files']['name'][$i];
            $fileTmpName = $_FILES['completion_files']['tmp_name'][$i];
            $fileSize = $_FILES['completion_files']['size'][$i];
            $fileError = $_FILES['completion_files']['error'][$i];
            $fileType = $_FILES['completion_files']['type'][$i];

            if ($fileError === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if ($fileError !== UPLOAD_ERR_OK) {
                $errors[] = "Error uploading file '$fileName': Upload error code $fileError";
                continue;
            }

            $maxFileSize = 50 * 1024 * 1024;
            if ($fileSize > $maxFileSize) {
                $errors[] = "File '$fileName' is too large.";
                continue;
            }

            $allowedTypes = [
                'application/zip',
                'application/x-zip-compressed',
                'application/x-rar-compressed',
                'application/x-7z-compressed',
                'application/x-tar',
                'application/gzip',
                'application/x-gzip'
            ];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $detectedType = finfo_file($finfo, $fileTmpName);
            finfo_close($finfo);

            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = ['zip', 'rar', '7z', 'tar', 'gz'];

            if (!in_array($detectedType, $allowedTypes) && !in_array($fileExtension, $allowedExtensions)) {
                $errors[] = "File '$fileName' has an invalid type. Only compressed files (.zip, .rar, .7z, .tar, .gz) are allowed.";
                continue;
            }

            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $baseFileName = pathinfo($fileName, PATHINFO_FILENAME);
            $uniqueFileName = $baseFileName . '_' . uniqid() . '.' . $fileExtension;
            $destinationPath = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($fileTmpName, $destinationPath)) {
                $uploadedFiles[] = [
                    'original_name' => $fileName,
                    'stored_name' => $uniqueFileName,
                    'size' => $fileSize
                ];
            } else {
                $errors[] = "Failed to save file '$fileName'";
            }
        }
    }

    if (Purchase::complete($purchaseId)) {
        $response = [
            'success' => true,
            'message' => 'Order completed successfully'
        ];

        if (!empty($uploadedFiles)) {
            $response['uploaded_files'] = $uploadedFiles;
        }

        if (!empty($errors)) {
            $response['warnings'] = $errors;
        }

        echo json_encode($response);
    } else {
        throw new Exception('Failed to update purchase status');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
}
