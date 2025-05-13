<?php

declare(strict_types=1);

echo '<link rel="stylesheet" href="/css/overlay.css">';
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
                <fieldset>
                    <legend>Personal Information</legend>

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
                        <input type="password" id="current-password" name="current_password">
                    </div>

                    <div class="form-group">
                        <label for="new-password">New Password</label>
                        <input type="password" id="new-password" name="new_password">
                    </div>

                    <div class="form-group">
                        <label for="confirm-password">Confirm New Password</label>
                        <input type="password" id="confirm-password" name="confirm_password">
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Profile Picture</legend>

                    <div class="form-group">
                        <label for="profile-picture">Upload New Picture</label>
                        <input type="file" id="profile-picture" name="profile_picture" accept="image/*">
                    </div>
                </fieldset>

                <div class="form-actions">
                    <button type="submit" class="btn primary-btn">Save Changes</button>
                    <button type="button" class="btn secondary-btn" data-action="close">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>