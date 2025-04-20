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
   * @return void
   */
  public static function render()
  {
    self::includeCSS();

    // Include the button component for login button
    require_once(__DIR__ . '/../button/button.php');
    ?>
    <header id="menu-header">
      <img src="assets/logo.png" alt="Logo">
      <?php
      // Using the Button component with primary variant for consistency
      Button::start(['variant' => 'primary', 'onClick' => "console.log('hello')"]);
      echo '<span>Login</span>';
      Button::end();
      ?>
    </header>
    <?php
  }
}
?>