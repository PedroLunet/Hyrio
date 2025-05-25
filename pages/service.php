<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../components/button/button.php');
require_once(__DIR__ . '/../components/card/card.php');
require_once(__DIR__ . '/../components/rating/rating.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../database/classes/rating.php');
require_once(__DIR__ . '/../database/classes/purchase.php');
require_once(__DIR__ . '/../database/classes/category.php');
require_once(__DIR__ . '/../includes/auth.php');

head();

echo '<link rel="stylesheet" href="/css/service.css">';
echo '<link rel="stylesheet" href="/components/rating/css/rating.css">';

$service = Service::getServiceById((int) $_GET['id']);
$seller = User::getUserById($service->getSeller());
$category = $service ? Category::getCategoryById($service->getCategory()) : null;
$loggedInUser = Auth::getInstance()->getUser();

$isFavorite = false;
if ($loggedInUser && $service) {
  $isFavorite = User::isFavorite($loggedInUser['id'], $service->getId());
}

// Get rating data
$ratingStats = Rating::getRatingStats($service->getId());
$userRating = null;
$canUserRate = false;
$ratingMessage = '';

if ($loggedInUser) {
  $canRateCheck = Rating::canUserRate($loggedInUser['id'], $service->getId());
  $canUserRate = $canRateCheck['can_rate'];
  $ratingMessage = $canRateCheck['reason'];

  if ($canUserRate) {
    $userRating = Rating::getUserRatingForService($loggedInUser['id'], $service->getId());
  }
}
$allRatings = Rating::getRatingsByServiceId($service->getId());

drawHeader();
?>
<main>
  <?php
  // Display success/error messages
  if (isset($_SESSION['success_message'])): ?>
    <div class="success-message">
      <i class="ph-bold ph-check-circle"></i>
      <?= htmlspecialchars($_SESSION['success_message']) ?>
    </div>
  <?php
    unset($_SESSION['success_message']);
  endif;

  if (isset($_SESSION['error_message'])): ?>
    <div class="error-message">
      <i class="ph-bold ph-warning-circle"></i>
      <?= htmlspecialchars($_SESSION['error_message']) ?>
    </div>
  <?php
    unset($_SESSION['error_message']);
  endif;
  ?>

  <?php
  if ($service):
  ?>
    <div class="service-details-container">
      <div class="service-header">
        <div class="service-pricing-block">
          <?php if ($ratingStats['total_ratings'] > 0): ?>
            <div class="rating-section">
              <div class="stars-container">
                <?php RatingComponent::renderStars((float) $ratingStats['average_rating'], (int) $ratingStats['total_ratings']); ?>
              </div>
            </div>
          <?php endif; ?>
          <div class="favorite-price-container">
            <form action="/actions/favorite_action.php" method="post" class="favorite-form">
              <input type="hidden" name="serviceId" value="<?php echo $service->getId(); ?>">
              <input type="hidden" name="action" value="toggle">
              <button type="submit" class="favorite-button <?php echo $isFavorite ? 'active' : ''; ?>"
                aria-label="<?php echo $isFavorite ? 'Remove from favorites' : 'Add to favorites'; ?>">
                <i class="ph-bold ph-heart"></i>
              </button>
            </form>
            <div class="price-section">
              <form action="/pages/checkout.php" method="get" style="display:inline;">
                <input type="hidden" name="id" value="<?= $service->getId() ?>">
                <button type="submit" class="service-price-button">
                  <i class="ph-bold ph-currency-eur"></i>
                  <span><?= htmlspecialchars(number_format($service->getPrice(), 2)) ?>€</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="service-basic-info">
        <h1><?= htmlspecialchars($service->getName()) ?></h1>
        <h2>by <?= htmlspecialchars($seller->getName()) ?></h2>
        <div class="service-meta-info">
          <span class="service-category">
            <i class="ph-bold ph-tag"></i>
            <?= htmlspecialchars($category ? $category->getName() : 'Unknown Category') ?>
          </span>
        </div>
      </div>

      <div class="service-image">
        <img src="<?= htmlspecialchars($service->getImage()) ?>" alt="<?= htmlspecialchars($service->getName()) ?>">
      </div>
      <p class="service-description"><?= nl2br(htmlspecialchars($service->getDescription())) ?></p>
      <div class="service-info">
        <h1><?= htmlspecialchars($service->getName()) ?></h1>
        <div class="service-meta-info">
          <h2>by <?= htmlspecialchars($seller->getName()) ?></h2>
          <span class="service-category">
            <i class="ph-bold ph-tag"></i>
            <?= htmlspecialchars($category ? $category->getName() : 'Unknown Category') ?>
          </span>
        </div>

        <a href="/pages/profile.php?username=<?= $seller->getUsername() ?>" class="seller-link">
          <div class="seller-profile">
            <img src="<?= htmlspecialchars($seller->getProfilePic()) ?>" alt="<?= htmlspecialchars($seller->getName()) ?>"
              class="seller-pic">
            <div class="seller-bio">
              <strong>About the seller:</strong>
              <p><?= htmlspecialchars($seller->getBio()) ?></p>
            </div>
          </div>
        </a>

        <div class="service-actions">
          <?php if ($loggedInUser && $loggedInUser['id'] !== $service->getSeller()): ?>
            <form action="/actions/messages_action.php" method="POST">
              <input type="hidden" name="user_id" value="<?php echo $service->getSeller(); ?>">
              <button type="submit" class="contact-button btn btn-secondary" title="Open a conversation with this seller">
                <i class="ph-bold ph-chat-text"></i>
                Contact Seller
              </button>
            </form>
          <?php endif; ?>
        </div> 
      </div>
    </div>

    <!-- Rating Section -->
    <div class="service-rating-section">
      <div class="rating-actions">
        <h2>Ratings & Reviews</h2>
        <?php if ($loggedInUser && $canUserRate): ?>
          <?php RatingComponent::renderRatingButton($service->getId(), $userRating); ?>
        <?php elseif ($loggedInUser && !$canUserRate): ?>
          <div class="rating-restriction-message">
            <i class="ph-bold ph-info"></i>
            <span><?= htmlspecialchars($ratingMessage) ?></span>
          </div>
        <?php elseif (!$loggedInUser): ?>
          <div class="rating-restriction-message">
            <i class="ph-bold ph-info"></i>
            <span>Please <a href="/pages/login.php">log in</a> to rate this service</span>
          </div>
        <?php endif; ?>
      </div>

      <!-- Rating Statistics -->
      <?php RatingComponent::renderRatingStats($ratingStats); ?>

      <!-- Reviews List -->
      <?php RatingComponent::renderReviews($allRatings); ?>
    </div>

    <!-- Related services from the same category -->
    <div class="related-services">
      <h2>More services in <?= htmlspecialchars($category ? $category->getName() : 'this category') ?></h2>
      <div class="services-row">
        <?php
        $categoryServices = Service::getRelatedServicesByCategory($service->getCategory(), $service->getId());
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
      <h2>More from <?= htmlspecialchars($seller->getName()) ?></h2>
      <div class="services-row">
        <?php
        $sellerServices = Service::getRelatedServicesBySeller($service->getSeller(), $service->getId());
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

