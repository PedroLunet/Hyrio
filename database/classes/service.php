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

    public function __construct(int $id, string $name, string $description, float $price, int $seller, int $category, string $image, ?float $rating = null)
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

    /**
     * Create a new service in the database
     */
    public static function createService(string $name, string $description, float $price, int $seller, int $category, string $image = '/assets/placeholder.png', ?float $rating = null): bool
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

    /**
     * Update an existing service in the database
     */
    public function update(): bool
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('
                UPDATE services 
                SET name = ?, description = ?, price = ?, seller = ?, category = ?, image = ?, rating = ?
                WHERE id = ?
            ');
            $stmt->execute([
                $this->name, 
                $this->description, 
                $this->price, 
                $this->seller, 
                $this->category, 
                $this->image, 
                $this->rating,
                $this->id
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Delete a service from the database
     */
    public function delete(): bool
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('DELETE FROM services WHERE id = ?');
            $stmt->execute([$this->id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Get a service by its ID
     */
    public static function getServiceById(int $id): ?Service
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('
                SELECT * FROM services WHERE id = ?
            ');
            $stmt->execute([$id]);
            $serviceData = $stmt->fetch();

            if ($serviceData) {
                return new Service(
                    (int)$serviceData['id'],
                    $serviceData['name'],
                    $serviceData['description'],
                    (float)$serviceData['price'],
                    (int)$serviceData['seller'],
                    (int)$serviceData['category'],
                    $serviceData['image'] ?: '/assets/placeholder.png',
                    isset($serviceData['rating']) ? (float)$serviceData['rating'] : null
                );
            }
            return null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Get service details with seller and category information
     */
    public static function getServiceDetailsById(int $id): ?array
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('
                SELECT services.*, users.name as seller_name, users.profile_pic, users.bio, categories.name as category_name
                FROM services 
                JOIN users ON services.seller = users.id
                JOIN categories ON services.category = categories.id
                WHERE services.id = ?
            ');
            $stmt->execute([$id]);
            $service = $stmt->fetch();
            
            if ($service) {
                // Set default image if not available
                if (!isset($service['image']) || empty($service['image'])) {
                    $service['image'] = '/assets/placeholder.png';
                }
                
                // Set default profile pic if not available
                if (!isset($service['profile_pic']) || empty($service['profile_pic'])) {
                    $service['profile_pic'] = '/assets/default_profile_pic.png';
                }
                
                // Add a default rating if not set
                if (!isset($service['rating'])) {
                    $service['rating'] = 4.5; // Default rating
                }
            }
            
            return $service ?: null;
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
            
            // Add default image and rating to each service
            foreach ($services as &$service) {
                $service['image'] = '/assets/placeholder.png';
                // Add a default rating if not set
                if (!isset($service['rating'])) {
                    $service['rating'] = 4.5;
                }
            }
            
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
            
            // Add default image and rating to each service
            foreach ($services as &$service) {
                $service['image'] = '/assets/placeholder.png';
                // Add a default rating if not set
                if (!isset($service['rating'])) {
                    $service['rating'] = 4.5;
                }
            }
            
            return $services;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get all services
     */
    public static function getAllServices(): array
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('
                SELECT services.*, users.name as seller_name, categories.name as category_name
                FROM services 
                JOIN users ON services.seller = users.id
                JOIN categories ON services.category = categories.id
            ');
            $stmt->execute();
            $services = $stmt->fetchAll();
            
            // Add default image and rating to each service
            foreach ($services as &$service) {
                $service['image'] = '/assets/placeholder.png';
                // Add a default rating if not set
                if (!isset($service['rating'])) {
                    $service['rating'] = 4.5;
                }
            }
            
            return $services;
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
            
            // Add default image and rating to each service
            foreach ($services as &$service) {
                $service['image'] = '/assets/placeholder.png';
                // Add a default rating if not set
                if (!isset($service['rating'])) {
                    $service['rating'] = 4.5;
                }
            }
            
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
            $stmt = $db->prepare('
                SELECT services.*, users.name as seller_name, categories.name as category_name
                FROM services 
                JOIN users ON services.seller = users.id
                JOIN categories ON services.category = categories.id
                WHERE services.seller = ?
            ');
            $stmt->execute([$sellerId]);
            $services = $stmt->fetchAll();
            
            // Add default image and rating to each service
            foreach ($services as &$service) {
                $service['image'] = '/assets/placeholder.png';
                // Add a default rating if not set
                if (!isset($service['rating'])) {
                    $service['rating'] = 4.5;
                }
            }
            
            return $services;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Search for services by name or description
     */
    public static function searchServices(string $query): array
    {
        try {
            $db = Database::getInstance();
            $searchQuery = '%' . $query . '%';
            $stmt = $db->prepare('
                SELECT services.*, users.name as seller_name, categories.name as category_name
                FROM services 
                JOIN users ON services.seller = users.id
                JOIN categories ON services.category = categories.id
                WHERE services.name LIKE ? OR services.description LIKE ?
            ');
            $stmt->execute([$searchQuery, $searchQuery]);
            $services = $stmt->fetchAll();
            
            // Add default image and rating to each service
            foreach ($services as &$service) {
                $service['image'] = '/assets/placeholder.png';
                // Add a default rating if not set
                if (!isset($service['rating'])) {
                    $service['rating'] = 4.5;
                }
            }
            
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
