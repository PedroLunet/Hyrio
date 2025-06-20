<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../includes/database.php');
require_once(__DIR__ . '/../components/button/button.php');
require_once(__DIR__ . '/../components/card/card.php');
require_once(__DIR__ . '/../includes/auth.php');

$user = User::getUserByUsername((string) $_GET['username']);
$loggedInUser = Auth::getInstance()->getUser();

if (!$user) {
    header('Location: /');
    exit;
}

head();

echo '<link rel="stylesheet" href="/css/profile.css">';
echo '<link rel="stylesheet" href="/css/landing.css">';
echo '<script src="/js/overlay.js"></script>';

drawHeader();

if ($loggedInUser && $loggedInUser['id'] === $user->getId()) {
    require_once(__DIR__ . '/../overlays/account_settings.php');
    require_once(__DIR__ . '/../overlays/view_order.php');

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
            } elseif ($loggedInUser) {
                echo '<div class="profile-actions">';
                echo '<button class="cta-button primary" onclick="window.location.href=\'/pages/messages.php?user=' . urlencode($user->getUsername()) . '\'">';
                echo '<span>Send Message</span>';
                echo '</button>';
                echo '</div>';
            }
            ?>
        </header>

        <section>
            <div class="section-header">
                <h2>About me</h2>
            </div>
            <div class="section-content">
                <?php
                if ($user->getBio() === '') {
                    echo '<p>This user has not set a bio yet.</p>';
                } else {
                    echo '<p>' . htmlspecialchars($user->getBio()) . '</p>';
                }
                ?>
            </div>
        </section>

        <?php
        // Show services offered by this user (visible to everyone if user is a seller)
        if ($user->isSeller()) {
            require_once(__DIR__ . '/../database/classes/service.php');
            require_once(__DIR__ . '/../database/classes/category.php');

            // Get services with seller and category information for proper card rendering
            try {
                $db = Database::getInstance();
                $stmt = $db->prepare('
                    SELECT services.*, users.name as seller_name, categories.name as category_name
                    FROM services 
                    JOIN users ON services.seller = users.id
                    JOIN categories ON services.category = categories.id
                    WHERE services.seller = ?
                    ORDER BY services.id DESC
                ');
                $stmt->execute([$user->getId()]);
                $userServices = $stmt->fetchAll();
            } catch (PDOException $e) {
                $userServices = [];
            }

            echo '<section>';
            echo '<div class="section-header">';
            echo '<h2>Services Offered</h2>';
            echo '</div>';
            echo '<div class="section-content">';

            if (empty($userServices)) {
                echo '<p>This user hasn\'t listed any services yet.</p>';
            } else {
                Card::renderGrid($userServices);
            }

            echo '</div>';
            echo '</section>';
        }
        ?>

        <?php
        if ($loggedInUser && $loggedInUser['id'] === $user->getId()) {
            echo '<section>';
            echo '<div class="section-header">';
            echo '<h2>Favorites</h2>';
            echo '</div>';
            echo '<div class="section-content">';

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

            echo '</div>';
            echo '</section>';

            // Purchased services section
            require_once(__DIR__ . '/../database/classes/purchase.php');
            $purchases = Purchase::getByUser($user->getId());
            echo '<section class="profile-purchases">';
            echo '<div class="section-header">';
            echo '<h2>Purchased Services</h2>';
            echo '</div>';
            if (empty($purchases)) {
                echo '<p>You haven\'t purchased any services yet.</p>';
            } else {
                echo '<div class="section-content">';
                echo '<table class="purchases-table">';
                echo '<thead><tr><th>Service</th><th>Price</th><th>Seller</th><th>Date</th><th>Actions</th></tr></thead>';
                echo '<tbody>';
                foreach ($purchases as $purchase) {
                    $service = Service::getServiceById($purchase['service_id']);
                    if ($service) {
                        $sellerObj = User::getUserById($service->getSeller());
                        echo '<tr>';
                        echo '<td><a href="/pages/service.php?id=' . $service->getId() . '">' . htmlspecialchars($service->getName()) . '</a></td>';
                        echo '<td class="price">' . htmlspecialchars(number_format($service->getPrice(), 2)) . '€</td>';
                        echo '<td class="seller">' . htmlspecialchars($sellerObj ? $sellerObj->getName() : 'Unknown') . '</td>';
                        echo '<td class="status">' . htmlspecialchars($purchase['status']) . '</td>';
                        echo '<td class="message-btn">';
                        echo '<button class="action-btn view-btn" onclick="showOrderDetails(' . $purchase['id'] . ')">View</button>';
                        echo '</td>';
                        echo '</tr>';
                    }
                }
                echo '</tbody>';
                echo '</table>';
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