<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../database/classes/user.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$loggedInUser = Auth::getInstance()->getUser();
if (!$loggedInUser) {
    header('Location: /pages/login.php');
    exit;
}

$name = $_POST['name'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$bio = $_POST['bio'] ?? '';
$currentPassword = $_POST['current_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$profilePic = "database/assets/userProfilePic.jpg";
$error = null;

if (empty($name) || empty($username) || empty($email)) {
    $error = "All fields are required";
} else if ($newPassword !== '' || $confirmPassword !== '') {
    if ($currentPassword === '') {
        $error = "Current password is required to change password";
    } else if (!User::checkPassword($loggedInUser['id'], $currentPassword)) {
        $error = "Current password is incorrect";
    } else if ($newPassword !== $confirmPassword) {
        $error = "New passwords don't match";
    } else {
        User::updateUser($loggedInUser['id'], $name, $username, $email, $bio, $profilePic);
        User::updatePassword($loggedInUser['id'], $newPassword);
    }
} else {
    User::updateUser($loggedInUser['id'], $name, $username, $email, $bio, $profilePic);
}

if (!$error) {
    $_SESSION['user'] = [
        'id' => $loggedInUser['id'],
        'name' => $name,
        'username' => $username,
        'email' => $email,
        'bio' => $bio,
        'profile_pic' => $profilePic,
    ];
}

if ($error) {
    $_SESSION['update_account_settings_error'] = $error;
    $_SESSION['show_account_settings'] = true;
    header('Location: /pages/profile.php?username=' . urlencode($username));
    exit;
}

header('Location: /pages/profile.php?username=' . urlencode($username));
exit;
