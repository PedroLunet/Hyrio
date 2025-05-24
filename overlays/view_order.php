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
        <div class="grid-row files-row" id="files-row" style="display: none;">
          <div class="grid-cell files-cell">
            <div class="files-content">
              <h3>Download Files:</h3>
              <div id="files-list"></div>
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
      const filesRow = document.getElementById('files-row');
      const filesList = document.getElementById('files-list');

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

      if (filesRow) {
        filesRow.style.display = 'none';
      }

      if (filesList) {
        filesList.innerHTML = '';
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

          if (data.status === 'completed' && data.available_files && data.available_files.length > 0) {
            if (filesRow && filesList) {
              let filesHtml = '';
              data.available_files.forEach(file => {
                const fileSizeFormatted = formatFileSize(file.size);
                filesHtml += `
                  <div class="file-item">
                    <div class="file-info">
                      <span class="file-name">${escapeHtml(file.original_name)}</span>
                      <span class="file-size">${fileSizeFormatted}</span>
                    </div>
                    <button class="btn btn-download" onclick="downloadFile(${purchaseId}, '${escapeHtml(file.filename)}')">
                      Download
                    </button>
                  </div>
                `;
              });
              filesList.innerHTML = filesHtml;
              filesRow.style.display = 'block';
            }
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

    function formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes';
      const k = 1024;
      const sizes = ['Bytes', 'KB', 'MB', 'GB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    window.downloadFile = function(purchaseId, filename) {
      const downloadUrl = `/actions/download_purchase_file.php?purchase_id=${purchaseId}&filename=${encodeURIComponent(filename)}`;

      const link = document.createElement('a');
      link.href = downloadUrl;
      link.style.display = 'none';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
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

  .files-cell {
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

  .files-content {
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

  .files-content h3 {
    margin-top: 0;
    margin-bottom: 15px;
    font-size: 1rem;
    color: #495057;
  }

  .file-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #e9ecef;
  }

  .file-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }

  .file-item:first-child {
    padding-top: 0;
  }

  .file-info {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
  }

  .file-name {
    font-weight: 500;
    color: #333;
    margin-bottom: 2px;
  }

  .file-size {
    font-size: 0.85rem;
    color: #666;
  }

  .btn-download {
    background-color: var(--primary);
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 500;
    transition: background-color 0.2s;
  }

  .btn-download:hover {
    background-color: var(--primary);
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

    .files-content {
      padding: 12px 15px;
    }

    .file-item {
      flex-direction: column;
      align-items: stretch;
      gap: 8px;
    }

    .btn-download {
      align-self: flex-end;
      width: auto;
    }
  }
</style>