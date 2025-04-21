<?php
/**
 * Navbar Component
 * 
 * Displays the top navigation bar of the website
 */
class Navbar
{
  private static $cssIncluded = false;

  /**
   * Include the CSS for the navbar component
   */
  public static function includeCSS()
  {
    if (!self::$cssIncluded) {
      echo '<link rel="stylesheet" href="components/navbar/css/navbar.css">';
      self::$cssIncluded = true;
    }
  }

  /**
   * Render the navbar
   * 
   * @param int|null $selectedCategoryId Optional currently selected category
   * @return void
   */
  public static function render(?int $selectedCategoryId = null)
  {
    self::includeCSS();

    // Include the button component for login button
    require_once(__DIR__ . '/../button/button.php');
    // Include the categories component for the dropdown
    require_once(__DIR__ . '/../categories/categories.php');

    $categories = Categories::getCategories();
    ?>
    <header id="menu-header">
      <div class="nav-left">
        <img src="assets/logo.png" alt="Logo">
      </div>
      <div class="nav-right">
        <div class="categories-dropdown">
          <button class="dropdown-btn">
            <?php
            if ($selectedCategoryId === null) {
              echo 'All Categories';
            } else {
              foreach ($categories as $category) {
                if ((int) $category['id'] === $selectedCategoryId) {
                  echo htmlspecialchars($category['name']);
                  break;
                }
              }
            }
            ?>
            <span class="dropdown-icon">▼</span>
          </button>
          <div class="dropdown-content">
            <a href="?" class="<?php echo $selectedCategoryId === null ? 'selected' : ''; ?>">All Categories</a>
            <?php foreach ($categories as $category): ?>
              <a href="?category=<?php echo htmlspecialchars($category['id']); ?>"
                class="<?php echo $selectedCategoryId === (int) $category['id'] ? 'selected' : ''; ?>">
                <?php echo htmlspecialchars($category['name']); ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php
        // Using the Button component with primary variant for consistency
        Button::start(['variant' => 'primary', 'onClick' => "console.log('hello')"]);
        echo '<span>Login</span>';
        Button::end();
        ?>
      </div>
    </header>
    <?php
  }
}
?>