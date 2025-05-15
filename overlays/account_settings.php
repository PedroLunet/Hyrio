<?php

declare(strict_types=1);

require_once(__DIR__ . '/../components/button/button.php');

echo '<link rel="stylesheet" href="/css/overlay.css">';
echo '<link rel="stylesheet" href="/css/forms.css">';
echo '<link rel="stylesheet" href="/css/account_settings.css">';

if (!$user) {
    header('Location: /pages/login.php');
    exit;
}

?>

<div class="overlay account-settings-overlay" id="account-settings-overlay">
    <div class="overlay-content">
        <div class="overlay-header">
            <h2>Account Settings</h2>
            <button class="close-btn" aria-label="Close">x</button>
        </div>

        <div class="overlay-body">
            <form action="/actions/update_account_action.php" method="post" class="settings-form" enctype="multipart/form-data">

                <?php if (isset($_SESSION['update_account_settings_error'])): ?>
                    <div class="error-message" id="account-settings-error">
                        <?php
                        echo htmlspecialchars($_SESSION['update_account_settings_error']);
                        unset($_SESSION['update_account_settings_error']);
                        ?>
                    </div>
                <?php endif; ?>

                <fieldset>
                    <legend>Personal Information</legend>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user->getName()); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user->getUsername()); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user->getEmail()); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <input type="text" id="bio" name="bio" value="<?php echo htmlspecialchars($user->getBio()); ?>">
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Change Password</legend>

                    <div class="form-group">
                        <label for="current-password">Current Password</label>
                        <div class="password-container">
                            <input type="password" id="current-password" name="current_password">
                            <button type="button" class="password-toggle" title="Show password"></button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="new-password">New Password</label>
                        <div class="password-container">
                            <input type="password" id="new-password" name="new_password">
                            <button type="button" class="password-toggle" title="Show password"></button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm-password">Confirm New Password</label>
                        <div class="password-container">
                            <input type="password" id="confirm-password" name="confirm_password">
                            <button type="button" class="password-toggle" title="Show password"></button>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Profile Picture</legend>

                    <div class="form-group profile-picture-container">
                        <div class="current-profile-picture">
                            <img src="<?php echo $user->getProfilePic(); ?>" alt="Current profile picture" id="current-profile-pic">
                        </div>
                        <div class="profile-picture-controls">
                            <?php
                            Button::start(['variant' => 'primary', 'class' => 'custom-file-upload', 'onclick' => "document.getElementById('profile-picture').click(); event.preventDefault(); return false;"]);
                            echo '<span>Choose New Picture</span>';
                            Button::end();
                            ?>
                            <input type="file" id="profile-picture" name="profile_picture" accept="image/*" class="file-input">
                            <?php
                            Button::start(['variant' => 'secondary', 'class' => 'remove-profile-picture-btn', 'onclick' => "document.getElementById('current-profile-pic').src = '/database/assets/userProfilePic.jpg'; document.getElementById('profile-picture').value = ''; document.getElementById('remove-profile-picture-flag').value = '1'; event.preventDefault(); return false;"]);
                            echo '<span>Remove Picture</span>';
                            Button::end();
                            ?>
                            <input type="hidden" name="remove_profile_picture" id="remove-profile-picture-flag" value="0">
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Freelancer Settings</legend>

                    <div class="form-group">
                        <p>Do you want to sell on our website? Click here to convert your account!</p>
                </fieldset>

                <fieldset class="delete-account-fieldset">
                    <legend>Delete Account</legend>

                    <div class="form-group">
                        <div class="warning-container">
                            <p class="warning-text">This action is irreversible. All your data, including profile information and associated content, will be permanently deleted.</p>
                            <?php
                            Button::start(['variant' => 'secondary', 'id' => 'delete-account-btn', 'data-action' => 'delete-account']);
                            echo '<span>Delete My Account</span>';
                            Button::end();
                            ?>
                        </div>
                    </div>
                </fieldset>

                <div class="form-actions">
                    <?php
                    Button::start(['variant' => 'text', 'type' => 'button', 'data-action' => 'close']);
                    echo '<span>Cancel</span>';
                    Button::end();
                    Button::start(['variant' => 'primary', 'type' => 'submit']);
                    echo '<span>Save Changes</span>';
                    Button::end();
                    ?>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/js/password-toggle.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const accountSettingsBtn = document.querySelector('[onClick="OverlaySystem.open(\'account-settings-overlay\')"]');
        if (accountSettingsBtn) {
            accountSettingsBtn.addEventListener('click', function() {
                const errorMessage = document.getElementById('account-settings-error');
                if (errorMessage) {
                    errorMessage.style.display = 'none';
                }
            });
        }

        const profilePicInput = document.getElementById('profile-picture');
        const currentProfilePic = document.getElementById('current-profile-pic');
        const removeProfilePicBtn = document.getElementById('remove-profile-picture');
        const removeProfilePicFlag = document.getElementById('remove-profile-picture-flag');

        if (profilePicInput) {
            profilePicInput.addEventListener('change', function() {
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

                            currentProfilePic.src = canvas.toDataURL('image/jpeg', 0.9);
                            removeProfilePicFlag.value = '0';
                        };
                        img.src = e.target.result;
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }

        const deleteAccountBtn = document.querySelector('[data-action="delete-account"]');
        if (deleteAccountBtn) {
            deleteAccountBtn.addEventListener('click', function(event) {
                event.preventDefault();
                if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/actions/delete_account_action.php';
                    form.style.display = 'none';

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'delete_account';
                    input.value = '1';
                    form.appendChild(input);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    });
</script>