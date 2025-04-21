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
   * Get a category by ID
   * 
   * @param int $categoryId The category ID
   * @return array|null The category or null if not found
   */
  public static function getCategoryById(int $categoryId): ?array
  {
    try {
      $db = getDatabaseConnection();
      $stmt = $db->prepare('SELECT * FROM categories WHERE id = ?');
      $stmt->execute([$categoryId]);
      $result = $stmt->fetch();
      return $result ?: null;
    } catch (PDOException $e) {
      return null;
    }
  }

  /**
   * Render the categories filter UI (legacy standalone version)
   * 
   * @param int|null $selectedCategoryId Optional currently selected category
   * @return void
   */
  public static function render(?int $selectedCategoryId = null): void
  {
    // Include CSS only when using standalone component
    echo '<link rel="stylesheet" href="components/categories/css/categories.css">';

    $categories = self::getCategories();

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