<!-- Rating Overlay -->
<?php if ($loggedInUser): ?>
  <div id="rating-overlay-container"></div>
<?php endif; ?>

<?php drawFooter(); ?>

<script src="/js/rating.js"></script>
<script src="/js/overlay.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const favoriteForms = document.querySelectorAll('.favorite-form');

    favoriteForms.forEach(form => {
      form.addEventListener('submit', function() {
        console.log('Favorite form submitted');
      });
    });

    // Initialize overlay system
    OverlaySystem.init();
  });

  // Function to open rating overlay
  function openRatingOverlay(serviceId) {
    const container = document.getElementById('rating-overlay-container');
    if (container) {
      // Show loading state
      container.innerHTML = `
        <div class="overlay rating-overlay" id="rating-overlay" style="display: block; opacity: 1;">
          <div class="overlay-content" style="transform: translateY(0); opacity: 1;">
            <div class="overlay-header">
              <h2>Loading...</h2>
              <button class="close-btn" onclick="OverlaySystem.close('rating-overlay')" aria-label="Close">✕</button>
            </div>
            <div class="overlay-body" style="text-align: center; padding: 2rem;">
              <i class="ph-bold ph-spinner" style="font-size: 2rem; animation: spin 1s linear infinite;"></i>
              <p>Loading rating form...</p>
            </div>
          </div>
        </div>
      `;

      // Load the overlay content via fetch
      fetch(`/overlays/rating.php?service_id=${serviceId}`)
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.text();
        })
        .then(html => {
          container.innerHTML = html;
          OverlaySystem.open('rating-overlay');
          // Initialize rating form after overlay content is loaded
          if (typeof initializeRatingForms === 'function') {
            initializeRatingForms();
          }
        })
        .catch(error => {
          console.error('Error loading rating overlay:', error);
          container.innerHTML = `
            <div class="overlay rating-overlay" id="rating-overlay" style="display: block; opacity: 1;">
              <div class="overlay-content" style="transform: translateY(0); opacity: 1;">
                <div class="overlay-header">
                  <h2>Error</h2>
                  <button class="close-btn" onclick="OverlaySystem.close('rating-overlay')" aria-label="Close">✕</button>
                </div>
                <div class="overlay-body" style="text-align: center; padding: 2rem;">
                  <i class="ph-bold ph-warning" style="font-size: 2rem; color: #e74c3c;"></i>
                  <p>Error loading rating form. Please try again.</p>
                  <button class="btn btn-secondary" onclick="OverlaySystem.close('rating-overlay')">Close</button>
                </div>
              </div>
            </div>
          `;
        });
    }
  }
</script>