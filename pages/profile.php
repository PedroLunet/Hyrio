<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../database/classes/user.php');

$user = User::getUserByUsername((string)$_GET['username']);

head();
drawHeader();

?>

<main class="profile-container">
    <article class="profile">
        <header class="profile-header">
            <?php
            echo '<img src="/' . htmlspecialchars($user->getProfilePic()) . '" alt="Profile Picture" class="profile-picture">';
            echo '<h1>' . htmlspecialchars($user->getName()) . '</h1>';
            ?>
        </header>

        <section class="profile-info">

        </section>

        <section class="profile-bio">
            <?php
            if ($user->getBio() === '') {
                echo '<p>This user has not set a bio yet.</p>';
            } else {
                echo '<p>' . htmlspecialchars($user->getBio()) . '</p>';
            }
            ?>
        </section>

        <?php
        $loggedInUser = Auth::getInstance()->getUser();
        if ($loggedInUser && $loggedInUser['id'] === $user->getId()) {
            echo '<section class="profile-actions">';
            echo '<a href="account_settings.php" class="button">Account Settings</a>';
            echo '</section>';
        }
        ?>
    </article>
</main>

<?php

drawFooter();

?>