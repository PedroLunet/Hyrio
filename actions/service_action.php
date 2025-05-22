<?php

declare(strict_types=1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/file_uploader.php';
require_once __DIR__ . '/../database/classes/service.php';

$user = Auth::getInstance()->getUser();

if (!$user || !$user['is_seller']) {
    header('Location: /pages/login.php');
    exit();
}

$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$price = $_POST['price'] ?? '';
$categoryId = $_POST['category_id'] ?? '';
$image = $_FILES['image'] ?? null;
$error = null;

// Changed validation to not require image
if (empty($name) || empty($description) || empty($price) || empty($categoryId)) {
    $error = "Name, description, price and category are required";
} else if (!is_numeric($price) || $price <= 0) {
    $error = "Price must be a positive number";
} else {
    $db = Database::getInstance();
    $created = Service::createService(
        $name,
        $description,
        (float)$price,
        $user['id'],
        (int)$categoryId
    );

    if (!$created) {
        $error = "Failed to create service";
    } else {
        $serviceId = (int)$db->lastInsertId();
        $imageUploaded = false;

        // Upload image only if provided and valid
        if (isset($image) && $image['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '/database/assets/services/' . $serviceId . '/';
            $uploader = new FileUploader('image', $uploadDir, ['image/jpeg', 'image/png'], 5242880, '2:1');
            $imagePath = $uploader->uploadFile($image);

            if ($imagePath !== null) {
                $stmt = $db->prepare('UPDATE services SET image = ? WHERE id = ?');
                $stmt->execute([$imagePath, $serviceId]);
                $imageUploaded = true;
            } else {
                $error = "Image upload failed: " . implode(', ', $uploader->getErrors());
                // Don't delete the service, just notify about image issue
            }
        } else if (isset($image) && $image['error'] !== UPLOAD_ERR_NO_FILE) {
            // Only consider it an error if user tried to upload but failed
            $error = "Image upload error: " . ($image['error'] ?? 'Unknown');
        }

        if (!$error) {
            $_SESSION['success_message'] = "Service created successfully" . 
                (!$imageUploaded && isset($image) && $image['error'] !== UPLOAD_ERR_NO_FILE ? " but image upload failed" : "");
            header('Location: /pages/seller.php');
            exit();
        }
    }
}

if ($error) {
    $_SESSION['error_message'] = $error;
    $_SESSION['show_add_service'] = true;
    header('Location: /pages/seller.php');
    exit();
}
