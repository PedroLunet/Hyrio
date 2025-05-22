<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../includes/database.php');

class Category
{
    private int $id;
    private string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function create(string $name)
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('INSERT INTO categories (name) VALUES (?)');
            $stmt->execute([$name]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function update(int $id, string $name)
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('UPDATE categories SET name = ? WHERE id = ?');
            $stmt->execute([$name, $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function delete(int $id)
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('DELETE FROM categories WHERE id = ?');
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getAllCategories(int $offset = 0, int $limit = 0): array
    {
        try {
            $db = Database::getInstance();
            
            if ($limit === 0) {
                $stmt = $db->query('SELECT * FROM categories');
            } else {
                $stmt = $db->prepare('SELECT * FROM categories LIMIT ?, ?');
                $stmt->bindParam(1, $offset, PDO::PARAM_INT);
                $stmt->bindParam(2, $limit, PDO::PARAM_INT);
                $stmt->execute();
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function getTotalCategories(): int
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->query('SELECT COUNT(*) FROM categories');
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public static function getCategoryById(int $id): ?Category
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM categories WHERE id = ?');
            $stmt->execute([$id]);
            $category = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($category) {
                return new Category((int)$category['id'], $category['name']);
            }
            return null;
        } catch (PDOException $e) {
            return null;
        }
    }
}
