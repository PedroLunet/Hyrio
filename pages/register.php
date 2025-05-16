<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../includes/auth.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

        <?php if (isset($_SESSION['register_error'])): ?>
            <div class="error-message">
                <?php
                echo htmlspecialchars($_SESSION['register_error']);
                unset($_SESSION['register_error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php echo isset($_SESSION['register_form_data']['name']) ? htmlspecialchars($_SESSION['register_form_data']['name']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo isset($_SESSION['register_form_data']['username']) ? htmlspecialchars($_SESSION['register_form_data']['username']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['register_form_data']['email']) ? htmlspecialchars($_SESSION['register_form_data']['email']) : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" id="password" name="password" required>
                <button type="button" class="password-toggle" title="Show password"></button>
            </div>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <div class="password-container">
                <input type="password" id="confirm-password" name="confirm_password" required>
                <button type="button" class="password-toggle" title="Show password"></button>
            </div>
        </div>

        <input class="form-item" type="submit" value="Register">
        <p>Already have an account? <a href="/pages/login.php">Login</a></p>
    </form>
</main>

<?php

drawFooter();

if (isset($_SESSION['register_form_data'])) {
    unset($_SESSION['register_form_data']);
}
?>

<script src="/js/password-toggle.js"></script>