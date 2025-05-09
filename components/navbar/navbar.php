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
      echo '<link rel="stylesheet" href="/components/navbar/css/navbar.css">';
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
        <a href="/">
          <img src="/assets/logo.png" alt="Logo">
        </a>
      </div>
      <div class="nav-right">
        <?php
        // Using the Button component with primary variant for consistency
        Button::start(['variant' => 'primary', 'onClick' => "window.location.href='/pages/login.php'"]);
        echo '<span>Login</span>';
        Button::end();
        ?>
      </div>
    </header>
    <?php
  }
}
?>