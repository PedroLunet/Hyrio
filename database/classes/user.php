<?php

declare(strict_types=1);


require_once(__DIR__ . '/../../includes/database.php');

class User
{
    private int $id;
    private string $name;
    private string $username;
    private string $email;
    private string $role;
    private string $profilePic;
    private string $bio;

    public function __construct(int $id, string $name, string $username, string $email, string $role, string $profilePic, string $bio)
    {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
        $this->profilePic = $profilePic;
        $this->bio = $bio;
    }

    public static function createUser(string $name, string $username, string $email, string $password, string $role = 'user', string $profilePic = 'database/assets/userProfilePic.jpg', string $bio = '')
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('INSERT INTO users (name, username, email, password, role, profile_pic, bio) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$name, $username, $email, password_hash($password, PASSWORD_DEFAULT), $role, $profilePic, $bio]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function updateUser(int $id, string $name, string $username, string $email, string $bio, string $profilePic)
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('UPDATE users SET name = ?, username = ?, email = ?, bio = ?, profile_pic = ? WHERE id = ?');
            $stmt->execute([$name, $username, $email, $bio, $profilePic, $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function deleteUser(int $id)
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$id]);
            self::removeUserFiles($id);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function removeUserFiles(int $id)
    {
        $userDir = __DIR__ . '/../../database/assets/profiles/' . $id;
        if (is_dir($userDir)) {
            array_map('unlink', glob("$userDir/*.*"));
            rmdir($userDir);
        }
    }

    public static function checkPassword(int $id, string $password): bool
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT password FROM users WHERE id = ?');
            $stmt->execute([$id]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                return true;
            }

            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function updatePassword(int $id, string $newPassword)
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('UPDATE users SET password = ? WHERE id = ?');
            $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $id]);
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

    public static function getUserByUsername(string $username): ?User
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user) {
                return new User($user['id'], $user['name'], $user['username'], $user['email'], $user['role'], $user['profile_pic'], $user['bio']);
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

    public function getUsername(): string
    {
        return $this->username;
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
        return '/' . $this->profilePic;
    }

    public function getBio(): string
    {
        return $this->bio;
    }
    
    // Favorite related methods
    public static function addFavorite(int $userId, int $serviceId): bool
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('INSERT OR IGNORE INTO favorites (user_id, service_id) VALUES (?, ?)');
            $stmt->execute([$userId, $serviceId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function removeFavorite(int $userId, int $serviceId): bool
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('DELETE FROM favorites WHERE user_id = ? AND service_id = ?');
            $stmt->execute([$userId, $serviceId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function isFavorite(int $userId, int $serviceId): bool
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT 1 FROM favorites WHERE user_id = ? AND service_id = ? LIMIT 1');
            $stmt->execute([$userId, $serviceId]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function getUserFavorites(int $userId): array
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('
                SELECT s.*, u.name as seller_name, c.name as category_name 
                FROM services s
                JOIN favorites f ON s.id = f.service_id
                JOIN users u ON s.seller = u.id
                JOIN categories c ON s.category = c.id
                WHERE f.user_id = ?
                ORDER BY f.created_at DESC
            ');
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}
