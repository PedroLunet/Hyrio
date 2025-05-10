<?php

require_once(__DIR__ . '/../../includes/database.php');

class Categories
{
  /**
   * Get all categories from the database
   * 
   * @return array Array of categories
   */
  public static function getCategories(): array
  {
    try {
      $db = Database::getInstance();
      $stmt = $db->prepare('SELECT * FROM categories ORDER BY name');
      $stmt->execute();
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      return [];
    }
  }

  /**
   * Get a category by ID
   * 
   * @param int $categoryId The category ID
   * @return array|null The category or null if not found
   */
  public static function getCategoryById(int $categoryId): ?array
  {
    try {
      $db = Database::getInstance();
      $stmt = $db->prepare('SELECT * FROM categories WHERE id = ?');
      $stmt->execute([$categoryId]);
      $result = $stmt->fetch();
      return $result ?: null;
    } catch (PDOException $e) {
      return null;
    }
  }

  /**
   * Get the name of the currently selected category
   *
   * @param int|null $categoryId The selected category ID
   * @return string The category name or "All Categories" if none selected
   */
  public static function getSelectedCategoryName(?int $categoryId = null): string
  {
    if ($categoryId === null) {
      return "All Categories";
    }

    $category = self::getCategoryById($categoryId);
    return $category ? htmlspecialchars($category['name']) : "All Categories";
  }

  /**
   * Render the categories filter UI (as a hamburger menu dropdown)
   * 
   * @param int|null $selectedCategoryId Optional currently selected category
   * @return void
   */
  public static function render(?int $selectedCategoryId = null): void
  {
    // Include CSS
    echo '<link rel="stylesheet" href="/components/categories/css/categories.css">';
    // Add Font Awesome
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">';

    $categories = self::getCategories();
    $selectedCategoryName = self::getSelectedCategoryName($selectedCategoryId);

    // Generate a unique ID for this instance to avoid conflicts
    $uniqueId = 'cat_' . uniqid();

    // Main container
    echo '<div class="categories-container">';

    // Selected category title as h1
    echo '<h1 class="selected-category-title">' . $selectedCategoryName . '</h1>';

    // Simpler dropdown implementation
    echo '<div class="hamburger-dropdown">';

    // Checkbox hack (with JS enhancement to close when clicking outside)
    echo '<input type="checkbox" id="' . $uniqueId . '" class="hamburger-toggle">';
    echo '<label for="' . $uniqueId . '" class="hamburger-btn">';
    echo '<i class="fas fa-bars"></i>';
    echo '<span>Categories</span>';
    echo '<i class="fas fa-chevron-down chevron-icon"></i>';
    echo '</label>';

    // Dropdown content
    echo '<div class="dropdown-menu">';

    // All categories option
    $allSelected = $selectedCategoryId === null ? 'selected' : '';
    echo "<a href=\"?\" class=\"category-item $allSelected\">";
    echo '<i class="fas fa-th-large"></i>';
    echo "All Categories</a>";

    // Each category
    foreach ($categories as $category) {
      $selected = $selectedCategoryId === (int) $category['id'] ? 'selected' : '';
      echo "<a href=\"?category=" . htmlspecialchars($category['id']) . "\" class=\"category-item $selected\">";
      echo '<i class="fas fa-tag"></i>';
      echo htmlspecialchars($category['name']);
      echo "</a>";
    }

    echo '</div>'; // dropdown-menu
    echo '</div>'; // hamburger-dropdown
    echo '</div>'; // categories-container

    // Add JavaScript to close dropdown when clicking outside
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
      const checkbox = document.getElementById("' . $uniqueId . '");
      const dropdown = checkbox.parentElement;
      
      // Close when clicking outside
      document.addEventListener("click", function(event) {
        // If the click was outside the dropdown and the dropdown is open
        if (!dropdown.contains(event.target) && checkbox.checked) {
          checkbox.checked = false;
        }
      });
      
      // Prevent dropdown from closing when clicking inside the dropdown menu
      const dropdownMenu = dropdown.querySelector(".dropdown-menu");
      dropdownMenu.addEventListener("click", function(event) {
        // Only prevent propagation if not clicking a link
        if (!event.target.closest("a")) {
          event.stopPropagation();
        }
      });
    });
    </script>';
  }
}
?>