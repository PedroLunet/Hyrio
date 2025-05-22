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
    header('Location: /pages/login.php');
    exit;
}

$serviceId = isset($_POST['serviceId']) ? (int)$_POST['serviceId'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if (!$serviceId) {
    header('Location: ' . $_SERVER['HTTP_REFERER'] ?? '/');
    exit;
}

$userId = $loggedInUser['id'];

switch ($action) {
    case 'add':
        $success = User::addFavorite($userId, $serviceId);
        break;

    case 'remove':
        $success = User::removeFavorite($userId, $serviceId);
        break;

    case 'toggle':
        $isFavorite = User::isFavorite($userId, $serviceId);
        if ($isFavorite) {
            $success = User::removeFavorite($userId, $serviceId);
        } else {
            $success = User::addFavorite($userId, $serviceId);
        }
        break;

    default:
        break;
}

header('Location: ' . $_SERVER['HTTP_REFERER'] ?? '/');
