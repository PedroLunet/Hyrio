<?php

declare(strict_types=1);

?>

<?php function drawHeader() { ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="../css/style.css">
            <title>Hyrio</title>
        </head>
        <body>
            <header id="menu-header">
                <h1>Hyrio</h1>
                <?php drawLoginButton() ?>
            </header>
            <main>
                <img src="https://media.istockphoto.com/id/453146777/photo/under-construction-dog.jpg?s=612x612&w=0&k=20&c=33kAcdjG_hpiOpNyRmVvlXV1TDd2PksxRrdG2-qvNrA=">
            </main>
        </body>
<?php } ?>

<?php function drawFooter() { ?>
        <footer>
            <p>&copy; 2025 Hyrio</p>
        </footer>
    </html>
<?php } ?>

<?php function drawLoginButton() { ?>
    <button>Login</button>
<?php } ?>
