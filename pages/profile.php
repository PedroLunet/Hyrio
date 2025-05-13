<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../components/button/button.php');
require_once(__DIR__ . '/../includes/auth.php');

$user = User::getUserByUsername((string)$_GET['username']);
$loggedInUser = Auth::getInstance()->getUser();

head();

echo '<link rel="stylesheet" href="/css/profile.css">';

drawHeader();

?>

<main class="profile-container">
    <article class="profile">
        <header class="profile-header">
            <?php
            echo '<img src="/' . htmlspecialchars($user->getProfilePic()) . '" alt="Profile Picture" class="profile-picture">';
            echo '<h1>' . htmlspecialchars($user->getName()) . '</h1>';
            echo '<p class="profile-username">@' . htmlspecialchars($user->getUsername()) . '</p>';

            if ($loggedInUser && $loggedInUser['id'] === $user->getId()) {
                echo '<div class="profile-actions">';
                Button::start(['variant' => 'primary', 'onClick' => "window.location.href='/pages/account_settings.php'"]);
                echo '<span>Account Settings</span>';
                Button::end();
                echo '</div>';
            }
            ?>
        </header>

        <section>
            <h2>About me</h2>
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
            echo '<h2>Saved services</h2>';
            echo '<p>You haven\'t saved any services yet. Try saving one by clicking on the favorite button in the service page.</p>';
            echo '</section>';
        }
        ?>
    </article>
</main>

<?php

drawFooter();

?>