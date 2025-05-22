<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../components/card/card.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../overlays/add_service.php');

$loggedInUser = Auth::getInstance()->getUser();

if (!$loggedInUser || !$loggedInUser['is_seller']) {
    header('Location: /');
    exit;
}

head();
drawHeader();

if ($loggedInUser) {
    require_once(__DIR__ . '/../overlays/add_service.php');

    if (isset($_SESSION['show_add_service']) && $_SESSION['show_add_service'] === true) {
        unset($_SESSION['show_add_service']);

        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                OverlaySystem.open("add-service-overlay");
            });
        </script>';
    }
}

echo '<link rel="stylesheet" href="../css/panel.css">';
echo '<link rel="stylesheet" href="../css/service.css">';
echo '<script src="/js/overlay.js"></script>';

?>

<main>
    <h1>Welcome, <?php echo htmlspecialchars($loggedInUser['name']); ?></h1>

    <section>
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Total Earnings</h3>
                <p class="stat-number">1 200,00 €</p>
            </div>
            <div class="stat-card">
                <h3>Listings</h3>
                <p class="stat-number">9</p>
            </div>
            <div class="stat-card">
                <h3>Current Orders</h3>
                <p class="stat-number">0</p>
            </div>
        </div>
    </section>
    <section class="">
        <div class="section-header">
            <h2>Your Listings</h2>
            <button class="section-header-button" id="add-listing-btn">Create New Listing</button>
        </div>
        <div class="services-row">
            <?php
            $listings = Service::getServicesBySeller($loggedInUser['id']);
            if (!empty($listings)) {
                foreach ($listings as $listing) {
                    Card::render($listing, false);
                }
            } else {
                echo '<p class="none">You don\'t have any listings yet.</p>';
            }
            ?>
        </div>
    </section>

</main>

<?php
drawFooter();
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof OverlaySystem !== 'undefined' && OverlaySystem.init) {
            OverlaySystem.init();
        }

        const addCategoryBtn = document.getElementById('add-listing-btn');
        if (addCategoryBtn) {
            addCategoryBtn.addEventListener('click', function() {
                OverlaySystem.open('add-service-overlay');
            });
        }
    });
</script>