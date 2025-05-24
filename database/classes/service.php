<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../includes/database.php');

class Service
{
    private int $id;
    private string $name;
    private string $description;
    private float $price;
    private int $seller;
    private int $category;
    private string $image;
    private ?float $rating;

    public function __construct(int $id, string $name, string $description, float $price, int $seller, int $category, string $image, ?float $rating)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->seller = $seller;
        $this->category = $category;
        $this->image = $image;
        $this->rating = $rating;
    }

    public static function createService(string $name, string $description, float $price, int $seller, int $category, string $image = '/assets/placeholder.png', ?float $rating = 0.0): bool
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('
                INSERT INTO services (name, description, price, seller, category, image, rating) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ');
            $stmt->execute([$name, $description, $price, $seller, $category, $image, $rating]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function update(int $id, string $name, string $description, float $price, int $category, string $image): bool
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('UPDATE services SET name = ?, description = ?, price = ?, category = ?, image = ? WHERE id = ?');
            $stmt->execute([$name, $description, $price, $category, $image, $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function delete(int $id): bool
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('DELETE FROM services WHERE id = ?');
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getServiceById(int $id): ?Service
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM services WHERE id = ?');
            $stmt->execute([$id]);
            $service = $stmt->fetch();

            if ($service) {
                return new Service(
                    (int)$service['id'],
                    $service['name'],
                    $service['description'],
                    (float)$service['price'],
                    (int)$service['seller'],
                    (int)$service['category'],
                    $service['image'],
                    isset($service['rating']) ? (float)$service['rating'] : null
                );
            }

            return null;
        } catch (PDOException $e) {
            return null;
        }
    }


    /**
     * Get related services from the same category
     */
    public static function getRelatedServicesByCategory(int $categoryId, int $currentServiceId, int $limit = 4): array
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('
                SELECT services.*, users.name as seller_name, categories.name as category_name
                FROM services 
                JOIN users ON services.seller = users.id
                JOIN categories ON services.category = categories.id
                WHERE services.category = ?
                AND services.id != ?
                ORDER BY RANDOM()
                LIMIT ?
            ');
            $stmt->execute([$categoryId, $currentServiceId, $limit]);
            $services = $stmt->fetchAll();

            return $services;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get other services from the same seller
     */
    public static function getRelatedServicesBySeller(int $sellerId, int $currentServiceId, int $limit = 4): array
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('
                SELECT services.*, users.name as seller_name, categories.name as category_name
                FROM services 
                JOIN users ON services.seller = users.id
                JOIN categories ON services.category = categories.id
                WHERE services.seller = ?
                AND services.id != ?
                ORDER BY RANDOM()
                LIMIT ?
            ');
            $stmt->execute([$sellerId, $currentServiceId, $limit]);
            $services = $stmt->fetchAll();

            return $services;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get all services
     */
    public static function getAllServices(int $offset = 0, int $limit = 0): array
    {
        try {
            $db = Database::getInstance();

            if ($limit === 0) {
                $stmt = $db->query('SELECT * FROM services');
            } else {
                $stmt = $db->prepare('SELECT * FROM services LIMIT ?, ?');
                $stmt->bindParam(1, $offset, PDO::PARAM_INT);
                $stmt->bindParam(2, $limit, PDO::PARAM_INT);
                $stmt->execute();
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get services by category ID
     */
    public static function getServicesByCategory(int $categoryId): array
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('
                SELECT services.*, users.name as seller_name, categories.name as category_name
                FROM services 
                JOIN users ON services.seller = users.id
                JOIN categories ON services.category = categories.id
                WHERE services.category = ?
            ');
            $stmt->execute([$categoryId]);
            $services = $stmt->fetchAll();

            return $services;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get services by seller ID
     */
    public static function getServicesBySeller(int $sellerId): array
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM services WHERE seller = ?');
            $stmt->execute([$sellerId]);
            $services = $stmt->fetchAll();

            return $services;
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function searchServicesWithFilters(
        string $query,
        ?int $categoryId = null,
        ?float $minPrice = null,
        ?float $maxPrice = null,
        ?float $minRating = null
    ): array {
        try {
            $db = Database::getInstance();

            $sql = '
                SELECT services.*, users.name as seller_name, categories.name as category_name
                FROM services 
                JOIN users ON services.seller = users.id
                JOIN categories ON services.category = categories.id
                WHERE 1=1
            ';

            $params = [];

            // Only add search query condition if the query is not empty
            if (!empty($query)) {
                $searchQuery = '%' . $query . '%';
                $sql .= ' AND (services.name LIKE ? OR services.description LIKE ?)';
                $params[] = $searchQuery;
                $params[] = $searchQuery;
            }

            if ($categoryId !== null && $categoryId > 0) {
                $sql .= ' AND services.category = ?';
                $params[] = $categoryId;
            }

            if ($minPrice !== null && $minPrice >= 0) {
                $sql .= ' AND services.price >= ?';
                $params[] = $minPrice;
            }

            if ($maxPrice !== null && $maxPrice > 0) {
                $sql .= ' AND services.price <= ?';
                $params[] = $maxPrice;
            }

            if ($minRating !== null && $minRating > 0) {
                $sql .= ' AND services.rating IS NOT NULL AND services.rating >= ?';
                $params[] = $minRating;
            }

            $debugSQL = $sql;
            $debugParams = $params;

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $services = $stmt->fetchAll();

            return $services;
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function getTotalServices(): int
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->query('SELECT COUNT(*) FROM services');
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public static function getTotalServicesBySeller(int $sellerId): int
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT COUNT(*) FROM services WHERE seller = ?');
            $stmt->execute([$sellerId]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getSeller(): int
    {
        return $this->seller;
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }
}
