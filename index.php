<?php

declare(strict_types=1);

require_once(__DIR__ . '/includes/common.php');

head();
drawHeader(); ?>
<main>
  <div class="categories-wrapper">
    <?php drawCategories() ?>
    <div class="gradient-text">Encontra o teu profissional!</div>
  </div>
  <?php drawCard() ?>
</main>

<?php drawFooter(); ?>