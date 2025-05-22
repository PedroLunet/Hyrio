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

<div class="overlay add-category-overlay" id="add-category-overlay">
    <div class="overlay-content">
        <div class="overlay-header">
            <h2>Add New Category</h2>
            <button class="close-btn" aria-label="Close">X</button>
        </div>
        <div class="overlay-body">
            <form id="add-category-form" method="POST" action="/actions/admin_action.php">
                <input type="hidden" name="type" value="category">
                <input type="hidden" name="action" value="add">
                <?php
                $section = isset($_GET['section']) ? $_GET['section'] : 'categories';
                echo '<input type="hidden" name="section" value="' . $section . '">';
                ?>

                <div class="form-group">
                    <label for="new-category-name">Category Name:</label>
                    <input type="text" id="new-category-name" name="name" required>
                </div>
                <div class="form-actions">
                    <?php
                    Button::start(['variant' => 'primary', 'type' => 'submit', 'form' => 'add-category-form']);
                    echo '<span>Add Category</span>';
                    Button::end();
                    ?>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // We'll let the admin.php handle the add category button click

    // Form validation for the add category form
    (function() {
        const addForm = document.getElementById('add-category-form');
        if (addForm) {
            addForm.addEventListener('submit', function(formEvent) {
                const nameInput = document.getElementById('new-category-name');
                if (!nameInput.value.trim()) {
                    formEvent.preventDefault();
                    alert('Category name cannot be empty');
                }
            });
        }
    })();
</script>