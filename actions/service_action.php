<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/file_uploader.php';
require_once __DIR__ . '/../database/classes/service.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user = Auth::getInstance()->getUser();

if (!$user || !$user['is_seller']) {
    header('Location: /pages/login.php');
    exit();
}

// Handle AJAX GET request for service details
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get' && isset($_GET['id'])) {
    $serviceId = intval($_GET['id']);
    $service = Service::getServiceById($serviceId);

    if (!$service || $service->getSeller() !== $user['id']) {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(['error' => 'You can only edit your own services']);
        exit();
    }

    $serviceData = [
        'id' => $service->getId(),
        'name' => $service->getName(),
        'description' => $service->getDescription(),
        'price' => $service->getPrice(),
        'category' => $service->getCategory(),
        'image' => $service->getImage()
    ];

    header('Content-Type: application/json');
    echo json_encode($serviceData);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit();
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : 'add';

$error = null;

try {
    $db = Database::getInstance();

    switch ($action) {
        case 'add':
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $categoryId = $_POST['category_id'] ?? '';
            $image = $_FILES['image'] ?? null;

            if (empty($name) || empty($description) || empty($price) || empty($categoryId)) {
                $error = "Name, description, price and category are required";
                break;
            } else if (!is_numeric($price) || $price <= 0) {
                $error = "Price must be a positive number";
                break;
            }

            $created = Service::createService(
                $name,
                $description,
                (float)$price,
                $user['id'],
                (int)$categoryId
            );

            if (!$created) {
                $error = "Failed to create service";
                break;
            }

            $serviceId = (int)$db->lastInsertId();
            $imageUploaded = false;

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
                    Service::delete($serviceId);
                    break;
                }
            } else if (isset($image) && $image['error'] !== UPLOAD_ERR_NO_FILE) {
                $error = "Image upload error: " . ($image['error'] ?? 'Unknown');
                Service::delete($serviceId);
                break;
            }

            $_SESSION['success_message'] = "Service created successfully" .
                (!$imageUploaded && isset($image) && $image['error'] !== UPLOAD_ERR_NO_FILE ? " but image upload failed" : "");
            break;

        case 'edit':
            if ($id <= 0) {
                $error = "Invalid service ID";
                break;
            }

            $service = Service::getServiceById($id);
            if (!$service || $service->getSeller() !== $user['id']) {
                $error = "You can only edit your own services";
                break;
            }

            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $categoryId = $_POST['category_id'] ?? '';
            $image = $_FILES['image'] ?? null;
            $currentImage = $_POST['current_image'] ?? '';

            if (empty($name) || empty($description) || empty($price) || empty($categoryId)) {
                $error = "Name, description, price and category are required";
                break;
            } else if (!is_numeric($price) || $price <= 0) {
                $error = "Price must be a positive number";
                break;
            }

            // Use current image if provided and valid, otherwise use the service's stored image
            $imagePath = !empty($currentImage) ? $currentImage : $service->getImage();

            if (isset($image) && $image['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '/database/assets/services/' . $id . '/';
                $uploader = new FileUploader('image', $uploadDir, ['image/jpeg', 'image/png'], 5242880, '2:1');
                $uploadedImagePath = $uploader->uploadFile($image);

                if ($uploadedImagePath !== null) {
                    $imagePath = $uploadedImagePath;
                } else {
                    $error = "Image upload failed: " . implode(', ', $uploader->getErrors());
                    break;
                }
            }

            if (Service::update(
                $id,
                $name,
                $description,
                (float)$price,
                (int)$categoryId,
                $imagePath,
            )) {
                $_SESSION['success_message'] = "Service updated successfully";
            } else {
                $error = "Failed to update service";
            }
            break;

        case 'delete':
            if ($id <= 0) {
                $error = "Invalid service ID";
                break;
            }

            $service = Service::getServiceById($id);
            if (!$service || $service->getSeller() !== $user['id']) {
                $error = "You can only delete your own services";
                break;
            }

            if (Service::delete($id)) {
                $_SESSION['success_message'] = "Service successfully deleted";
            } else {
                $error = "Failed to delete service";
            }
            break;

        default:
            $error = "Invalid action specified";
            break;
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
} catch (Exception $e) {
    $error = "Error: " . $e->getMessage();
}

if ($error) {
    $_SESSION['error_message'] = $error;
    if ($action === 'add') {
        $_SESSION['show_add_service'] = true;
    }
}

$section = isset($_POST['section']) ? $_POST['section'] : 'listings';
header('Location: /pages/seller.php?section=' . $section);
exit();
