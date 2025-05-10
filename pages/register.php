<?php

declare( strict_types=1 );

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../includes/auth.php');

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required";
    } else {
        $success = registerUser($name, $email, $password);
        if ($success) {
            header('Location: /');
            attemptLogin($email, $password);
            exit;
        } else {
            $error = "Email already registered";
        }
    }
}

head();

echo '<link rel="stylesheet" href="/css/auth.css">';

drawHeader();
?>

<main>
    <form class="register-form" action="" method="POST">
        <h1>Welcome!</h1>

        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
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