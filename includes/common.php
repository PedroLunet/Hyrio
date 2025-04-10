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
    include 'components/card.php';
} ?>

    

<?php function drawFooter()
{ ?>
    <footer>
        <p>&copy; 2025 Hyrio</p>
    </footer>

    </html>
<?php } ?>