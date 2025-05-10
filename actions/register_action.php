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
    $success = User::createUser($name, $email, $password);
    if ($success) {
        header('Location: /');
        exit;
    } else {
        $error = "Registration failed. Please try again.";
    }
}