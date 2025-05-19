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
   * Render a single card with service data
   * 
   * @param array $service The service data to display in the card
   * @return void
   */
  public static function render($service = null)
  {
    self::includeCSS();

    // Include the button component for price button
    require_once(__DIR__ . '/../button/button.php');

    // Generate service ID for the link
    $serviceId = isset($service['id']) ? (int) $service['id'] : 0;

    ?>
    <a href="/pages/service.php?id=<?= $serviceId ?>" class="card-link">
      <div class="card" id="container">
        <div class="card-rating">
          <i class="ph-fill ph-star"></i>
          <span><?= isset($service['rating']) ? htmlspecialchars(number_format($service['rating'], 1)) : '4.5' ?></span>
        </div>

        <div class="image-container">
          <img src="<?= isset($service['image']) ? htmlspecialchars($service['image']) : '../assets/placeholder.png' ?>"
            alt="<?= isset($service['name']) ? htmlspecialchars($service['name']) : 'Service' ?>">
        </div>

        <div id="label">
          <div id="titles">
            <h3><?= isset($service['name']) ? htmlspecialchars($service['name']) : 'i build minecraft servers' ?></h3>
            <p><?= isset($service['seller_name']) ? htmlspecialchars($service['seller_name']) : 'bald man' ?></p>
          </div>
          <?php
          Button::start(['variant' => 'primary', 'class' => 'price-button']);
          if (isset($service['price'])) {
            ButtonIcon::render('ph-bold ph-currency-eur');
          }
          echo '<span>' . (isset($service['price']) ? htmlspecialchars(number_format($service['price'], 2)) : '230') . '€</span>';
          Button::end();
          ?>
        </div>
      </div>
    </a>
    <?php
  }

  /**
   * Render a grid of cards from services data
   * 
   * @param array $services Array of service data
   * @return void
   */
  public static function renderGrid($services = [])
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
    echo '<div class="card-grid">';
    foreach ($services as $service) {
      self::render($service);
    }
    echo '</div>';
  }
}
?>