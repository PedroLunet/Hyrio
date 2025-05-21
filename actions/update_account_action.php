<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../includes/file_uploader.php');

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
$removeProfilePicture = isset($_POST['remove_profile_picture']) && $_POST['remove_profile_picture'] === '1';
$profilePicture = $loggedInUser['profile_pic'];
$error = null;

if ($removeProfilePicture) {
    $profilePicture = 'database/assets/userProfilePic.jpg';
} else if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
    $uploader = new FileUploader('profile_picture', 'database/assets/profiles/' . $loggedInUser['id'] . '/');
    if ($uploader->hasInitErrors()) {
        $error = implode(' ', $uploader->getErrors());
    } else {
        $uploadedFilePath = $uploader->uploadFile($_FILES['profile_picture']);
        if ($uploadedFilePath) {
            $profilePicture = $uploadedFilePath;
        } else {
            $error = implode(' ', $uploader->getErrors());
        }
    }
}

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
        User::update($loggedInUser['id'], $name, $username, $email, $bio, $profilePicture);
        User::updatePassword($loggedInUser['id'], $newPassword);
    }
} else {
    User::update($loggedInUser['id'], $name, $username, $email, $bio, $profilePicture);
}

if (!$error) {
    $_SESSION['user'] = [
        'id' => $loggedInUser['id'],
        'name' => $name,
        'username' => $username,
        'email' => $email,
        'bio' => $bio,
        'profile_pic' => $profilePicture,
        'is_seller' => $loggedInUser['is_seller'],
        'is_admin' => $loggedInUser['is_admin']
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
