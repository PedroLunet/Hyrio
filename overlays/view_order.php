<?php

declare(strict_types=1);

echo '<link rel="stylesheet" href="/css/overlay.css">';

?>

<div class="overlay seller-message-overlay" id="seller-message-overlay">
  <div class="overlay-content">
    <div class="overlay-header">
      <h2>Order Details</h2>
      <button class="close-btn" aria-label="Close">x</button>
    </div>

    <div class="overlay-body">
      <div class="order-grid">
        <div class="grid-row">
          <div class="grid-cell service-cell" id="message-service-info">
            <div class="loading-indicator" id="order-loading">Loading order details...</div>
          </div>
          <div class="grid-cell details-cell" id="order-status-section">
            <h3>Status: <span id="order-status"></span></h3>
            <p id="order-date"></p>
          </div>
        </div>
        <div class="grid-row message-row">
          <div class="grid-cell message-cell">
            <div class="message-content">
              <h3>Message:</h3>
              <p id="seller-message-text"></p>
            </div>
          </div>
        </div>
      </div>
      <div class="message-footer">
        <button class="close-message-btn" onclick="OverlaySystem.close('seller-message-overlay')">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const closeBtn = document.querySelector('#seller-message-overlay .close-btn');
    if (closeBtn) {
      closeBtn.addEventListener('click', function() {
        OverlaySystem.close('seller-message-overlay');
      });
    }

    window.showOrderDetails = function(purchaseId) {
      if (!purchaseId) {
        console.error('Purchase ID is required');
        return;
      }

      const serviceInfo = document.getElementById('message-service-info');
      const messageText = document.getElementById('seller-message-text');
      const orderStatus = document.getElementById('order-status');
      const orderDate = document.getElementById('order-date');

      if (serviceInfo) {
        serviceInfo.innerHTML = '<div class="loading-indicator">Loading order details...</div>';
      }

      if (messageText) {
        messageText.innerHTML = '';
      }

      if (orderStatus) {
        orderStatus.textContent = '';
      }

      if (orderDate) {
        orderDate.textContent = '';
      }

      fetch(`/actions/get_purchase_details.php?id=${purchaseId}`)
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          if (serviceInfo) {
            serviceInfo.innerHTML = `<div class="service-info">
                <h3>${data.service_name}</h3>
                <p>Seller: ${data.seller_name}</p>
                <p>Buyer: ${data.buyer_name}</p>
            </div>`;
          }

          if (messageText) {
            if (data.message && data.message.trim() !== '') {
              messageText.innerHTML = data.message;
            } else {
              messageText.innerHTML = '<em>No message was provided for this purchase.</em>';
            }
          }

          if (orderStatus) {
            orderStatus.textContent = data.status;
            orderStatus.className = `status-${data.status.toLowerCase()}`;
          }

          if (orderDate) {
            orderDate.textContent = `Purchased on: ${new Date(data.purchased_at).toLocaleDateString()} at ${new Date(data.purchased_at).toLocaleTimeString()}`;
          }
        })
        .catch(error => {
          console.error('Error fetching order details:', error);
          if (serviceInfo) {
            serviceInfo.innerHTML = '<div class="error-message">Error loading order details. Please try again.</div>';
          }
        });

      OverlaySystem.open('seller-message-overlay');
    };
  });
</script>

<style>
  .order-grid {
    display: grid;
    grid-template-columns: 1fr;
    grid-gap: 15px;
    margin-bottom: 15px;
  }

  .grid-row {
    display: grid;
    grid-gap: 15px;
  }

  .grid-row:first-child {
    grid-template-columns: 1fr 1fr;
  }

  .grid-row.message-row {
    grid-template-columns: 1fr;
  }

  .grid-cell {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
  }
  
  .message-cell {
    padding: 0;
    background-color: transparent;
  }

  .service-info h3 {
    margin: 0 0 5px 0;
    color: var(--primary, #3b82f6);
    font-size: 1.2rem;
  }

  .service-info p {
    margin: 0 0 5px 0;
    color: #666;
  }

  .message-content {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 15px 20px;
    margin: 0;
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

  .loading-indicator {
    text-align: center;
    padding: 10px;
    color: #666;
    font-style: italic;
  }

  .error-message {
    color: #dc3545;
    padding: 10px;
    background-color: #f8d7da;
    border-radius: 4px;
    margin-bottom: 10px;
  }

  .details-cell h3 {
    margin-top: 0;
    margin-bottom: 10px;
    font-size: 1rem;
    color: #495057;
  }

  .status-pending {
    color: #fd7e14;
    font-weight: bold;
  }

  .status-completed {
    color: #198754;
    font-weight: bold;
  }

  .status-cancelled {
    color: #dc3545;
    font-weight: bold;
  }

  #order-date {
    color: #6c757d;
    font-size: 0.9rem;
    margin-top: 5px;
  }

  .message-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
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

  @media (max-width: 600px) {
    .seller-message-overlay .overlay-body {
      padding: 15px 20px 20px;
    }

    .grid-row:first-child {
      grid-template-columns: 1fr;
    }

    .grid-cell {
      padding: 12px;
    }

    .message-content {
      padding: 12px 15px;
    }
  }
</style>