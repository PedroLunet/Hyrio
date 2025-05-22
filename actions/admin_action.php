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

if (!$user || !$user['is_admin']) {
    header('Location: /pages/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit();
}

$type = isset($_POST['type']) ? $_POST['type'] : '';
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : 'delete';

if (!($type === 'category' && $action === 'add') && (empty($type) || $id <= 0)) {
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
                        $_SESSION['user']['is_admin'] = false;
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

            if ($service && Service::delete($service->getId())) {
                $_SESSION['success_message'] = 'Service successfully deleted';
            } else {
                $_SESSION['error_message'] = 'Failed to delete service';
            }

            break;
        case 'category':
            $action = isset($_POST['action']) ? $_POST['action'] : 'delete';

            if ($action === 'add') {
                $name = isset($_POST['name']) ? trim($_POST['name']) : '';

                if (empty($name)) {
                    $_SESSION['error_message'] = 'Category name cannot be empty';
                    break;
                }

                if (Category::create($name)) {
                    $_SESSION['success_message'] = 'Category successfully created';
                } else {
                    $_SESSION['error_message'] = 'Failed to create category';
                }
            } elseif ($action === 'edit') {
                $name = isset($_POST['name']) ? trim($_POST['name']) : '';

                if (empty($name)) {
                    $_SESSION['error_message'] = 'Category name cannot be empty';
                    break;
                }

                if (Category::update($id, $name)) {
                    $_SESSION['success_message'] = 'Category successfully updated';
                } else {
                    $_SESSION['error_message'] = 'Failed to update category';
                }
            } elseif ($action === 'delete') {
                if (Category::delete($id)) {
                    $_SESSION['success_message'] = 'Category successfully deleted';
                } else {
                    $_SESSION['error_message'] = 'Failed to delete category';
                }
            } else {
                $_SESSION['error_message'] = 'Invalid action specified for category';
            }
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

$section = 'users';

if (isset($_SERVER['HTTP_REFERER'])) {
    $refererParams = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
    if ($refererParams) {
        parse_str($refererParams, $queryParams);
        if (isset($queryParams['section'])) {
            $section = $queryParams['section'];
        }
    }
}

if ($type === 'category' && ($action === 'add' || $action === 'edit')) {
    $section = 'categories';
} elseif ($type === 'user') {
    $section = 'users';
} elseif ($type === 'service') {
    $section = 'services';
}

header('Location: /pages/admin.php?section=' . $section);
exit();
