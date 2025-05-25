<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../database/classes/rating.php');

echo '<link rel="stylesheet" href="/css/overlay.css">';
echo '<link rel="stylesheet" href="/css/forms.css">';
echo '<link rel="stylesheet" href="/components/rating/css/rating.css">';

$user = Auth::getInstance()->getUser();

if (!$user) {
  header('Location: /pages/login.php');
  exit();
}

// Get service ID from query parameter
$serviceId = isset($_GET['service_id']) ? (int) $_GET['service_id'] : 0;
$service = null;
$existingRating = null;
$canUserRate = false;
$ratingMessage = '';

if ($serviceId > 0) {
  $service = Service::getServiceById($serviceId);
  if ($service) {
    $canRateCheck = Rating::canUserRate($user['id'], $serviceId);
    $canUserRate = $canRateCheck['can_rate'];
    $ratingMessage = $canRateCheck['reason'];

    if ($canUserRate) {
      $existingRating = Rating::getUserRatingForService($user['id'], $serviceId);
    }
  }
}

$currentRating = $existingRating ? $existingRating['rating'] : 0;
$currentReview = $existingRating ? $existingRating['review'] : '';
$isUpdate = $existingRating !== null;

?>

<div class="overlay rating-overlay" id="rating-overlay">
  <div class="overlay-content">
    <div class="overlay-header">
      <h2><?= $isUpdate ? 'Update Your Rating' : 'Rate This Service' ?></h2>
      <button class="close-btn" aria-label="Close">✕</button>
    </div>
    <div class="overlay-body">
      <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error-message">
          <?php
          echo htmlspecialchars($_SESSION['error_message']);
          unset($_SESSION['error_message']);
          ?>
        </div>
      <?php endif; ?>

      <?php if ($service && $canUserRate): ?>
        <div class="service-info-header">
          <img src="<?= htmlspecialchars($service->getImage()) ?>" alt="<?= htmlspecialchars($service->getName()) ?>"
            class="service-thumbnail">
          <div class="service-details">
            <h3><?= htmlspecialchars($service->getName()) ?></h3>
            <p class="service-price">€<?= htmlspecialchars(number_format($service->getPrice(), 2)) ?></p>
          </div>
        </div>

        <form action="/actions/rating_action.php" method="POST" class="rating-form" id="rating-form">
          <input type="hidden" name="service_id" value="<?= $serviceId ?>">
          <input type="hidden" name="action" value="<?= $isUpdate ? 'update' : 'add' ?>">

          <!-- Star rating input -->
          <div class="star-rating-input">
            <label>Your Rating:</label>
            <div class="stars-input">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" <?= $i <= $currentRating ? 'checked' : '' ?> required>
                <label for="star<?= $i ?>" class="star-label">
                  <i class="ph-fill ph-star"></i>
                </label>
              <?php endfor; ?>
            </div>
            <div class="rating-text-feedback">
              <span id="rating-feedback">Click to rate</span>
            </div>
          </div>

          <!-- Review textarea -->
          <div class="review-input">
            <label for="review">Your Review (optional):</label>
            <textarea id="review" name="review" rows="4"
              placeholder="Share your experience with this service..."><?= htmlspecialchars($currentReview) ?></textarea>
            <div class="character-count">
              <span id="review-count">0</span>/500 characters
            </div>
          </div>

          <!-- Submit actions -->
          <div class="rating-form-actions">
            <button type="button" class="btn btn-secondary"
              onclick="OverlaySystem.close('rating-overlay')">Cancel</button>
            <button type="submit" class="btn btn-primary"><?= $isUpdate ? 'Update Rating' : 'Submit Rating' ?></button>
            <?php if ($isUpdate): ?>
              <button type="button" class="btn btn-danger" onclick="deleteRating(<?= $serviceId ?>)">Delete Rating</button>
            <?php endif; ?>
          </div>
        </form>

      <?php elseif ($service && !$canUserRate): ?>
        <div class="rating-restriction">
          <i class="ph-bold ph-info"></i>
          <h3>Cannot Rate Service</h3>
          <p><?= htmlspecialchars($ratingMessage) ?></p>
          <button type="button" class="btn btn-secondary" onclick="OverlaySystem.close('rating-overlay')">Close</button>
        </div>

      <?php else: ?>
        <div class="service-not-found">
          <i class="ph-bold ph-warning"></i>
          <h3>Service Not Found</h3>
          <p>The service you're trying to rate could not be found.</p>
          <button type="button" class="btn btn-secondary" onclick="OverlaySystem.close('rating-overlay')">Close</button>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
  // Initialize character count for review textarea
  function initializeCharacterCount() {
    const reviewTextarea = document.getElementById('review');
    const reviewCount = document.getElementById('review-count');

    if (reviewTextarea && reviewCount) {
      function updateCharCount() {
        const count = reviewTextarea.value.length;
        reviewCount.textContent = count;
        
        // Update color and form validation state
        if (count > 500) {
          reviewCount.style.color = '#e74c3c';
          reviewTextarea.style.borderColor = '#e74c3c';
          updateSubmitButtonState();
        } else {
          reviewCount.style.color = '#666';
          reviewTextarea.style.borderColor = '#d1d5db';
          updateSubmitButtonState();
        }
      }

      function updateSubmitButtonState() {
        const submitButton = document.querySelector('#rating-form button[type="submit"]');
        const count = reviewTextarea.value.length;
        
        if (submitButton) {
          if (count > 500) {
            submitButton.disabled = true;
            submitButton.style.opacity = '0.5';
            submitButton.style.cursor = 'not-allowed';
          } else {
            submitButton.disabled = false;
            submitButton.style.opacity = '1';
            submitButton.style.cursor = 'pointer';
          }
        }
      }

      reviewTextarea.addEventListener('input', updateCharCount);
      updateCharCount(); // Initial count

      // Prevent typing when at 500 characters (but allow deletion)
      reviewTextarea.addEventListener('keydown', function (e) {
        if (
          this.value.length >= 500 &&
          e.key !== 'Backspace' &&
          e.key !== 'Delete' &&
          e.key !== 'ArrowLeft' &&
          e.key !== 'ArrowRight' &&
          e.key !== 'ArrowUp' &&
          e.key !== 'ArrowDown' &&
          !e.ctrlKey && // Allow Ctrl+A, Ctrl+C, etc.
          !e.metaKey   // Allow Cmd+A, Cmd+C, etc. on Mac
        ) {
          e.preventDefault();
        }
      });

      // Prevent pasting text that would exceed the limit
      reviewTextarea.addEventListener('paste', function (e) {
        const pastedData = e.clipboardData.getData('text');
        const currentText = this.value;
        const selectionStart = this.selectionStart;
        const selectionEnd = this.selectionEnd;
        const newText = currentText.substring(0, selectionStart) + pastedData + currentText.substring(selectionEnd);
        
        if (newText.length > 500) {
          e.preventDefault();
          // Allow partial paste if it doesn't exceed the limit
          const availableSpace = 500 - (currentText.length - (selectionEnd - selectionStart));
          if (availableSpace > 0) {
            const truncatedPaste = pastedData.substring(0, availableSpace);
            const beforeSelection = currentText.substring(0, selectionStart);
            const afterSelection = currentText.substring(selectionEnd);
            this.value = beforeSelection + truncatedPaste + afterSelection;
            this.setSelectionRange(selectionStart + truncatedPaste.length, selectionStart + truncatedPaste.length);
            updateCharCount();
          }
        }
      });
    }
  }

  // Initialize character count immediately
  initializeCharacterCount();

  function deleteRating(serviceId) {
    if (confirm('Are you sure you want to delete your rating? This action cannot be undone.')) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '/actions/rating_action.php';

      const serviceInput = document.createElement('input');
      serviceInput.type = 'hidden';
      serviceInput.name = 'service_id';
      serviceInput.value = serviceId;

      const actionInput = document.createElement('input');
      actionInput.type = 'hidden';
      actionInput.name = 'action';
      actionInput.value = 'delete';

      form.appendChild(serviceInput);
      form.appendChild(actionInput);
      document.body.appendChild(form);
      form.submit();
    }
  }
</script>