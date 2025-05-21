<?php
// Service Details Page
require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../components/button/button.php');
require_once(__DIR__ . '/../components/card/card.php');
require_once(__DIR__ . '/../database/classes/service.php');

head();

echo '<link rel="stylesheet" href="/css/service.css">';
echo '<script src="/js/favorite.js" defer></script>';

// Get service ID from query
$serviceId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Using the Service class from /database/classes/service.php
$service = $serviceId ? Service::getServiceDetailsById($serviceId) : null;

// Ensure service exists before adding defaults
  if ($service) {
      $service['image'] = '/assets/placeholder.png';
      $service['profile_pic'] = '/assets/default_profile_pic.png';
      
      // Make sure we have a rating for testing (if not already set)
      if (!isset($service['rating'])) {
          $service['rating'] = 4.5; // Default rating for testing
      }
  }

drawHeader();
?>
<main>
  <?php if ($service): ?>
    <div class="service-details-container">
      <div class="service-header">
        <div class="service-pricing-block">
          <?php if (isset($service['rating'])): ?>
            <div class="rating-section">
              <div class="stars-container">
                <i class="ph-fill ph-star star-filled"></i>
                <span class="rating-value"><?= number_format(floatval($service['rating']), 1) ?></span>
              </div>
            </div>
          <?php endif; ?>
          <div class="favorite-price-container">
            <button class="favorite-button" id="favoriteBtn" aria-label="Add to favorites">
              <i class="ph-bold ph-heart"></i>
            </button>
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
    
    <!-- Related services from the same category -->
    <div class="related-services">
      <h2>More services in <?= htmlspecialchars($service['category_name']) ?></h2>
      <div class="services-row">
        <?php
        $categoryServices = Service::getRelatedServicesByCategory($service['category'], $serviceId);
        if (!empty($categoryServices)) {
          foreach ($categoryServices as $relatedService) {
            Card::render($relatedService);
          }
        } else {
          echo '<p class="none">No related services found in this category.</p>';
        }
        ?>
      </div>
    </div>
    
    <!-- Other services from the same seller -->
    <div class="related-services">
      <h2>More from <?= htmlspecialchars($service['seller_name']) ?></h2>
      <div class="services-row">
        <?php
        $sellerServices = Service::getRelatedServicesBySeller($service['seller'], $serviceId);
        if (!empty($sellerServices)) {
          foreach ($sellerServices as $sellerService) {
            Card::render($sellerService);
          }
        } else {
          echo '<p class="none">No other services available from this seller.</p>';
        }
        ?>
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
