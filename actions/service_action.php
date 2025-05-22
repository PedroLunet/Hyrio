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

if (empty($name) || empty($description) || empty($price) || empty($categoryId) || empty($image)) {
    $error = "All fields are required";
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

        if (isset($image) && $image['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '/database/assets/services/' . $serviceId . '/';
            $uploader = new FileUploader('image', $uploadDir, ['image/jpeg', 'image/png'], 5242880, ImageCropper::ASPECT_RATIO_WIDESCREEN);
            $imagePath = $uploader->uploadFile($image);

            if ($imagePath !== null) {
                $stmt = $db->prepare('UPDATE services SET image = ? WHERE id = ?');
                $stmt->execute([$imagePath, $serviceId]);

                $_SESSION['success_message'] = "Service created successfully";
                header('Location: /pages/seller.php');
                exit();
            } else {
                $error = "Image upload failed: " . implode(', ', $uploader->getErrors());

                $stmt = $db->prepare('DELETE FROM services WHERE id = ?');
                $stmt->execute([$serviceId]);
            }
        } else {
            $error = "Image upload error: " . ($image['error'] ?? 'Unknown');

            $stmt = $db->prepare('DELETE FROM services WHERE id = ?');
            $stmt->execute([$serviceId]);
        }
    }
}

if ($error) {
    $_SESSION['error_message'] = $error;
    $_SESSION['show_add_service'] = true;
    header('Location: /pages/seller.php');
    exit();
}
