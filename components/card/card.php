<?php

/**
 * Card Component
 * 
 * Displays a service card with image, title, seller, and price information
 */
class Card
{
  private static $cssIncluded = false;

  /**
   * Include the CSS for the card component
   */
  public static function includeCSS()
  {
    if (!self::$cssIncluded) {
      echo '<link rel="stylesheet" href="/components/card/css/card.css">';
      self::$cssIncluded = true;
    }
  }

  /**
   * Render a single card with service data (alias for renderService)
   * 
   * @param array $service The service data to display in the card
   * @param bool $showPrice Whether to show the price button (default: true)
   * @return void
   */
  public static function render($service = null, $showPrice = true)
  {
    // Keep the original render method as an alias to renderService for backward compatibility
    self::renderService($service, $showPrice);
  }

  /**
   * Render a single service card
   * 
   * @param array $service The service data to display in the card
   * @param bool $showPrice Whether to show the price button (default: true)
   * @return void
   */
  public static function renderService($service = null, $showPrice = true)
  {
    self::includeCSS();

    // Include the button component for price button
    require_once(__DIR__ . '/../button/button.php');
    require_once(__DIR__ . '/../../database/classes/user.php');

    // Generate service ID for the link
    $serviceId = isset($service['id']) ? (int) $service['id'] : 0;
    $seller = User::getUserById($service['seller']);

?>
    <div class="card">
      <a href="/pages/service.php?id=<?= $serviceId ?>" class="card-link">
        <div class="card-content">
          <div class="rating">
            <i class="ph-fill ph-star"></i>
            <span><?= isset($service['rating']) ? htmlspecialchars(number_format($service['rating'], 1)) : '4.5' ?></span>
          </div>

          <div class="service-image">
            <img src="<?= htmlspecialchars($service['image']) ?>"
              alt="<?= isset($service['name']) ? htmlspecialchars($service['name']) : 'Service' ?>">
          </div>

          <div class="label">
            <div class="titles">
              <h3><?= isset($service['name']) ? htmlspecialchars($service['name']) : 'i build minecraft servers' ?></h3>
              <p><?= htmlspecialchars($seller->getName()) ?></p>
            </div>
            <?php
            if ($showPrice) {
              Button::start(['variant' => 'primary', 'class' => 'price-button']);
              if (isset($service['price'])) {
                ButtonIcon::render('ph-bold ph-currency-eur');
              }
              echo '<span>' . (isset($service['price']) ? htmlspecialchars(number_format($service['price'], 2)) : '230') . '€</span>';
              Button::end();
            }
            ?>
          </div>
        </div>
      </a>
    </div>
  <?php
  }

  /**
   * Render a single user card
   * 
   * @param array $user The user data to display in the card
   * @return void
   */
  public static function renderUser($user = null)
  {
    self::includeCSS();

  ?>
    <div class="card">
      <a href="/pages/profile.php?username=<?= htmlspecialchars($user['username']) ?>" class="card-link">
        <div class="card-content">
          <div class="user-image">
            <img src="<?= htmlspecialchars($user['profile_pic']) ?>"
              alt="<?= htmlspecialchars($user['name']) ?>">
          </div>
          <div class="label">
            <div class="titles">
              <h3><?= htmlspecialchars($user['name']) ?></h3>
              <p class="username">@<?= htmlspecialchars($user['username']) ?></p>
            </div>
            <?php if ((bool)$user['is_seller']): ?>
              <span class="user-badge freelancer">Freelancer</span>
            <?php endif; ?>
          </div>
        </div>
      </a>
    </div>
<?php
  }

  /**
   * Render a grid of user cards
   * 
   * @param array $users Array of user data
   * @return void
   */
  public static function renderUserGrid($users = [])
  {
    self::includeCSS();

    if (empty($users)) {
      echo '<div class="no-users-message">';
      echo '<p>No users found matching your criteria.</p>';
      echo '<p>Try changing your filters or search terms.</p>';
      echo '</div>';
      return;
    }

    echo '<div class="grid">';
    foreach ($users as $user) {
      self::renderUser($user);
    }
    echo '</div>';
  }

  /**
   * Render a grid of cards from services data
   * 
   * @param array $services Array of service data
   * @param bool $showPrice Whether to show price buttons (default: true)
   * @return void
   */
  public static function renderGrid($services = [], $showPrice = true)
  {
    self::includeCSS();

    if (empty($services)) {
      // Display a message instead of a placeholder card
      echo '<div class="no-services-message">';
      echo '<p>No services found in this category.</p>';
      echo '<p>Try selecting a different category or check back later.</p>';
      echo '</div>';
      return;
    }

    // Display services in a grid
    echo '<div class="grid">';
    foreach ($services as $service) {
      self::render($service, $showPrice);
    }
    echo '</div>';
  }
}
?>