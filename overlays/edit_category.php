<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../components/button/button.php');
require_once(__DIR__ . '/../database/classes/category.php');

echo '<link rel="stylesheet" href="/css/overlay.css">';
echo '<link rel="stylesheet" href="/css/forms.css">';

$user = Auth::getInstance()->getUser();

if (!$user || !$user['is_admin']) {
    header('Location: /pages/login.php');
    exit();
}

?>

<div class="overlay edit-category-overlay" id="edit-category-overlay">
    <div class="overlay-content">
        <div class="overlay-header">
            <h2>Edit Category</h2>
            <button class="close-btn" aria-label="Close">X</button>
        </div>
        <div class="overlay-body">
            <form id="edit-category-form" method="POST" action="/actions/admin_action.php">
                <input type="hidden" name="type" value="category">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="category-id">
                <?php
                $section = isset($_GET['section']) ? $_GET['section'] : 'categories';
                echo '<input type="hidden" name="section" value="' . $section . '">';
                ?>
                <div class="form-group">
                    <label for="name">Category Name:</label>
                    <input type="text" id="category-name" name="name" required>
                </div>
                <div class="form-actions">
                    <?php
                    Button::start(['variant' => 'primary', 'type' => 'submit', 'form' => 'edit-category-form']);
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
            if (event.target.classList.contains('edit-btn') && event.target.dataset.categoryId) {
                event.preventDefault();
                const categoryId = event.target.dataset.categoryId;
                const categoryName = event.target.closest('tr').querySelector('td:nth-child(2)').textContent.trim();

                document.getElementById('category-id').value = categoryId;
                document.getElementById('category-name').value = categoryName;

                OverlaySystem.open('edit-category-overlay');
            }
        });

        const form = document.getElementById('edit-category-form');
        if (form) {
            form.addEventListener('submit', function(formEvent) {
                const nameInput = document.getElementById('category-name');
                if (!nameInput.value.trim()) {
                    formEvent.preventDefault();
                    alert('Category name cannot be empty');
                }
            });
        }
    })();
</script>