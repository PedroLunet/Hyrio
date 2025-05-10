<?php
declare(strict_types=1);

function attemptLogin(string $email, string $password): bool {
    try {
        $db = getDatabaseConnection();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            return true;
        }
        
        return false;
    } catch (PDOException $e) {
        return false;
    }
}

function registerUser(string $name, string $email, string $password, string $role = 'user'): bool|string {
    try {
        $db = getDatabaseConnection();
        
        $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return "Email already registered";
        }
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $profilePic = 'db/assets/userProfilePic.jpg';
        
        $stmt = $db->prepare('INSERT INTO users (name, password, email, role, profile_pic) VALUES (?, ?, ?, ?, ?)');
        $success = $stmt->execute([$name, $hashedPassword, $email, $role, $profilePic]);
        
        return $success;
    } catch (PDOException $e) {
        return "Database error: " . $e->getMessage();
    }
}

function logoutUser(): void {
    session_start();
    session_destroy();
}

function isLoggedIn(): bool {
    session_start();
    return isset($_SESSION['user_id']);
}

function getCurrentUser(): ?array {
    if (!isLoggedIn()) {
        return null;
    }
    
    try {
        $db = getDatabaseConnection();
        $stmt = $db->prepare('SELECT id, name, email, role, profile_pic, bio FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return null;
    }
}
?>