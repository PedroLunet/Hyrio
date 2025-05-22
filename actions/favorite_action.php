<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../includes/auth.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$loggedInUser = Auth::getInstance()->getUser();
if (!$loggedInUser) {
    $_SESSION['error_message'] = "Please log in to manage favorites";
    header('Location: /pages/login.php');
    exit;
}

$serviceId = isset($_POST['serviceId']) ? (int)$_POST['serviceId'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if (!$serviceId) {
    $_SESSION['error_message'] = "Invalid service ID";
    header('Location: ' . $_SERVER['HTTP_REFERER'] ?? '/');
    exit;
}

$userId = $loggedInUser['id'];

switch ($action) {
    case 'add':
        $success = User::addFavorite($userId, $serviceId);
        if ($success) {
            $_SESSION['success_message'] = "Service added to favorites";
        } else {
            $_SESSION['error_message'] = "Failed to add favorite";
        }
        break;

    case 'remove':
        $success = User::removeFavorite($userId, $serviceId);
        if ($success) {
            $_SESSION['success_message'] = "Service removed from favorites";
        } else {
            $_SESSION['error_message'] = "Failed to remove favorite";
        }
        break;

    case 'toggle':
        $isFavorite = User::isFavorite($userId, $serviceId);
        if ($isFavorite) {
            $success = User::removeFavorite($userId, $serviceId);
            if ($success) {
                $_SESSION['success_message'] = "Service removed from favorites";
            } else {
                $_SESSION['error_message'] = "Failed to remove favorite";
            }
        } else {
            $success = User::addFavorite($userId, $serviceId);
            if ($success) {
                $_SESSION['success_message'] = "Service added to favorites";
            } else {
                $_SESSION['error_message'] = "Failed to add favorite";
            }
        }
        break;

    default:
        $_SESSION['error_message'] = "Invalid action";
        break;
}

header('Location: ' . $_SERVER['HTTP_REFERER'] ?? '/');
