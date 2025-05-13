<?php
// Service Details Page
require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../components/button/button.php');

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
$service['image'] = '/assets/placeholder.png';
$service['profile_pic'] = '/assets/default_profile_pic.png';

drawHeader();
?>
<main>
  <?php if ($service): ?>
    <div class="service-details-container">
      <div class="service-header">
        <div class="service-pricing-block">
          <?php if (isset($service['rating'])): ?>
            <div class="rating-section">
              <span class="service-rating">
                <i class="ph-bold ph-star-fill"></i>
                <?= number_format($service['rating'], 1) ?>/5
              </span>
            </div>
          <?php endif; ?>
          <div class="price-section">
            <?php
              Button::start(['variant' => 'primary', 'class' => 'service-price-button']);
              if (isset($service['price'])) {
                ButtonIcon::render('ph-bold ph-currency-eur');
              }
              echo '<span>' . (isset($service['price']) ? htmlspecialchars(number_format($service['price'], 2)) : '230') . '€</span>';
              Button::end();
            ?>
          </div>
        </div>
      </div>
      
      <div class="service-image">
        <img src="<?= htmlspecialchars($service['image']) ?>"
          alt="<?= isset($service['name']) ? htmlspecialchars($service['name']) : 'Service' ?>">
      </div>
      <div class="service-info">
        <h1><?= htmlspecialchars($service['name']) ?></h1>
        <div class="service-meta-info">
          <h2>by <?= htmlspecialchars($service['seller_name']) ?></h2>
          <span class="service-category"><i class="ph-bold ph-tag"></i> <?= htmlspecialchars($service['category_name']) ?></span>
        </div>
        <p class="service-description"><?= nl2br(htmlspecialchars($service['description'])) ?></p>
        
        <div class="seller-profile">
          <img src="<?= htmlspecialchars($service['profile_pic']) ?>" 
               alt="<?= htmlspecialchars($service['seller_name']) ?>" 
               class="seller-pic">
          <div class="seller-bio">
            <strong>About the seller:</strong>
            <p><?= htmlspecialchars($service['bio']) ?></p>
          </div>
        </div>
        
        <div class="service-actions">
          <button class="contact-button btn btn-secondary">
            <i class="ph-bold ph-chat-text"></i>
            Contact Seller
          </button>
        </div>
      </div>
    </div>
  <?php else: ?>
    <div class="service-not-found">
      <i class="ph-bold ph-magnifying-glass"></i>
      <h2>Service Not Found</h2>
      <p>We couldn't find the service you're looking for.</p>
      <a href="/" class="btn btn-primary">Back to Home</a>
    </div>
  <?php endif; ?>
</main>
<?php drawFooter(); ?>
