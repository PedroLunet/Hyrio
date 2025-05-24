<?php
declare(strict_types=1);

echo '<link rel="stylesheet" href="/css/overlay.css">';

?>

<div class="overlay seller-message-overlay" id="seller-message-overlay">
  <div class="overlay-content">
    <div class="overlay-header">
      <h2>Message to Seller</h2>
      <button class="close-btn" aria-label="Close">x</button>
    </div>

    <div class="overlay-body">
      <div class="message-service-info" id="message-service-info"></div>
      <div class="message-content">
        <h3>Your Message:</h3>
        <p id="seller-message-text"></p>
      </div>
      <div class="message-footer">
        <button class="close-message-btn" onclick="OverlaySystem.close('seller-message-overlay')">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Setup close button for the overlay
    const closeBtn = document.querySelector('#seller-message-overlay .close-btn');
    if (closeBtn) {
      closeBtn.addEventListener('click', function () {
        OverlaySystem.close('seller-message-overlay');
      });
    }

    // Display message content when the overlay is opened
    window.showSellerMessage = function (message, serviceName, sellerName) {
      const messageText = document.getElementById('seller-message-text');
      const serviceInfo = document.getElementById('message-service-info');

      if (serviceInfo && serviceName && sellerName) {
        serviceInfo.innerHTML = `<div class="service-info">
                <h3>${serviceName}</h3>
                <p>Seller: ${sellerName}</p>
            </div>`;
      }

      if (messageText) {
        if (message && message.trim() !== '') {
          messageText.innerHTML = message; // Already escaped and formatted with nl2br in PHP
        } else {
          messageText.innerHTML = '<em>No message was provided for this purchase.</em>';
        }
      }

      OverlaySystem.open('seller-message-overlay');
    };
  });
</script>

<style>
  .message-service-info {
    margin-bottom: 15px;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 15px;
  }

  .message-service-info h3 {
    margin: 0 0 5px 0;
    color: var(--primary, #3b82f6);
    font-size: 1.2rem;
  }

  .message-service-info p {
    margin: 0;
    color: #666;
  }

  .message-content {
    width: 100%;
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 15px 20px;
    border-left: 4px solid var(--primary, #3b82f6);
    margin: 10px 0 20px 0;
  }

  .message-content h3 {
    margin-top: 0;
    margin-bottom: 10px;
    font-size: 1rem;
    color: #495057;
  }

  #seller-message-text {
    line-height: 1.6;
    color: #333;
    white-space: pre-line;
    margin: 0;
  }

  #seller-message-text em {
    color: #777;
  }

  .message-footer {
    display: flex;
    justify-content: flex-end;
  }

  .close-message-btn {
    background-color: #e9ecef;
    color: #495057;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.2s;
  }

  .close-message-btn:hover {
    background-color: #dee2e6;
  }

  /* Make the body take full width in this case */
  .seller-message-overlay .overlay-body {
    display: block;
    padding: 20px 30px 30px;
  }

  .overlay.active {
    opacity: 1;
  }

  .overlay-content.active {
    transform: translateY(0);
    opacity: 1;
  }

  @media (max-width: 480px) {
    .seller-message-overlay .overlay-body {
      padding: 15px 20px 20px;
    }

    .message-content {
      padding: 12px 15px;
    }
  }
</style>