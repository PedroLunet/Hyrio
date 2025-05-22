<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../components/button/button.php');

echo '<link rel="stylesheet" href="/css/overlay.css">';
echo '<link rel="stylesheet" href="/css/forms.css">';

$user = Auth::getInstance()->getUser();

if (!$user || !$user['is_seller']) {
    header('Location: /pages/login.php');
    exit();
}

?>

<div class="overlay edit-service-overlay" id="edit-service-overlay">
    <div class="overlay-content">
        <div class="overlay-header">
            <h2>Edit Service</h2>
            <button class="close-btn" aria-label="Close">X</button>
        </div>
        <div class="overlay-body">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="error-message">
                    <?php
                    echo htmlspecialchars($_SESSION['error_message']);
                    unset($_SESSION['error_message']);
                    ?>
                </div>
            <?php endif; ?>
            <form id="edit-service-form" method="POST" action="/actions/service_action.php" enctype="multipart/form-data">
                <input type="hidden" name="type" value="service">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="service-id">
                <input type="hidden" name="current_image" id="current-image">
                <?php
                $section = isset($_GET['section']) ? $_GET['section'] : 'listings';
                echo '<input type="hidden" name="section" value="' . $section . '">';
                ?>
                <div class="form-group">
                    <label for="edit-service-name">Service Name:</label>
                    <input type="text" id="edit-service-name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="edit-service-description">Description:</label>
                    <textarea id="edit-service-description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="edit-service-price">Price (€):</label>
                    <input type="number" id="edit-service-price" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="edit-service-category">Category:</label>
                    <select id="edit-service-category" name="category_id" required>
                        <?php
                        require_once(__DIR__ . '/../database/classes/category.php');
                        $categories = Category::getAllCategories();
                        foreach ($categories as $category) {
                            echo '<option value="' . htmlspecialchars(strval($category['id'])) . '">'
                                . htmlspecialchars($category['name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group image-upload-container">
                    <label for="edit-service-image">Image:</label>
                    <div class="image-preview">
                        <img src="/assets/placeholder.png" alt="Service image preview" id="edit-service-image-preview">
                    </div>
                    <div class="image-controls">
                        <?php
                        Button::start(['variant' => 'primary', 'class' => 'custom-file-upload', 'onclick' => "document.getElementById('edit-service-image').click(); event.preventDefault(); return false;"]);
                        echo '<span>Choose Image</span>';
                        Button::end();
                        ?>
                        <input type="file" id="edit-service-image" name="image" accept="image/*" class="file-input">
                        <?php
                        Button::start(['variant' => 'secondary', 'class' => 'remove-image-btn', 'onclick' => "document.getElementById('edit-service-image-preview').src = '/assets/placeholder.png'; document.getElementById('edit-service-image').value = ''; event.preventDefault(); return false;"]);
                        echo '<span>Remove Image</span>';
                        Button::end();
                        ?>
                    </div>
                </div>
                <div class="form-actions">
                    <?php
                    Button::start(['variant' => 'text', 'type' => 'button', 'data-action' => 'close']);
                    echo '<span>Cancel</span>';
                    Button::end();
                    Button::start(['variant' => 'primary', 'type' => 'submit', 'form' => 'edit-service-form']);
                    echo '<span>Save Changes</span>';
                    Button::end();
                    ?>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    (function() {
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('edit-btn') && event.target.dataset.serviceId) {
                event.preventDefault();
                const serviceId = event.target.dataset.serviceId;

                // Fetch service data
                fetch(`/actions/service_action.php?action=get&id=${serviceId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to load service data');
                        }
                        return response.json();
                    })
                    .then(service => {
                        // Fill the form with service data
                        document.getElementById('service-id').value = service.id;
                        document.getElementById('edit-service-name').value = service.name;
                        document.getElementById('edit-service-description').value = service.description;
                        document.getElementById('edit-service-price').value = service.price;
                        document.getElementById('edit-service-category').value = service.category;
                        document.getElementById('current-image').value = service.image;

                        // Set the image preview
                        const imagePreview = document.getElementById('edit-service-image-preview');
                        if (service.image && service.image !== '/assets/placeholder.png') {
                            imagePreview.src = service.image;
                        } else {
                            imagePreview.src = '/assets/placeholder.png';
                        }

                        // Open the overlay
                        OverlaySystem.open('edit-service-overlay');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to load service data: ' + error.message);
                    });
            }
        });

        const editForm = document.getElementById('edit-service-form');
        if (editForm) {
            editForm.addEventListener('submit', function(formEvent) {
                const nameInput = document.getElementById('edit-service-name');
                const descriptionInput = document.getElementById('edit-service-description');
                const priceInput = document.getElementById('edit-service-price');

                if (!nameInput.value.trim()) {
                    formEvent.preventDefault();
                    alert('Service name cannot be empty');
                } else if (!descriptionInput.value.trim()) {
                    formEvent.preventDefault();
                    alert('Description cannot be empty');
                } else if (parseFloat(priceInput.value) <= 0) {
                    formEvent.preventDefault();
                    alert('Price must be greater than 0');
                }
            });
        }

        const serviceImageInput = document.getElementById('edit-service-image');
        const serviceImagePreview = document.getElementById('edit-service-image-preview');

        if (serviceImageInput) {
            serviceImageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = new Image();
                        img.onload = function() {
                            const canvas = document.createElement('canvas');
                            const size = Math.min(img.width, img.height);

                            const canvasSize = 300;
                            canvas.width = canvasSize;
                            canvas.height = canvasSize;

                            const ctx = canvas.getContext('2d');

                            ctx.clearRect(0, 0, canvasSize, canvasSize);

                            const offsetX = (img.width - size) / 2;
                            const offsetY = (img.height - size) / 2;

                            ctx.drawImage(img, offsetX, offsetY, size, size, 0, 0, canvasSize, canvasSize);

                            serviceImagePreview.src = canvas.toDataURL('image/jpeg', 0.9);
                        };
                        img.src = e.target.result;
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    })();
</script>