<?php

declare( strict_types=1 );

require_once(__DIR__ . '/../includes/common.php');

head();

echo '<link rel="stylesheet" href="/css/auth.css">';

drawHeader();
?>

<main>
    <form class="login-form" action="/actions/login.php" method="POST">
        <h1>Welcome back!</h1>
        <input class="form-item" type="email" name="email" placeholder="Email" required>
        <input class="form-item" type="password" name="password" placeholder="Password" required>
        <input class="form-item" type="submit" value="Login">
    </form>
</main>

<?php

drawFooter();

?>