<?php

declare(strict_types=1);

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


    require_once(__DIR__ . '/../button/button.php');
    require_once(__DIR__ . '/../categories/categories.php');
    require_once(__DIR__ . '/../../includes/auth.php');

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
        $user = Auth::getInstance()->getUser();
        if ($user) {
          $userName = isset($user['name']) ? $user['name'] : 'User';
          echo '<span class="user-name">' . htmlspecialchars($userName) . '</span>';
          echo '<img class="user-profile-pic" src="' . htmlspecialchars($user['profile_pic']) . '" alt="Profile Picture">';
          Button::start(['variant' => 'primary', 'onClick' => "window.location.href='/actions/logout_action.php'"]);
          echo '<span>Logout</span>';
          Button::end();
        } else {
          Button::start(['variant' => 'primary', 'onClick' => "window.location.href='/pages/login.php'"]);
          echo '<span>Login</span>';
          Button::end();
        }
        ?>
      </div>
    </header>
<?php
  }
}
?>