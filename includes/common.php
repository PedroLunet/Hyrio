<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/database.php');
require_once(__DIR__ . '/../database/classes/service.php');

// Function to get all services from the database
function getServices(?int $categoryId = null): array
{
    if ($categoryId !== null) {
        return Service::getServicesByCategory($categoryId);
    } else {
        return Service::getAllServices();
    }
}

// Function to get featured services for landing page
function getFeaturedServices(int $limit = 6): array
{
    try {
        $db = Database::getInstance();
        // Get random services with good ratings or recent ones
        $stmt = $db->prepare('
            SELECT services.*, users.name as seller_name, categories.name as category_name
            FROM services 
            JOIN users ON services.seller = users.id
            JOIN categories ON services.category = categories.id
            ORDER BY RANDOM()
            LIMIT ?
        ');
        $stmt->execute([$limit]);
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
        <link rel="stylesheet" href="/css/style.css">
        <link rel="stylesheet" href="/css/forms.css">
        <title>Hyrio</title>
        <link rel="stylesheet" type="text/css"
            href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" />
        <link rel="stylesheet" type="text/css"
            href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/bold/style.css" />
        <link rel="stylesheet" type="text/css"
            href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    </head>

    <body>
    <?php } ?>

    <?php function drawHeader()
    {
        // Get the selected category from the URL query parameter
        $selectedCategoryId = isset($_GET['category']) ? (int) $_GET['category'] : null;

        require_once(__DIR__ . '/../components/navbar/navbar.php');
        Navbar::render($selectedCategoryId);
    } ?>

    <?php function drawCategories()
    {
        // Get the selected category from the URL query parameter
        $selectedCategoryId = isset($_GET['category']) ? (int) $_GET['category'] : null;

        // Include and use Categories component
        require_once(__DIR__ . '/../components/categories/categories.php');
        Categories::render($selectedCategoryId);
    } ?>

    <?php function drawCard()
    {
        // Get selected category from URL if present
        $selectedCategoryId = isset($_GET['category']) ? (int) $_GET['category'] : null;

        // Get services from database with optional category filter
        $services = getServices($selectedCategoryId);

        // Include and use Card component
        require_once(__DIR__ . '/../components/card/card.php');
        Card::renderGrid($services);
    } ?>

    <?php function drawFeaturedCards()
    {
        // Get featured services for landing page
        $services = getFeaturedServices(6); // Show 6 featured services
    
        // Include and use Card component
        require_once(__DIR__ . '/../components/card/card.php');

        // Render the service cards
        Card::renderGrid($services);

        // Add "See All Services" button with gradient border below the cards
        echo '<div class="see-all-container">';
        echo '<button class="see-all-button" onclick="window.location.href=\'/pages/search.php\'">Explore More Services</button>';
        echo '</div>';
    } ?>

    <?php function drawFooter()
    { ?>
        <footer>
            <p>&copy; 2025 Hyrio</p>
        </footer>
    </body>

    </html>
<?php } ?>