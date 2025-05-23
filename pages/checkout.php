<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../components/button/button.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../database/classes/purchase.php');
require_once(__DIR__ . '/../includes/auth.php');

head();
echo '<link rel="stylesheet" href="/css/service.css">';
echo '<link rel="stylesheet" href="/css/checkout.css">';

$service = Service::getServiceById((int) $_GET['id']);
$seller = $service ? User::getUserById($service->getSeller()) : null;
$loggedInUser = Auth::getInstance()->getUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $loggedInUser && $service) {
  // Get message if provided
  $message = isset($_POST['message']) ? trim($_POST['message']) : null;

  // Store purchase in DB
  $purchaseId = Purchase::create($loggedInUser['id'], $service->getId(), $service->getPrice(), $message);

  // Redirect to receipt with purchase id
  header('Location: /pages/receipt.php?purchase_id=' . $purchaseId);
  exit();
}

drawHeader();
?>
<main>
  <?php if ($service && $seller): ?>
    <div class="checkout-container">
      <h1>Checkout</h1>
      <div class="checkout-service-info">
        <img src="<?= htmlspecialchars($service->getImage()) ?>" alt="<?= htmlspecialchars($service->getName()) ?>"
          class="checkout-service-img">
        <div>
          <h2><?= htmlspecialchars($service->getName()) ?></h2>
          <p>by <?= htmlspecialchars($seller->getName()) ?></p>
          <p><?= nl2br(htmlspecialchars($service->getDescription())) ?></p>
          <p><strong>Price:</strong> <?= htmlspecialchars(number_format($service->getPrice(), 2)) ?>€</p>
        </div>
      </div>
      <form id="pay-form" action="/pages/checkout.php?id=<?= $service->getId() ?>" method="post">
        <input type="hidden" name="id" value="<?= $service->getId() ?>">
        <div class="message-container">
          <label for="message">Message to seller (optional):</label>
          <textarea id="message" name="message" rows="4"
            placeholder="Add any special instructions or information for the seller..."></textarea>
        </div>
        <?php
        require_once(__DIR__ . '/../components/button/button.php');
        Button::start([
          'type' => 'button',
          'id' => 'pay-button',
          'variant' => 'primary',
        ]);
        ButtonIcon::render('ph-bold ph-credit-card');
        ?>
        <span>Pay</span>
        <?php Button::end(); ?>
      </form>
    </div>
    <div id="confirm-overlay" class="confirm-overlay" style="display:none;">
      <div class="confirm-modal">
        <p>Are you sure you want to pay for this service?</p>
        <button id="confirm-pay" class="btn btn-primary">Confirm</button>
        <button id="cancel-pay" class="btn btn-secondary">Cancel</button>
      </div>
    </div>
  <?php else: ?>
    <div class="service-not-found">
      <h2>Service Not Found</h2>
      <p>We couldn't find the service you're trying to buy.</p>
      <a href="/" class="btn btn-primary">Back to Home</a>
    </div>
  <?php endif; ?>
</main>
<?php drawFooter(); ?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const payButton = document.getElementById('pay-button');
    const overlay = document.getElementById('confirm-overlay');
    const confirmPay = document.getElementById('confirm-pay');
    const cancelPay = document.getElementById('cancel-pay');
    const payForm = document.getElementById('pay-form');

    if (payButton) {
      payButton.addEventListener('click', function (e) {
        overlay.style.display = 'flex';
      });
    }
    if (cancelPay) {
      cancelPay.addEventListener('click', function () {
        overlay.style.display = 'none';
      });
    }
    if (confirmPay) {
      confirmPay.addEventListener('click', function () {
        payForm.submit();
      });
    }
  });
</script>