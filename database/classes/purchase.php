<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../includes/database.php');

class Purchase
{
  private int $id;
  private int $buyer;
  private int $service;
  private ?string $message;
  private string $status;

  public function __construct(int $id, int $buyer, int $service, ?string $message = null, string $status)
  {
    $this->id = $id;
    $this->buyer = $buyer;
    $this->service = $service;
    $this->message = $message;
    $this->status = $status;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getBuyer(): int
  {
    return $this->buyer;
  }

  public function getService(): int
  {
    return $this->service;
  }

  public function getMessage(): ?string
  {
    return $this->message;
  }

  public function getStatus(): string
  {
    return $this->status;
  }

  public static function create(int $buyer, int $service, ?string $message = null): int
  {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('INSERT INTO purchases (user_id, service_id, message) VALUES (?, ?, ?)');
    $stmt->execute([$buyer, $service, $message]);
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

  public static function getBySeller(int $sellerId): array
  {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM purchases WHERE service_id IN (SELECT id FROM services WHERE seller = ?) ORDER BY purchased_at DESC');
    $stmt->execute([$sellerId]);
    return $stmt->fetchAll();
  }

  public static function cancel(int $purchaseId): bool
  {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('UPDATE purchases SET status = ? WHERE id = ?');
    return $stmt->execute(['cancelled', $purchaseId]);
  }

  public static function complete(int $purchaseId): bool
  {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('UPDATE purchases SET status = ? WHERE id = ?');
    return $stmt->execute(['completed', $purchaseId]);
  }

  public static function getTotalPendingPurchasesBySeller(int $sellerId): int
  {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT COUNT(*) FROM purchases WHERE service_id IN (SELECT id FROM services WHERE seller = ?) AND status = ?');
    $stmt->execute([$sellerId, 'pending']);
    return (int) $stmt->fetchColumn();
  }
}
