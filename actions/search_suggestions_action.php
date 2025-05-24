<?php

declare(strict_types=1);

header('Content-Type: application/json');

require_once(__DIR__ . '/../includes/database.php');
require_once(__DIR__ . '/../database/classes/service.php');

// Get query parameter
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Return empty array if query is too short
if (strlen($query) < 2) {
    echo json_encode(['suggestions' => []]);
    exit();
}

try {
    // Get search suggestions
    $db = Database::getInstance();
    $searchQuery = '%' . $query . '%';

    // Get service suggestions (limit to 5)
    $stmt = $db->prepare('
        SELECT services.name as text, services.id, "service" as type
        FROM services 
        WHERE services.name LIKE ? 
        ORDER BY services.rating DESC, services.name ASC
        LIMIT 5
    ');
    $stmt->execute([$searchQuery]);
    $serviceSuggestions = $stmt->fetchAll();

    // Get category suggestions (limit to 3)
    $stmt = $db->prepare('
        SELECT categories.name as text, categories.id, "category" as type
        FROM categories
        WHERE categories.name LIKE ?
        LIMIT 3
    ');
    $stmt->execute([$searchQuery]);
    $categorySuggestions = $stmt->fetchAll();

    // Combine suggestions
    $suggestions = array_merge($serviceSuggestions, $categorySuggestions);

    // Return suggestions as JSON
    echo json_encode(['suggestions' => $suggestions]);
} catch (Exception $e) {
    echo json_encode(['error' => 'An error occurred', 'suggestions' => []]);
}
