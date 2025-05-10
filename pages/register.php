<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../includes/auth.php');

if (Auth::getInstance()->getUser()) {
    header('Location: /');
    exit();
}

head();

echo '<link rel="stylesheet" href="/css/auth.css">';

drawHeader();
?>

<main>
    <form class="register-form" action="/actions/register_action.php" method="POST">
        <h1>Welcome!</h1>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <input class="form-item" type="text" name="name" placeholder="Name" required>
        <input class="form-item" type="email" name="email" placeholder="Email" required>
        <input class="form-item" type="password" name="password" placeholder="Password" required>
        <input class="form-item" type="submit" value="Register">
        <p>Already have an account? <a href="/pages/login.php">Login</a></p>
    </form>
</main>

<?php

drawFooter();

?>