<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../database/classes/rating.php');
require_once(__DIR__ . '/../database/classes/service.php');

// Check if user is authenticated
$auth = Auth::getInstance();
$user = $auth->getUser();

if (!$user) {
  header('HTTP/1.1 401 Unauthorized');
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'message' => 'You must be logged in to rate services']);
  exit;
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('HTTP/1.1 405 Method Not Allowed');
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  exit;
}

// Get and validate input
$action = $_POST['action'] ?? '';
$serviceId = (int) ($_POST['service_id'] ?? 0);
$rating = (int) ($_POST['rating'] ?? 0);
$review = trim($_POST['review'] ?? '');

// Validate service ID
if ($serviceId <= 0) {
  header('HTTP/1.1 400 Bad Request');
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'message' => 'Invalid service ID']);
  exit;
}

// Check if user can rate this service (handles service existence, ownership, and purchase validation)
$canRateCheck = Rating::canUserRate($user['id'], $serviceId);
if (!$canRateCheck['can_rate']) {
  header('HTTP/1.1 403 Forbidden');
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'message' => $canRateCheck['reason']]);
  exit;
}

// Handle different actions
switch ($action) {
  case 'add':
  case 'update':
    // Validate rating
    if ($rating < 1 || $rating > 5) {
      header('HTTP/1.1 400 Bad Request');
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5 stars']);
      exit;
    }

    // Clean review text (optional)
    $review = !empty($review) ? $review : null;

    // Add or update rating
    $success = Rating::addOrUpdateRating($user['id'], $serviceId, $rating, $review);

    if ($success) {
      // Check if this is an AJAX request
      $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

      if ($isAjax) {
        // Get updated rating stats for AJAX response
        $stats = Rating::getRatingStats($serviceId);
        header('Content-Type: application/json');
        echo json_encode([
          'success' => true,
          'message' => $action === 'add' ? 'Rating added successfully' : 'Rating updated successfully',
          'stats' => $stats
        ]);
      } else {
        // Set success message and redirect for form submission
        $_SESSION['success_message'] = $action === 'add' ? 'Rating added successfully!' : 'Rating updated successfully!';
        header('Location: /pages/service.php?id=' . $serviceId);
        exit;
      }
    } else {
      $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

      if ($isAjax) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Failed to save rating']);
      } else {
        $_SESSION['error_message'] = 'Failed to save rating. Please try again.';
        header('Location: /pages/service.php?id=' . $serviceId);
        exit;
      }
    }
    break;

  case 'delete':
    $success = Rating::deleteRating($user['id'], $serviceId);

    if ($success) {
      $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

      if ($isAjax) {
        // Get updated rating stats for AJAX response
        $stats = Rating::getRatingStats($serviceId);
        header('Content-Type: application/json');
        echo json_encode([
          'success' => true,
          'message' => 'Rating deleted successfully',
          'stats' => $stats
        ]);
      } else {
        $_SESSION['success_message'] = 'Rating deleted successfully!';
        header('Location: /pages/service.php?id=' . $serviceId);
        exit;
      }
    } else {
      $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

      if ($isAjax) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Failed to delete rating']);
      } else {
        $_SESSION['error_message'] = 'Failed to delete rating. Please try again.';
        header('Location: /pages/service.php?id=' . $serviceId);
        exit;
      }
    }
    break;

  case 'get':
    // Check if user can rate this service
    $canRateCheck = Rating::canUserRate($user['id'], $serviceId);

    // Get user's existing rating for this service
    $userRating = null;
    if ($canRateCheck['can_rate']) {
      $userRating = Rating::getUserRatingForService($user['id'], $serviceId);
    }

    $stats = Rating::getRatingStats($serviceId);

    header('Content-Type: application/json');
    echo json_encode([
      'success' => true,
      'userRating' => $userRating,
      'stats' => $stats,
      'canRate' => $canRateCheck['can_rate'],
      'reason' => $canRateCheck['reason']
    ]);
    break;

  default:
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if ($isAjax) {
      header('HTTP/1.1 400 Bad Request');
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Invalid action']);
    } else {
      $_SESSION['error_message'] = 'Invalid action requested.';
      header('Location: /pages/service.php?id=' . $serviceId);
      exit;
    }
    break;
}
