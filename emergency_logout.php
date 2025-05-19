<?php

declare(strict_types=1);

require_once(__DIR__ . '/includes/auth.php');

// Force logout regardless of user state
Auth::getInstance()->logout();

// Redirect to homepage
header('Location: /');
exit;
?>
