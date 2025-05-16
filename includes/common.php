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

?>

<?php function head(): void
{ ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/css/style.css">
        <title>Hyrio</title>
        <link rel="stylesheet" type="text/css"
            href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" />
        <link rel="stylesheet" type="text/css"
            href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/bold/style.css" />
        <link rel="stylesheet" type="text/css"
            href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css" />
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

    <?php function drawFooter()
    { ?>
        <footer>
            <p>&copy; 2025 Hyrio</p>
        </footer>
    </body>

    </html>
<?php } ?>