<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../database/classes/purchase.php');
require_once(__DIR__ . '/../includes/auth.php');

head();
echo '<link rel="stylesheet" href="/css/service.css">';

$purchase = null;
if (isset($_GET['purchase_id'])) {
  $purchase = Purchase::getById((int) $_GET['purchase_id']);
  if ($purchase) {
    $service = Service::getServiceById((int) $purchase['service_id']);
    $seller = $service ? User::getUserById($service->getSeller()) : null;
    $buyer = User::getUserById((int) $purchase['user_id']);
  }
}

drawHeader();
?>
<main>
  <?php if ($purchase && $service && $seller && $buyer): ?>
    <div class="receipt-container">
      <h1>Payment Receipt</h1>
      <div class="receipt-details">
        <p><strong>Receipt #:</strong> <?= (int) $purchase['id'] ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($purchase['purchased_at']) ?></p>
        <hr>
        <h2><?= htmlspecialchars($service->getName()) ?></h2>
        <p>by <?= htmlspecialchars($seller->getName()) ?></p>
        <p>Purchased by: <?= htmlspecialchars($buyer->getName()) ?></p>
        <p><?= nl2br(htmlspecialchars($service->getDescription())) ?></p>
        <p><strong>Price Paid:</strong> <?= htmlspecialchars(number_format($purchase['price'], 2)) ?>€</p>
        <hr>
        <p>Thank you for your purchase!</p>
        <a href="/" class="btn btn-primary">Back to Home</a>
      </div>
    </div>
  <?php else: ?>
    <div class="service-not-found">
      <h2>Service Not Found</h2>
      <p>We couldn't find the service for this receipt.</p>
      <a href="/" class="btn btn-primary">Back to Home</a>
    </div>
  <?php endif; ?>
</main>
<?php drawFooter(); ?>

<style>
  .receipt-container {
    max-width: 500px;
    margin: 2rem auto;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    padding: 2rem;
  }

  .receipt-details {
    font-size: 1.1rem;
  }
</style>