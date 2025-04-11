<div class="card" id="container">
  <img src="<?= isset($service['image']) ? htmlspecialchars($service['image']) : '../assets/placeholder.png' ?>"
    alt="<?= isset($service['name']) ? htmlspecialchars($service['name']) : 'Service' ?>">
  <div id="label">
    <div id="titles">
      <h3><?= isset($service['name']) ? htmlspecialchars($service['name']) : 'i build minecraft servers' ?></h3>
      <p><?= isset($service['seller_name']) ? htmlspecialchars($service['seller_name']) : 'bald man' ?></p>
    </div>
    <button>
      <i class="ph-bold ph-currency-eur"></i>
      <span>a partir de
        <?= isset($service['price']) ? htmlspecialchars(number_format($service['price'], 2)) : '230' ?>€</span>
    </button>
  </div>
</div>