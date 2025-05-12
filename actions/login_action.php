<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../database/classes/user.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$error = null;

if (empty($email) || empty($password)) {
    $error = "All fields are required";
} else {
    $user = User::loginUser($email, $password);
    if ($user) {
        Auth::getInstance()->login($user);
        header('Location: /');
        exit;
    } else {
        $error = "Invalid email or password";
    }
}

if ($error) {
    $_SESSION['login_error'] = $error;
    $_SESSION['login_form_data'] = [
        'email' => $email
    ];
    header('Location: /pages/login.php');
    exit;
}
