<?php

declare(strict_types=1);

echo '<link rel="stylesheet" href="/css/overlay.css">';

?>

<div class="overlay complete-order-overlay" id="complete-order-overlay">
    <div class="overlay-content">
        <div class="overlay-header">
            <h2>Complete Order</h2>
            <button class="close-btn" aria-label="Close">x</button>
        </div>

        <div class="overlay-body">
            <form id="complete-order-form" method="POST" action="/actions/complete_order.php" enctype="multipart/form-data">
                <input type="hidden" name="purchase_id" id="purchase-id" value="">
                <div class="form-group">
                    <label for="completion-files">Upload Files (Optional):</label>
                    <input type="file" name="completion_files[]" id="completion-files" multiple accept=".zip,.rar,.7z,.tar,.gz">
                    <small class="file-info">Upload compressed files (.zip, .rar, .7z, .tar, .gz). These files will be available for download by the buyer once the order is completed.</small>
                </div>
                <div id="upload-progress" class="upload-progress" style="display: none;">
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                    <span class="progress-text">Uploading...</span>
                </div>
                <div id="form-message" class="form-message" style="display: none;"></div>
                <div class="form-actions">
                    <button type="button" class="btn btn-text" onclick="OverlaySystem.close('complete-order-overlay')">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="complete-order-btn">Complete Order</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const closeBtn = document.querySelector('#complete-order-overlay .close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                OverlaySystem.close('complete-order-overlay');
            });
        }

        window.showCompleteOrderOverlay = function(purchaseId) {
            if (!purchaseId) {
                console.error('Purchase ID is required');
                return;
            }

            document.getElementById('purchase-id').value = purchaseId;

            document.getElementById('complete-order-form').reset();
            document.getElementById('purchase-id').value = purchaseId;
            document.getElementById('form-message').style.display = 'none';
            document.getElementById('upload-progress').style.display = 'none';

            OverlaySystem.open('complete-order-overlay');
        };

        const form = document.getElementById('complete-order-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitBtn = document.getElementById('complete-order-btn');
                const progressDiv = document.getElementById('upload-progress');
                const messageDiv = document.getElementById('form-message');
                const progressFill = progressDiv.querySelector('.progress-fill');
                const progressText = progressDiv.querySelector('.progress-text');

                submitBtn.disabled = true;
                submitBtn.textContent = 'Processing...';
                progressDiv.style.display = 'block';
                messageDiv.style.display = 'none';

                const formData = new FormData(form);

                const xhr = new XMLHttpRequest();

                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percentComplete = (e.loaded / e.total) * 100;
                        progressFill.style.width = percentComplete + '%';
                        progressText.textContent = `Uploading... ${Math.round(percentComplete)}%`;
                    }
                });

                xhr.addEventListener('load', function() {
                    progressDiv.style.display = 'none';
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Complete Order';

                    console.log('Response status:', xhr.status);
                    console.log('Response text:', xhr.responseText);

                    try {
                        const response = JSON.parse(xhr.responseText);

                        if (response.success) {
                            messageDiv.className = 'form-message success';
                            messageDiv.textContent = response.message;

                            if (response.uploaded_files && response.uploaded_files.length > 0) {
                                messageDiv.textContent += ` (${response.uploaded_files.length} file(s) uploaded)`;
                            }

                            if (response.warnings && response.warnings.length > 0) {
                                messageDiv.textContent += '\n\nWarnings:\n' + response.warnings.join('\n');
                            }

                            messageDiv.style.display = 'block';

                            setTimeout(function() {
                                OverlaySystem.close('complete-order-overlay');
                                window.location.reload();
                            }, 2000);

                        } else {
                            messageDiv.className = 'form-message error';
                            messageDiv.textContent = response.message || 'An error occurred while completing the order.';
                            messageDiv.style.display = 'block';
                        }
                    } catch (error) {
                        console.error('JSON parse error:', error);
                        console.error('Response was:', xhr.responseText);
                        messageDiv.className = 'form-message error';
                        messageDiv.textContent = 'Server returned invalid response. Check console for details.';
                        messageDiv.style.display = 'block';
                    }
                });

                xhr.addEventListener('error', function() {
                    progressDiv.style.display = 'none';
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Complete Order';

                    messageDiv.className = 'form-message error';
                    messageDiv.textContent = 'Network error. Please check your connection and try again.';
                    messageDiv.style.display = 'block';
                });

                xhr.open('POST', form.action);
                xhr.send(formData);
            });
        }
    });
</script>

<style>
    .file-info {
        display: block;
        margin-top: 5px;
        color: #666;
        font-size: 0.85rem;
        line-height: 1.4;
    }

    .upload-progress {
        margin: 15px 0;
        text-align: center;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background-color: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .progress-fill {
        height: 100%;
        background-color: var(--primary);
        width: 0%;
        transition: width 0.3s ease;
    }

    .progress-text {
        font-size: 0.9rem;
        color: #666;
    }

    .form-message {
        padding: 12px;
        border-radius: 6px;
        margin: 15px 0;
        font-size: 0.9rem;
        white-space: pre-line;
    }

    .form-message.success {
        background-color: #d1edff;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .form-message.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    #complete-order-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
    }

    #completion-files {
        width: 100%;
        padding: 8px;
        border: 2px dashed #ddd;
        border-radius: 6px;
        background-color: #fafafa;
        transition: border-color 0.2s;
    }

    #completion-files:hover {
        border-color: #bbb;
    }

    #completion-files:focus {
        outline: none;
        border-color: var(--primary, #3b82f6);
        background-color: #fff;
    }
</style>