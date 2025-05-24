<?php
// filepath: /Users/pedrolunet/feup/ltw/database/classes/purchase.php

declare(strict_types=1);

require_once(__DIR__ . '/../../includes/database.php');

class Purchase
{
  public static function create(int $userId, int $serviceId, float $price, ?string $message = null): int
  {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('INSERT INTO purchases (user_id, service_id, price, message) VALUES (?, ?, ?, ?)');
    $stmt->execute([$userId, $serviceId, $price, $message]);
    return (int) $db->lastInsertId();
  }

  public static function getById(int $id): ?array
  {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM purchases WHERE id = ?');
    $stmt->execute([$id]);
    $purchase = $stmt->fetch();
    return $purchase ? $purchase : null;
  }

  public static function getByUser(int $userId): array
  {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM purchases WHERE user_id = ? ORDER BY purchased_at DESC');
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
  }

  /**
   * Check if a user has purchased a specific service
   */
  public static function hasPurchased(int $userId, int $serviceId): bool
  {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT COUNT(*) FROM purchases WHERE user_id = ? AND service_id = ?');
    $stmt->execute([$userId, $serviceId]);
    return $stmt->fetchColumn() > 0;
  }

  /**
   * Get all purchases for a specific service
   */
  public static function getByService(int $serviceId): array
  {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM purchases WHERE service_id = ? ORDER BY purchased_at DESC');
    $stmt->execute([$serviceId]);
    return $stmt->fetchAll();
  }
}
