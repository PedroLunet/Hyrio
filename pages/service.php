<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../components/button/button.php');
require_once(__DIR__ . '/../components/card/card.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../includes/auth.php');

head();

echo '<link rel="stylesheet" href="/css/service.css">';

$service = Service::getServiceById((int)$_GET['id']);
$seller = User::getUserById($service->getSeller());
$loggedInUser = Auth::getInstance()->getUser();

$isFavorite = false;
if ($loggedInUser && $service) {
  $isFavorite = User::isFavorite($loggedInUser['id'], $service->getId());
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
          <div class="rating-section">
            <div class="stars-container">
              <i class="ph-fill ph-star star-filled"></i>
              <span class="rating-value"><?= number_format(floatval($service->getRating()), 1) ?></span>
            </div>
          </div>
          <div class="favorite-price-container">
            <form action="/actions/favorite_action.php" method="post" class="favorite-form">
              <input type="hidden" name="serviceId" value="<?php echo $service->getId(); ?>">
              <input type="hidden" name="action" value="toggle">
              <button type="submit" class="favorite-button <?php echo $isFavorite ? 'active' : ''; ?>" aria-label="<?php echo $isFavorite ? 'Remove from favorites' : 'Add to favorites'; ?>">
                <i class="ph-bold ph-heart"></i>
              </button>
            </form>
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
          <h2>by <?= htmlspecialchars($seller->getName()) ?></h2>
          <span class="service-category"><i class="ph-bold ph-tag"></i> <?= htmlspecialchars("Category") ?></span>
        </div>
        <p class="service-description"><?= nl2br(htmlspecialchars($service->getDescription())) ?></p>

        <div class="seller-profile">
          <img src="<?= htmlspecialchars($seller->getProfilePic()) ?>"
            alt="<?= htmlspecialchars($seller->getName()) ?>"
            class="seller-pic">
          <div class="seller-bio">
            <strong>About the seller:</strong>
            <p><?= htmlspecialchars($seller->getBio()) ?></p>
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
      <h2>More services in <?= htmlspecialchars("Category") ?></h2>
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
<?php drawFooter(); ?>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const favoriteForms = document.querySelectorAll('.favorite-form');

    favoriteForms.forEach(form => {
      form.addEventListener('submit', function() {
        console.log('Favorite form submitted');
      });
    });
  });
</script>