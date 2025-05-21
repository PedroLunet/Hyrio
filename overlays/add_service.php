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

<div class="overlay add-service-overlay" id="add-service-overlay">
    <div class="overlay-content">
        <div class="overlay-header">
            <h2>Add New Service</h2>
            <button class="close-btn" aria-label="Close">X</button>
        </div>
        <div class="overlay-body">
            <form id="add-service-form" method="POST" action="/actions/service_action.php">
                <input type="hidden" name="type" value="service">
                <input type="hidden" name="action" value="add">
                <?php
                $section = isset($_GET['section']) ? $_GET['section'] : 'services';
                echo '<input type="hidden" name="section" value="' . $section . '">';
                ?>
                <div class="form-group">
                    <label for="service-name">Service Name:</label>
                    <input type="text" id="service-name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="service-description">Description:</label>
                    <textarea id="service-description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="service-price">Price (€):</label>
                    <input type="number" id="service-price" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="service-category">Category:</label>
                    <select id="service-category" name="category_id" required>
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
                <div class="form-group">
                    <label for="service-image">Image:</label>
                    <input type="file" id="service-image" name="image" accept="image/*">
                </div>
                <div class="form-actions">
                    <?php
                    Button::start(['variant' => 'primary', 'type' => 'submit', 'form' => 'add-service-form']);
                    echo '<span>Add Service</span>';
                    Button::end();
                    ?>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    (function() {
        const addForm = document.getElementById('add-service-form');
        if (addForm) {
            addForm.addEventListener('submit', function(formEvent) {
                const nameInput = document.getElementById('service-name');
                const descriptionInput = document.getElementById('service-description');
                const priceInput = document.getElementById('service-price');

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
    })();
</script>