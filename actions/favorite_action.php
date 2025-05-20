<?php
require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../includes/auth.php');

header('Content-Type: application/json');

// Check if the user is logged in
if (!isLoggedIn()) {
    echo json_encode([
        'success' => false,
        'error' => 'User not logged in'
    ]);
    exit;
}

// Get current user
$currentUser = getCurrentUser();
if (!$currentUser) {
    echo json_encode([
        'success' => false,
        'error' => 'User not found'
    ]);
    exit;
}

// Get request data
$data = json_decode(file_get_contents('php://input'), true);
$serviceId = isset($data['serviceId']) ? (int)$data['serviceId'] : 0;
$action = isset($data['action']) ? $data['action'] : '';

if (!$serviceId) {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid service ID'
    ]);
    exit;
}

$userId = $currentUser->getId();

// Process the requested action
$result = [
    'success' => false,
    'error' => 'Invalid action'
];

switch ($action) {
    case 'add':
        $result['success'] = User::addFavorite($userId, $serviceId);
        break;
        
    case 'remove':
        $result['success'] = User::removeFavorite($userId, $serviceId);
        break;
        
    case 'check':
        $isFavorite = User::isFavorite($userId, $serviceId);
        $result = [
            'success' => true,
            'isFavorite' => $isFavorite
        ];
        break;
        
    default:
        // Invalid action, result already set
        break;
}

echo json_encode($result);
