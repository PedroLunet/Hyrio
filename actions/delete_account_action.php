<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../includes/database.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$loggedInUser = Auth::getInstance()->getUser();
$error = null;

if (!$loggedInUser) {
    header('Location: /pages/login.php');
    exit;
}

if (isset($_POST['delete_account'])) {
    $userId = $loggedInUser['id'];

    if (User::delete($userId)) {
        session_destroy();
        header('Location: /');
        exit;
    } else {
        $error = "Failed to delete account. Please try again.";
        header('Location: /pages/profile.php?username=' . urlencode($loggedInUser['username']));
        exit;
    }
} else {
    $error = "Invalid request.";
    header('Location: /pages/profile.php?username=' . urlencode($loggedInUser['username']));
    exit;
}

if ($error) {
    $_SESSION['delete_account_error'] = $error;
    $_SESSION['show_account_settings'] = true;
    header('Location: /pages/profile.php?username=' . urlencode($loggedInUser['username']));
    exit;
}