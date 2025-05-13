<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../database/classes/user.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// This is a placeholder action file for updating account settings
// You would need to implement the actual functionality to update user details in the database

// Check if user is logged in
$loggedInUser = Auth::getInstance()->getUser();
if (!$loggedInUser) {
    header('Location: /pages/login.php');
    exit;
}

// Redirect back to profile page
header('Location: /pages/profile.php?username=' . urlencode($loggedInUser['username']));
exit;
