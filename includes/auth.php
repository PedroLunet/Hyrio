<?php

declare(strict_types=1);

class Auth
{
    private static ?Auth $instance = null;

    public static function getInstance(): Auth
    {
        if (self::$instance === null) {
            self::$instance = new Auth();
        }
        return self::$instance;
    }

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function getUser()
    {
        return $_SESSION['user'] ?? null;
    }

    public function login($user)
    {
        $_SESSION["user"] = $user;
    }

    public function logout()
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
    }
    
}

// Global helper functions for auth
function isLoggedIn(): bool {
    return Auth::getInstance()->getUser() !== null;
}

function getCurrentUser() {
    $userData = Auth::getInstance()->getUser();
    if ($userData) {
        return new User(
            $userData['id'],
            $userData['name'],
            $userData['username'],
            $userData['email'],
            $userData['role'],
            $userData['profile_pic'],
            $userData['bio'] ?? ''
        );
    }
    return null;
}
