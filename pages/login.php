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
    <form class="login-form" action="/actions/login_action.php" method="POST">
        <h1>Welcome back!</h1>

        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="error-message">
                <?php
                echo htmlspecialchars($_SESSION['login_error']);
                unset($_SESSION['login_error']);
                ?>
            </div>
        <?php endif; ?>

        <input class="form-item" type="email" name="email" placeholder="Email" value="<?php echo isset($_SESSION['login_form_data']['email']) ? htmlspecialchars($_SESSION['login_form_data']['email']) : ''; ?>" required>

        <div class="password-container">
            <input class="form-item" type="password" name="password" placeholder="Password" required>
            <button type="button" class="password-toggle" title="Show password">👁️‍🗨️</button>
        </div>

        <input class="form-item" type="submit" value="Login">
        <p>Don't have an account? <a href="/pages/register.php">Register</a></p>
    </form>
</main>

<?php

drawFooter();

if (isset($_SESSION['login_form_data'])) {
    unset($_SESSION['login_form_data']);
}
?>

<script src="/js/password-toggle.js"></script>