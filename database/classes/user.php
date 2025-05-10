<?php

declare(strict_types=1);


require_once(__DIR__ . '/../../includes/database.php');

class User
{
    private int $id;
    private string $name;
    private string $email;
    private string $role;
    private string $profilePic;
    private string $bio;

    public function __construct(int $id, string $name, string $email, string $role, string $profilePic, string $bio)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->profilePic = $profilePic;
        $this->bio = $bio;
    }

    public static function createUser($name, $email, $password, $role = 'user', $profilePic = 'database/assets/userProfilePic.jpg', $bio = '')
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('INSERT INTO users (name, email, password, role, profile_pic, bio) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $role, $profilePic, $bio]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function loginUser($email, $password)
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }

            return null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getProfilePic(): string
    {
        return $this->profilePic;
    }

    public function getBio(): string
    {
        return $this->bio;
    }
}
