<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/classes/user.php');

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$error = null;

if (empty($name) || empty($email) || empty($password)) {
    $error = "All fields are required";
} else {
    $user = User::createUser($name, $email, $password);
    if ($user) {
        header('Location: /pages/login.php');
        exit;
    } else {
        $error = "Registration failed. Please try again.";
    }
}

if ($error) {
    header('Location: /pages/register.php?error=' . urlencode($error));
    exit;
}
