<?php

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
      $db = getDatabaseConnection();
      $stmt = $db->prepare('SELECT * FROM categories ORDER BY name');
      $stmt->execute();
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      return [];
    }
  }

  /**
   * Render the categories filter UI
   * 
   * @param int|null $selectedCategoryId Optional currently selected category
   * @return void
   */
  public static function render(?int $selectedCategoryId = null): void
  {
    $categories = self::getCategories();

    echo '<link rel="stylesheet" href="components/categories/css/categories.css">';
    echo '<div class="categories-container">';
    echo '<h3>Categories</h3>';
    echo '<div class="categories-list">';

    // All categories option
    $allSelected = $selectedCategoryId === null ? 'selected' : '';
    echo "<a href=\"?\" class=\"category-item $allSelected\">All Categories</a>";

    // Each category
    foreach ($categories as $category) {
      $selected = $selectedCategoryId === (int) $category['id'] ? 'selected' : '';
      echo "<a href=\"?category=" . htmlspecialchars($category['id']) . "\" class=\"category-item $selected\">";
      echo htmlspecialchars($category['name']);
      echo "</a>";
    }

    echo '</div>'; // categories-list
    echo '</div>'; // categories-container
  }
}
?>