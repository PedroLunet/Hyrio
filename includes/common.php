<?php

declare(strict_types=1);

// Database connection function
function getDatabaseConnection(): PDO
{
    $db = new PDO('sqlite:' . __DIR__ . '/../db/site.db');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}

// Function to get all services from the database
function getServices(): array
{
    try {
        $db = getDatabaseConnection();

        $stmt = $db->prepare('
            SELECT services.*, users.name as seller_name, categories.name as category_name
            FROM services 
            JOIN users ON services.seller = users.id
            JOIN categories ON services.category = categories.id
        ');

        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

?>

<?php function head(): void
{ ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <title>Hyrio</title>
        <link rel="stylesheet" type="text/css"
            href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" />
        <link rel="stylesheet" type="text/css"
            href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/bold/style.css" />
    </head>
<?php } ?>

<?php function drawHeader()
{
    include 'components/navbar.php';
} ?>

<?php function drawCard()
{
    // Get services from database
    $services = getServices();

    // If no services found, display a placeholder
    if (empty($services)) {
        include 'components/card.php';
        return;
    }

    // Display services in a grid
    echo '<div class="card-grid">';
    foreach ($services as $service) {
        // Pass the service data to a modified card component
        include 'components/card.php';
    }
    echo '</div>';
} ?>

<?php function drawFooter()
{ ?>
    <footer>
        <p>&copy; 2025 Hyrio</p>
    </footer>

    </html>
<?php } ?>