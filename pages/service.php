<?php
// Service Details Page
require_once(__DIR__ . '/../includes/common.php');

head();

echo '<link rel="stylesheet" href="/css/service.css">';

// Get service ID from query
$serviceId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch service from database
function getServiceById(int $id): ?array {
    try {
        $db = Database::getInstance();
        $stmt = $db->prepare('
            SELECT services.*, users.name as seller_name, users.profile_pic, users.bio, categories.name as category_name
            FROM services 
            JOIN users ON services.seller = users.id
            JOIN categories ON services.category = categories.id
            WHERE services.id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $service = $stmt->fetch();
        return $service ?: null;
    } catch (PDOException $e) {
        return null;
    }
}

$service = $serviceId ? getServiceById($serviceId) : null;

drawHeader();
?>
<main>
  <?php if ($service): ?>
    <div class="service-details-container">
      <div class="service-image">
        <img src="<?= htmlspecialchars($service['image']) ?>" alt="<?= htmlspecialchars($service['name']) ?>">
      </div>
      <div class="service-info">
        <h1><?= htmlspecialchars($service['name']) ?></h1>
        <h2>by <?= htmlspecialchars($service['seller_name']) ?></h2>
        <p class="service-category">Category: <?= htmlspecialchars($service['category_name']) ?></p>
        <p class="service-description"><?= nl2br(htmlspecialchars($service['description'])) ?></p>
        <div class="service-meta">
          <span class="service-price">Price: <?= number_format($service['price'], 2) ?>€</span>
          <?php if (isset($service['rating'])): ?>
            <span class="service-rating">Rating: <?= number_format($service['rating'], 1) ?>/5</span>
          <?php endif; ?>
        </div>
        <div class="seller-profile">
          <img src="<?= htmlspecialchars($service['profile_pic']) ?>" alt="<?= htmlspecialchars($service['seller_name']) ?>" class="seller-pic">
          <div class="seller-bio">
            <strong>About the seller:</strong>
            <p><?= htmlspecialchars($service['bio']) ?></p>
          </div>
        </div>
      </div>
    </div>
  <?php else: ?>
    <p>Service not found.</p>
  <?php endif; ?>
</main>
<?php drawFooter(); ?>
