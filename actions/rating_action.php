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

// Check if service exists
$service = Service::getServiceById($serviceId);
if (!$service) {
  header('HTTP/1.1 404 Not Found');
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'message' => 'Service not found']);
  exit;
}

// Check if user is trying to rate their own service
if ($service->getSeller() === $user['id']) {
  header('HTTP/1.1 403 Forbidden');
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'message' => 'You cannot rate your own service']);
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
      // Get updated rating stats
      $stats = Rating::getRatingStats($serviceId);

      header('Content-Type: application/json');
      echo json_encode([
        'success' => true,
        'message' => $action === 'add' ? 'Rating added successfully' : 'Rating updated successfully',
        'stats' => $stats
      ]);
    } else {
      header('HTTP/1.1 500 Internal Server Error');
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Failed to save rating']);
    }
    break;

  case 'delete':
    $success = Rating::deleteRating($user['id'], $serviceId);

    if ($success) {
      // Get updated rating stats
      $stats = Rating::getRatingStats($serviceId);

      header('Content-Type: application/json');
      echo json_encode([
        'success' => true,
        'message' => 'Rating deleted successfully',
        'stats' => $stats
      ]);
    } else {
      header('HTTP/1.1 500 Internal Server Error');
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Failed to delete rating']);
    }
    break;

  case 'get':
    // Get user's existing rating for this service
    $userRating = Rating::getUserRatingForService($user['id'], $serviceId);
    $stats = Rating::getRatingStats($serviceId);

    header('Content-Type: application/json');
    echo json_encode([
      'success' => true,
      'userRating' => $userRating,
      'stats' => $stats
    ]);
    break;

  default:
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    break;
}

// Redirect back to service page if not AJAX request
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
  $redirectUrl = '/pages/service.php?id=' . $serviceId;
  if (isset($_SESSION['rating_message'])) {
    unset($_SESSION['rating_message']);
  }
  header('Location: ' . $redirectUrl);
  exit;
}
