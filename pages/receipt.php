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
        <p><strong>Price Paid:</strong> <?= htmlspecialchars(number_format($service->getPrice(), 2)) ?>€</p>

        <?php if (!empty($purchase['message'])): ?>
          <div class="message-box">
            <h3>Message to Seller</h3>
            <p><?= nl2br(htmlspecialchars($purchase['message'])) ?></p>
          </div>
        <?php endif; ?>

        <hr>
        <p>Thank you for your purchase!</p>
        <?php
        require_once(__DIR__ . '/../components/button/button.php');
        Button::start([
          'type' => 'button',
          'variant' => 'primary',
          'style' => 'margin-top:1rem;',
          'id' => 'back-home-btn'
        ]);
        ButtonIcon::render('ph-bold ph-house');
        ?>
        <span>Back to Home</span>
        <?php Button::end(); ?>
      </div>
    </div>
  <?php else: ?>
    <div class="service-not-found">
      <h2>Service Not Found</h2>
      <p>We couldn't find the service for this receipt.</p>
      <a href="/" class="btn btn-primary">
        <i class="icon-home"></i> Back to Home
      </a>
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

  .receipt-container h1 {
    margin-top: 0;
  }

  .receipt-details {
    font-size: 1.1rem;
  }

  .message-box {
    background-color: #f8f9fa;
    border-left: 3px solid #6c757d;
    padding: 0.8rem 1rem;
    margin: 1rem 0;
    border-radius: 0 4px 4px 0;
  }

  .message-box h3 {
    margin-top: 0;
    font-size: 1.1rem;
    color: #495057;
  }

  .message-box p {
    margin-bottom: 0;
    color: #495057;
  }
</style>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const backHomeBtn = document.getElementById('back-home-btn');
    if (backHomeBtn) {
      backHomeBtn.addEventListener('click', function () {
        window.location.href = '/';
      });
    }
  });
</script>