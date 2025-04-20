<?php
// Include the Button component
include_once(__DIR__ . '/button.php');
?>
<div class="card" id="container">
  <img src="<?= isset($service['image']) ? htmlspecialchars($service['image']) : '../assets/placeholder.png' ?>"
    alt="<?= isset($service['name']) ? htmlspecialchars($service['name']) : 'Service' ?>">
  <div id="label">
    <div id="titles">
      <h3><?= isset($service['name']) ? htmlspecialchars($service['name']) : 'i build minecraft servers' ?></h3>
      <p><?= isset($service['seller_name']) ? htmlspecialchars($service['seller_name']) : 'bald man' ?></p>
    </div>
    <?php
    Button::start(['variant' => 'primary']);
    if (isset($service['price'])) {
      ButtonIcon::render('ph-bold ph-currency-eur');
    }
    echo '<span>a partir de ' . (isset($service['price']) ? htmlspecialchars(number_format($service['price'], 2)) : '230') . '€</span>';
    Button::end();
    ?>
  </div>
</div>