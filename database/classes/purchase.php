<?php
// filepath: /Users/pedrolunet/feup/ltw/database/classes/purchase.php

declare(strict_types=1);

require_once(__DIR__ . '/../../includes/database.php');

class Purchase
{
  public static function create(int $userId, int $serviceId, float $price): int
  {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('INSERT INTO purchases (user_id, service_id, price) VALUES (?, ?, ?)');
    $stmt->execute([$userId, $serviceId, $price]);
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
}
