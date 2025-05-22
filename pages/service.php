<?php
// Service Details Page
require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../components/button/button.php');
require_once(__DIR__ . '/../components/card/card.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../includes/auth.php');

head();

echo '<link rel="stylesheet" href="/css/service.css">';
echo '<script src="/js/favorite.js" defer></script>';

// Get service ID from query
$serviceId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Using the Service class from /database/classes/service.php
$service = $serviceId ? Service::getServiceById($serviceId) : null;

// Check if the service is favorited by the current user
$isFavorite = false;
$currentUser = null;
if (isLoggedIn()) {
    $currentUser = getCurrentUser();
    if ($currentUser && $serviceId && $service) {
        $isFavorite = User::isFavorite($currentUser->getId(), $serviceId);
    }
}

drawHeader();
?>
<main>
  <?php
  if ($service): 
  ?>
    <div class="service-details-container">
      <div class="service-header">
        <div class="service-pricing-block">
          <?php if ($service->getRating()): ?>
            <div class="rating-section">
              <div class="stars-container">
                <i class="ph-fill ph-star star-filled"></i>
                <span class="rating-value"><?= number_format(floatval($service->getRating()), 1) ?></span>
              </div>
            </div>
          <?php endif; ?>
          <div class="favorite-price-container">
            <button class="favorite-button <?php echo $isFavorite ? 'active' : ''; ?>" id="favoriteBtn" aria-label="<?php echo $isFavorite ? 'Remove from favorites' : 'Add to favorites'; ?>">
              <i class="ph-bold ph-heart"></i>
            </button>
            <div class="price-section">
              <?php
                Button::start(['variant' => 'primary', 'class' => 'service-price-button']);
                ButtonIcon::render('ph-bold ph-currency-eur');
                echo '<span>' . htmlspecialchars(number_format($service->getPrice(), 2)) . '€</span>';
                Button::end();
              ?>
            </div>
          </div>
        </div>
      </div>
      
      <div class="service-image">
        <img src="<?= htmlspecialchars($service->getImage()) ?>"
          alt="<?= htmlspecialchars($service->getName()) ?>">
      </div>
      <div class="service-info">
        <h1><?= htmlspecialchars($service->getName()) ?></h1>
        <div class="service-meta-info">
          <h2>by <?= htmlspecialchars($service->getSeller_name()) ?></h2>
          <span class="service-category"><i class="ph-bold ph-tag"></i> <?= htmlspecialchars($service->getCategory_name()) ?></span>
        </div>
        <p class="service-description"><?= nl2br(htmlspecialchars($service->getDescription())) ?></p>
        
        <div class="seller-profile">  
          <img src="<?= htmlspecialchars($service->getProfilePic()) ?>" 
               alt="<?= htmlspecialchars($service->getSeller_name()) ?>" 
               class="seller-pic">
          <div class="seller-bio">
            <strong>About the seller:</strong>
            <p><?= htmlspecialchars($service->getBio()) ?></p>
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
      <h2>More services in <?= htmlspecialchars($service->getCategory_name()) ?></h2>
      <div class="services-row">
        <?php
        $categoryServices = Service::getRelatedServicesByCategory($service->getCategory(), $serviceId);
        if (!empty($categoryServices)) {
          foreach ($categoryServices as $relatedService) {
            Card::render($relatedService);
          }
        } else {
          echo '<p class="no-related">No related services found in this category.</p>';
        }
        ?>
      </div>
    </div>
    
    <!-- Other services from the same seller -->
    <div class="related-services">
      <h2>More from <?= htmlspecialchars($service->getSeller_name()) ?></h2>
      <div class="services-row">
        <?php
        $sellerServices = Service::getRelatedServicesBySeller($service->getSeller(), $serviceId);
        if (!empty($sellerServices)) {
          foreach ($sellerServices as $sellerService) {
            Card::render($sellerService);
          }
        } else {
          echo '<p class="no-related">No other services available from this seller.</p>';
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
