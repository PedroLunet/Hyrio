<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../includes/database.php');

class User
{
    private int $id;
    private string $name;
    private string $username;
    private string $email;
    private bool $isSeller;
    private bool $isAdmin;
    private string $profilePic;
    private string $bio;

    public function __construct(int $id, string $name, string $username, string $email, bool $isSeller, bool $isAdmin, string $profilePic, string $bio)
    {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->isSeller = $isSeller;
        $this->isAdmin = $isAdmin;
        $this->profilePic = $profilePic;
        $this->bio = $bio;
    }

    public static function create(string $name, string $username, string $email, string $password, bool $isSeller = false, bool $isAdmin = false, string $profilePic = '/database/assets/userProfilePic.jpg', string $bio = '')
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('INSERT INTO users (name, username, email, password, is_seller, is_admin, profile_pic, bio) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$name, $username, $email, password_hash($password, PASSWORD_DEFAULT), $isSeller, $isAdmin, $profilePic, $bio]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function update(int $id, string $name, string $username, string $email, string $bio, string $profilePic)
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

    public static function delete(int $id)
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
                return new User($user['id'], $user['name'], $user['username'], $user['email'], (bool)$user['is_seller'], (bool)$user['is_admin'], $user['profile_pic'], $user['bio']);
            }

            return null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function getUserById(int $id): ?User
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute([$id]);
            $user = $stmt->fetch();

            if ($user) {
                return new User($user['id'], $user['name'], $user['username'], $user['email'], (bool)$user['is_seller'], (bool)$user['is_admin'], $user['profile_pic'], $user['bio']);
            }

            return null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function getTotalUsers(): int
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->query('SELECT COUNT(*) FROM users');
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public static function getAllUsers(int $offset, int $limit): array
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM users LIMIT ?, ?');
            $stmt->bindParam(1, $offset, PDO::PARAM_INT);
            $stmt->bindParam(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function addAdminPrivileges(int $id): bool
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('UPDATE users SET is_admin = ? WHERE id = ?');
            $stmt->execute([1, $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function removeAdminPrivileges(int $id): bool
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('UPDATE users SET is_admin = ? WHERE id = ?');
            $stmt->execute([0, $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function addFreelancerStatus(int $id): bool
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('UPDATE users SET is_seller = ? WHERE id = ?');
            $stmt->execute([1, $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function removeFreelancerStatus(int $id): bool
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('UPDATE users SET is_seller = ? WHERE id = ?');
            $stmt->execute([0, $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
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

    public function isSeller(): bool
    {
        return $this->isSeller;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function getRole(): string
    {
        if ($this->isAdmin) return 'admin';
        if ($this->isSeller) return 'freelancer';
        return 'user';
    }

    public function getProfilePic(): string
    {
        return $this->profilePic;
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

    public static function getUserFavorites(int $id): array
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
            $stmt->execute([$id]);
            $services = $stmt->fetchAll();

            return $services;
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function searchUsers(string $query, bool $onlySellers = false, int $limit = 0): array
    {
        try {
            $db = Database::getInstance();

            $searchQuery = '%' . $query . '%';
            $sql = 'SELECT id, name, username, email, is_seller, is_admin, profile_pic, bio 
                   FROM users 
                   WHERE (name LIKE ? OR username LIKE ?)';

            if ($onlySellers) {
                $sql .= ' AND is_seller = 1';
            }

            $sql .= ' ORDER BY name';

            if ($limit > 0) {
                $sql .= ' LIMIT ?';
                $stmt = $db->prepare($sql);
                $stmt->execute([$searchQuery, $searchQuery, $limit]);
            } else {
                $stmt = $db->prepare($sql);
                $stmt->execute([$searchQuery, $searchQuery]);
            }

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}
