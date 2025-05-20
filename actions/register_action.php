<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/classes/user.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$name = $_POST['name'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$error = null;

if (empty($name) || empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required";
} else if ($password !== $confirm_password) {
    $error = "Passwords don't match";
} else {
    $user = User::create($name, $username, $email, $password);
    if ($user) {
        header('Location: /pages/login.php');
        exit;
    } else {
        $error = "Registration failed. Please try again.";
    }
}

if ($error) {
    $_SESSION['register_error'] = $error;
    $_SESSION['register_form_data'] = [
        'name' => $name,
        'email' => $email,
        'username' => $username,
    ];
    header('Location: /pages/register.php');
    exit;
}
