<?php
// Service Details Page
require_once(__DIR__ . '/../includes/common.php');

head();

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


<style>
.service-details-container {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  background: #fff;
  border-radius: 24px;
  box-shadow: 0 2px 15px 0 var(--shadow);
  padding: 2rem;
  margin: 2rem auto;
  max-width: 1600px;
}
.service-image img {
  width: 320px;
  max-width: 100%;
  border-radius: 20px;
  object-fit: cover;
}
.service-info {
  flex: 1;
  min-width: 250px;
}
.service-info h1 {
  margin-top: 0;
  font-size: 2.2rem;
}
.service-info h2 {
  margin: 0.5rem 0 1rem 0;
  font-size: 1.2rem;
  color: var(--secondary);
}
.service-category {
  font-weight: bold;
  color: var(--primary);
}
.service-description {
  margin: 1.5rem 0;
  font-size: 1.1rem;
}
.service-meta {
  margin-bottom: 1.5rem;
  font-size: 1.1rem;
}
.service-price {
  font-weight: bold;
  color: var(--primary);
  margin-right: 1.5rem;
}
.service-rating {
  color: #888;
}
.seller-profile {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  margin-top: 2rem;
  background: #f7f7f7;
  border-radius: 12px;
  padding: 1rem;
}
.seller-pic {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  object-fit: cover;
}
.seller-bio {
  flex: 1;
  font-size: 1rem;
}
@media (max-width: 700px) {
  .service-details-container {
    flex-direction: column;
    padding: 1rem;
  }
  .service-image img {
    width: 100%;
    margin-bottom: 1rem;
  }
}
</style>

