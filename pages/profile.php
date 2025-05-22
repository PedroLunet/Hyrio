<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../components/button/button.php');
require_once(__DIR__ . '/../components/card/card.php');
require_once(__DIR__ . '/../includes/auth.php');

$user = User::getUserByUsername((string)$_GET['username']);
$loggedInUser = Auth::getInstance()->getUser();

if (!$user) {
    header('Location: /');
    exit;
}

head();

echo '<link rel="stylesheet" href="/css/profile.css">';
echo '<script src="/js/overlay.js"></script>';

drawHeader();

if ($loggedInUser && $loggedInUser['id'] === $user->getId()) {
    require_once(__DIR__ . '/../overlays/account_settings.php');

    if (isset($_SESSION['show_account_settings']) && $_SESSION['show_account_settings'] === true) {
        unset($_SESSION['show_account_settings']);

        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                OverlaySystem.open("account-settings-overlay");
            });
        </script>';
    }
}

?>

<main class="profile-container">
    <article class="profile">
        <header class="profile-header">
            <?php
            echo '<img src="' . htmlspecialchars($user->getProfilePic()) . '" alt="Profile Picture" class="profile-picture">';
            echo '<h1>' . htmlspecialchars($user->getName()) . '</h1>';
            echo '<p class="profile-username">@' . htmlspecialchars($user->getUsername()) . '</p>';

            if ($loggedInUser && $loggedInUser['id'] === $user->getId()) {
                echo '<div class="profile-actions">';
                Button::start(['variant' => 'primary', 'onClick' => 'OverlaySystem.open("account-settings-overlay")']);
                echo '<span>Account Settings</span>';
                Button::end();
                echo '</div>';
            }
            ?>
        </header>

        <section>
            <div class="section-header">
                <h2>About me</h2>
            </div>
            <?php
            if ($user->getBio() === '') {
                echo '<p>This user has not set a bio yet.</p>';
            } else {
                echo '<p>' . htmlspecialchars($user->getBio()) . '</p>';
            }
            ?>
        </section>
        <?php
        if ($loggedInUser && $loggedInUser['id'] === $user->getId()) {
            echo '<section>';
            echo '<div class="section-header">';
            echo '<h2>Favorites</h2>';
            echo '</div>';
            
            $favorites = $user->getUserFavorites($user->getId());
            
            if (empty($favorites)) {
                echo '<p>You haven\'t liked any services yet. Try saving one by clicking the heart button in the service page.</p>';
            } else {
                echo '<div class="services-row">';
                foreach ($favorites as $favorite) {
                    Card::render($favorite);
                }
                echo '</div>';
            }
            
            echo '</section>';
        }
        ?>
    </article>
</main>

<?php
drawFooter();
?>