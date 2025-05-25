<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../includes/database.php');

class Rating
{
  private int $id;
  private int $userId;
  private int $serviceId;
  private int $rating;
  private ?string $review;
  private string $createdAt;

  public function __construct(int $id, int $userId, int $serviceId, int $rating, ?string $review, string $createdAt)
  {
    $this->id = $id;
    $this->userId = $userId;
    $this->serviceId = $serviceId;
    $this->rating = $rating;
    $this->review = $review;
    $this->createdAt = $createdAt;
  }

  // Getters
  public function getId(): int
  {
    return $this->id;
  }
  public function getUserId(): int
  {
    return $this->userId;
  }
  public function getServiceId(): int
  {
    return $this->serviceId;
  }
  public function getRating(): int
  {
    return $this->rating;
  }
  public function getReview(): ?string
  {
    return $this->review;
  }
  public function getCreatedAt(): string
  {
    return $this->createdAt;
  }

  /**
   * Add or update a rating for a service
   */
  public static function addOrUpdateRating(int $userId, int $serviceId, int $rating, ?string $review = null): bool
  {
    $db = Database::getInstance();

    try {
      // Check if rating already exists
      $stmt = $db->prepare('SELECT id FROM ratings WHERE user_id = ? AND service_id = ?');
      $stmt->execute([$userId, $serviceId]);
      $existingRating = $stmt->fetch();

      if ($existingRating) {
        // Update existing rating
        $stmt = $db->prepare('UPDATE ratings SET rating = ?, review = ? WHERE user_id = ? AND service_id = ?');
        $result = $stmt->execute([$rating, $review, $userId, $serviceId]);
      } else {
        // Insert new rating
        $stmt = $db->prepare('INSERT INTO ratings (user_id, service_id, rating, review) VALUES (?, ?, ?, ?)');
        $result = $stmt->execute([$userId, $serviceId, $rating, $review]);
      }

      if ($result) {
        // Update the service's average rating
        self::updateServiceAverageRating($serviceId);
        return true;
      }

      return false;
    } catch (PDOException $e) {
      error_log("Error adding/updating rating: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Check if a user can rate a service (must have purchased it and not be the seller)
   */
  public static function canUserRate(int $userId, int $serviceId): array
  {
    require_once(__DIR__ . '/purchase.php');
    require_once(__DIR__ . '/service.php');

    // Check if service exists
    $service = Service::getServiceById($serviceId);
    if (!$service) {
      return ['can_rate' => false, 'reason' => 'Service not found'];
    }

    // Check if user is the seller
    if ($service->getSeller() === $userId) {
      return ['can_rate' => false, 'reason' => 'You cannot rate your own service'];
    }

    // Check if user has purchased the service
    if (!Purchase::hasPurchased($userId, $serviceId)) {
      return ['can_rate' => false, 'reason' => 'You must purchase this service before rating it'];
    }

    return ['can_rate' => true, 'reason' => ''];
  }

  /**
   * Get all ratings for a service
   */
  public static function getRatingsByServiceId(int $serviceId): array
  {
    $db = Database::getInstance();

    try {
      $stmt = $db->prepare('
                SELECT r.*, u.name as user_name, u.profile_pic as user_profile_pic
                FROM ratings r
                JOIN users u ON r.user_id = u.id
                WHERE r.service_id = ?
                ORDER BY r.created_at DESC
            ');
      $stmt->execute([$serviceId]);

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error fetching ratings: " . $e->getMessage());
      return [];
    }
  }

  /**
   * Get user's rating for a specific service
   */
  public static function getUserRatingForService(int $userId, int $serviceId): ?array
  {
    $db = Database::getInstance();

    try {
      $stmt = $db->prepare('SELECT * FROM ratings WHERE user_id = ? AND service_id = ?');
      $stmt->execute([$userId, $serviceId]);

      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result ?: null;
    } catch (PDOException $e) {
      error_log("Error fetching user rating: " . $e->getMessage());
      return null;
    }
  }

  /**
   * Get rating statistics for a service
   */
  public static function getRatingStats(int $serviceId): array
  {
    $db = Database::getInstance();

    try {
      $stmt = $db->prepare('
                SELECT 
                    COUNT(*) as total_ratings,
                    AVG(rating) as average_rating,
                    COUNT(CASE WHEN rating = 5 THEN 1 END) as five_star,
                    COUNT(CASE WHEN rating = 4 THEN 1 END) as four_star,
                    COUNT(CASE WHEN rating = 3 THEN 1 END) as three_star,
                    COUNT(CASE WHEN rating = 2 THEN 1 END) as two_star,
                    COUNT(CASE WHEN rating = 1 THEN 1 END) as one_star
                FROM ratings 
                WHERE service_id = ?
            ');
      $stmt->execute([$serviceId]);

      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result ?: [
        'total_ratings' => 0,
        'average_rating' => 0,
        'five_star' => 0,
        'four_star' => 0,
        'three_star' => 0,
        'two_star' => 0,
        'one_star' => 0
      ];
    } catch (PDOException $e) {
      error_log("Error fetching rating stats: " . $e->getMessage());
      return [
        'total_ratings' => 0,
        'average_rating' => 0,
        'five_star' => 0,
        'four_star' => 0,
        'three_star' => 0,
        'two_star' => 0,
        'one_star' => 0
      ];
    }
  }

  /**
   * Update the average rating for a service
   */
  private static function updateServiceAverageRating(int $serviceId): void
  {
    $db = Database::getInstance();

    try {
      $stmt = $db->prepare('
                UPDATE services 
                SET rating = (
                    SELECT AVG(rating) 
                    FROM ratings 
                    WHERE service_id = ?
                )
                WHERE id = ?
            ');
      $stmt->execute([$serviceId, $serviceId]);
    } catch (PDOException $e) {
      error_log("Error updating service average rating: " . $e->getMessage());
    }
  }

  /**
   * Delete a rating
   */
  public static function deleteRating(int $userId, int $serviceId): bool
  {
    $db = Database::getInstance();

    try {
      $stmt = $db->prepare('DELETE FROM ratings WHERE user_id = ? AND service_id = ?');
      $result = $stmt->execute([$userId, $serviceId]);

      if ($result) {
        // Update the service's average rating
        self::updateServiceAverageRating($serviceId);
        return true;
      }

      return false;
    } catch (PDOException $e) {
      error_log("Error deleting rating: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Sync all service ratings with actual rating data
   */
  public static function syncAllServiceRatings(): void
  {
    $db = Database::getInstance();

    try {
      $stmt = $db->prepare('
        UPDATE services 
        SET rating = (
          SELECT AVG(rating) 
          FROM ratings 
          WHERE service_id = services.id
        ) 
        WHERE id IN (SELECT DISTINCT service_id FROM ratings)
      ');
      $stmt->execute();
      
      // Set rating to NULL for services with no ratings
      $stmt = $db->prepare('
        UPDATE services 
        SET rating = NULL 
        WHERE id NOT IN (SELECT DISTINCT service_id FROM ratings)
      ');
      $stmt->execute();
    } catch (PDOException $e) {
      error_log("Error syncing service ratings: " . $e->getMessage());
    }
  }
}
