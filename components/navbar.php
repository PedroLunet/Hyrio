<?php
// Include the Button component
include_once(__DIR__ . '/button.php');
?>
<header id="menu-header">
  <img src="assets/logo.png" alt="Logo">
  <?php
  // Using the Button component with primary variant for consistency
  Button::start(['variant' => 'primary', 'onClick' => "console.log('hello')"]);
  echo '<span>Login</span>';
  Button::end();
  ?>
</header>