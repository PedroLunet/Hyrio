<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../database/classes/purchase.php';
require_once __DIR__ . '/../database/classes/service.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user = Auth::getInstance()->getUser();

if (!$user) {
    header('Location: /pages/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit();
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

$error = null;

try {
    if ($id <= 0) {
        $error = "Invalid purchase ID";
    } else {
        $purchase = Purchase::getById($id);

        if (!$purchase) {
            $error = "Purchase not found";
        } else {
            $service = Service::getServiceById((int)$purchase['service_id']);

            $isSeller = $service && $service->getSeller() === $user['id'];
            $isBuyer = $purchase['user_id'] == $user['id'];
            $isAdmin = $user['role'] === 'admin';

            if (!$isSeller && !$isBuyer && !$isAdmin) {
                $error = "You are not authorized to perform this action";
            } else {
                switch ($action) {
                    case 'cancel':
                        if (!$isSeller && !$isAdmin) {
                            $error = "Only sellers can cancel orders";
                            break;
                        }

                        if ($purchase['status'] !== 'pending') {
                            $error = "Only pending orders can be cancelled";
                            break;
                        }

                        if (Purchase::cancel($id)) {
                            $_SESSION['success_message'] = "Order has been cancelled successfully";
                        } else {
                            $error = "Failed to cancel the order";
                        }
                        break;

                    case 'complete':
                        if (!$isSeller && !$isAdmin) {
                            $error = "Only sellers can complete orders";
                            break;
                        }

                        if ($purchase['status'] !== 'pending') {
                            $error = "Only pending orders can be completed";
                            break;
                        }

                        if (Purchase::complete($id)) {
                            $_SESSION['success_message'] = "Order has been completed successfully";
                        } else {
                            $error = "Failed to complete the order";
                        }
                        break;

                    default:
                        $error = "Invalid action specified";
                        break;
                }
            }
        }
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
} catch (Exception $e) {
    $error = "Error: " . $e->getMessage();
}

if ($error) {
    $_SESSION['error_message'] = $error;
}

if ($isSeller ?? false) {
    $section = isset($_POST['section']) ? $_POST['section'] : 'orders';
    header('Location: /pages/seller.php?section=' . $section);
} else {
    header('Location: /pages/profile.php');
}
exit();
