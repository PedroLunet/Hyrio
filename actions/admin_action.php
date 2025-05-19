<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../includes/database.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../database/classes/category.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user = Auth::getInstance()->getUser();

if (!$user || $user['role'] !== 'admin') {
    header('Location: /pages/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit();
}

$type = isset($_POST['type']) ? $_POST['type'] : '';
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;


if (empty($type) || $id <= 0) {
    $_SESSION['error_message'] = 'Invalid parameters provided';
    header('Location: /pages/admin.php');
    exit();
}

try {
    $db = Database::getInstance();

    switch ($type) {
        case 'user':
            $action = isset($_POST['action']) ? $_POST['action'] : 'delete';

            if ($action === 'promote') {
                if (User::addAdminPrivileges($id)) {
                    $_SESSION['success_message'] = 'User successfully promoted to admin';
                } else {
                    $_SESSION['error_message'] = 'Failed to promote user';
                }
            } elseif ($action === 'demote') {
                if (User::removeAdminPrivileges($id)) {
                    $_SESSION['success_message'] = 'User successfully demoted to user';
                    if ($id === $user['id']) {
                        $_SESSION['user']['role'] = 'user';
                        header('Location: /');
                        exit();
                    }
                } else {
                    $_SESSION['error_message'] = 'Failed to demote user';
                }
            } elseif ($action === 'delete') {
                if (User::delete($id)) {
                    $_SESSION['success_message'] = 'User successfully deleted';
                } else {
                    $_SESSION['error_message'] = 'Failed to delete user';
                }
            } else {
                $_SESSION['error_message'] = 'Invalid action specified';
            }

            break;
        case 'service':
            $service = Service::getServiceById($id);

            if ($service && $service->delete()) {
                $_SESSION['success_message'] = 'Service successfully deleted';
            } else {
                $_SESSION['error_message'] = 'Failed to delete service';
            }

            break;
        case 'category':
            if (Category::delete($id)) {
                $_SESSION['success_message'] = 'Category successfully deleted';
            } else {
                $_SESSION['error_message'] = 'Failed to delete category';
            }
            break;
        case 'ticket':
            $_SESSION['error_message'] = 'Ticket deletion not implemented';
            break;
        case 'comment':
            $_SESSION['error_message'] = 'Comment deletion not implemented';
            break;
        default:
            $_SESSION['error_message'] = 'Invalid type specified';
            break;
    }
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Database error: ' . $e->getMessage();
} catch (Exception $e) {
    $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
}

header('Location: /pages/admin.php');
exit();
