<?php

declare(strict_types=1);

?>

<?php function head(): void
{ ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/style.css">
        <title>Hyrio</title>
    </head>
<?php } ?>

<?php function drawHeader()
{
    include 'components/navbar.php';
} ?>

<?php function drawFooter()
{ ?>
    <footer>
        <p>&copy; 2025 Hyrio</p>
    </footer>

    </html>
<?php } ?>
