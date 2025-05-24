<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../database/classes/rating.php');
require_once(__DIR__ . '/../../database/classes/user.php');

class RatingComponent
{

  /**
   * Render star rating display (read-only)
   */
  public static function renderStars(float $rating, int $totalRatings = 0, bool $showCount = true): void
  {
    $fullStars = floor($rating);
    $hasHalfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);

    echo '<div class="star-rating-display">';

    // Full stars
    for ($i = 0; $i < $fullStars; $i++) {
      echo '<i class="ph-fill ph-star star-filled"></i>';
    }

    // Half star
    if ($hasHalfStar) {
      echo '<i class="ph-fill ph-star-half star-half"></i>';
    }

    // Empty stars
    for ($i = 0; $i < $emptyStars; $i++) {
      echo '<i class="ph ph-star star-empty"></i>';
    }

    if ($showCount) {
      echo '<span class="rating-text">';
      echo number_format($rating, 1) . ' (' . $totalRatings . ' rating' . ($totalRatings !== 1 ? 's' : '') . ')';
      echo '</span>';
    }

    echo '</div>';
  }

  /**
   * Render rating button that opens the overlay
   */
  public static function renderRatingButton(int $serviceId, ?array $existingRating = null): void
  {
    $isUpdate = $existingRating !== null;
    $buttonText = $isUpdate ? 'Update Rating' : 'Rate Service';
    $buttonClass = $isUpdate ? 'btn btn-secondary' : 'btn btn-primary';

    echo '<button class="' . $buttonClass . ' rate-service-btn" onclick="openRatingOverlay(' . $serviceId . ')">';
    echo '<i class="ph-bold ph-star"></i>';
    echo $buttonText;
    echo '</button>';
  }

  /**
   * Render rating statistics
   */
  public static function renderRatingStats(array $stats): void
  {
    $totalRatings = (int) $stats['total_ratings'];
    $averageRating = (float) $stats['average_rating'];

    if ($totalRatings === 0) {
      echo '<div class="rating-stats">';
      echo '<p class="no-ratings">No ratings yet. Be the first to rate this service!</p>';
      echo '</div>';
      return;
    }

    echo '<div class="rating-stats">';
    echo '<div class="rating-overview">';
    echo '<div class="average-rating">';
    echo '<span class="rating-number">' . number_format($averageRating, 1) . '</span>';
    self::renderStars($averageRating, $totalRatings);
    echo '</div>';
    echo '</div>';

    echo '<div class="rating-breakdown">';
    for ($i = 5; $i >= 1; $i--) {
      $count = (int) $stats[$i === 1 ? 'one_star' : ($i === 2 ? 'two_star' : ($i === 3 ? 'three_star' : ($i === 4 ? 'four_star' : 'five_star')))];
      $percentage = $totalRatings > 0 ? ($count / $totalRatings) * 100 : 0;

      echo '<div class="rating-bar">';
      echo '<span class="star-count">' . $i . ' star' . ($i !== 1 ? 's' : '') . '</span>';
      echo '<div class="bar-container">';
      echo '<div class="bar-fill" style="width: ' . $percentage . '%"></div>';
      echo '</div>';
      echo '<span class="count">(' . $count . ')</span>';
      echo '</div>';
    }
    echo '</div>';
    echo '</div>';
  }

  /**
   * Render list of reviews
   */
  public static function renderReviews(array $ratings): void
  {
    if (empty($ratings)) {
      echo '<div class="reviews-section">';
      echo '<h3>Reviews</h3>';
      echo '<p class="no-reviews">No reviews yet.</p>';
      echo '</div>';
      return;
    }

    echo '<div class="reviews-section">';
    echo '<h3>Reviews (' . count($ratings) . ')</h3>';
    echo '<div class="reviews-list">';

    foreach ($ratings as $rating) {
      echo '<div class="review-item">';
      echo '<div class="review-header">';
      echo '<div class="reviewer-info">';
      echo '<img src="' . htmlspecialchars($rating['user_profile_pic']) . '" alt="' . htmlspecialchars($rating['user_name']) . '" class="reviewer-avatar">';
      echo '<div class="reviewer-details">';
      echo '<span class="reviewer-name">' . htmlspecialchars($rating['user_name']) . '</span>';
      echo '<div class="review-rating">';
      self::renderStars((float) $rating['rating'], 0, false);
      echo '</div>';
      echo '</div>';
      echo '</div>';
      echo '<span class="review-date">' . date('M j, Y', strtotime($rating['created_at'])) . '</span>';
      echo '</div>';

      if (!empty($rating['review'])) {
        echo '<div class="review-text">';
        echo '<p>' . nl2br(htmlspecialchars($rating['review'])) . '</p>';
        echo '</div>';
      }
      echo '</div>';
    }

    echo '</div>';
    echo '</div>';
  }
}
