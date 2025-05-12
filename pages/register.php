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

        <input class="form-item" type="text" name="name" placeholder="Name" value="<?php echo isset($_SESSION['register_form_data']['name']) ? htmlspecialchars($_SESSION['register_form_data']['name']) : ''; ?>" required>
        <input class="form-item" type="text" name="username" placeholder="Username" value="<?php echo isset($_SESSION['register_form_data']['username']) ? htmlspecialchars($_SESSION['register_form_data']['username']) : ''; ?>" required>
        <input class="form-item" type="email" name="email" placeholder="Email" value="<?php echo isset($_SESSION['register_form_data']['email']) ? htmlspecialchars($_SESSION['register_form_data']['email']) : ''; ?>" required>

        <div class="password-container">
            <input class="form-item" type="password" name="password" placeholder="Password" required>
            <button type="button" class="password-toggle" title="Show password">👁️‍🗨️</button>
        </div>

        <div class="password-container">
            <input class="form-item" type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="button" class="password-toggle" title="Show password">👁️‍🗨️</button>
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