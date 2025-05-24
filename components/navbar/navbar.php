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
      echo '<link rel="stylesheet" href="/components/navbar/css/dynamic-search.css">';
      echo '<script src="/js/dynamic-search.js" defer></script>';
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

      <div class="nav-center">
        <form action="/pages/search.php" method="GET" class="search-form">
          <div class="search-container">
            <input type="text" name="q" placeholder="Search Hyrio..." class="search-input" required>
            <button type="submit" class="search-button">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </form>
      </div>

      <div class="nav-right">
        <?php
        $user = Auth::getInstance()->getUser();
        if ($user) {
          $profilePic = $user['profile_pic'];

          // Generate a unique ID for this profile dropdown
          $profileDropdownId = 'profile_' . uniqid();

          echo '<div class="profile-dropdown">';
          // Checkbox hack for dropdown toggle
          echo '<input type="checkbox" id="' . $profileDropdownId . '" class="profile-dropdown-toggle">';
          echo '<label for="' . $profileDropdownId . '" class="profile-dropdown-btn">';
          echo '<div class="user-profile">';
          echo '<img src="' . htmlspecialchars($profilePic) . '" alt="Profile Picture" class="profile-pic" title="' . htmlspecialchars($user['name']) . '">';
          echo '</div>';
          echo '</label>';

          // Dropdown menu content
          echo '<div class="profile-dropdown-menu">';
          echo '<div class="user-name">' . htmlspecialchars($user['name']) . '</div>';
          echo '<a href="/pages/profile.php?username=' . htmlspecialchars($user['username']) . '" class="dropdown-item"><i class="fas fa-user"></i> My Profile</a>';
          echo '<a href="/pages/messages.php?username=' . htmlspecialchars($user['username']) . '" class="dropdown-item"><i class="fas fa-envelope"></i> Messages</a>';
          if ($user['is_seller']) {
            echo '<a href="/pages/seller.php" class="dropdown-item"><i class="fas fa-store"></i> Seller Panel</a>';
          }
          if ($user['is_admin']) {
            echo '<a href="/pages/admin.php" class="dropdown-item"><i class="fas fa-cog"></i> Admin Panel</a>';
          }
          echo '<a href="/actions/logout_action.php" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</a>';
          echo '</div>'; // End dropdown menu
          echo '</div>'; // End profile dropdown

          // Add JavaScript to close dropdown when clicking outside
          echo '<script>
          document.addEventListener("DOMContentLoaded", function() {
            const checkbox = document.getElementById("' . $profileDropdownId . '");
            const dropdown = checkbox.parentElement;
            
            // Close when clicking outside
            document.addEventListener("click", function(event) {
              // If the click was outside the dropdown and the dropdown is open
              if (!dropdown.contains(event.target) && checkbox.checked) {
                checkbox.checked = false;
              }
            });
          });
          </script>';
        } else {
          Button::start(['variant' => 'primary', 'onClick' => "window.location.href='/pages/login.php'"]);
          echo '<span>Login</span>';
          Button::end();
        }
        ?>
      </div>
    </header>

    </div>
    </header>
    <div class="mobile-search-container">
      <form action="/pages/search.php" method="GET" class="search-form">
        <div class="search-container">
          <input type="text" name="q" placeholder="Search Hyrio..." class="search-input" required>
          <button type="submit" class="search-button">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </form>
    </div>
<?php
  }
}
?>