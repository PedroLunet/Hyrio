<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../components/card/card.php');
require_once(__DIR__ . '/../database/classes/service.php');

$loggedInUser = Auth::getInstance()->getUser();

if (!$loggedInUser || !$loggedInUser['is_seller']) {
    header('Location: /');
    exit;
}

head();
drawHeader();

echo '<link rel="stylesheet" href="../css/panel.css">';
echo '<link rel="stylesheet" href="../css/service.css">';

?>

<main>
    <h1>Welcome, <?php echo htmlspecialchars($loggedInUser['name']); ?></h1>

    <section>
        <!-- <h2>Welcome, <?php echo htmlspecialchars($loggedInUser['name']); ?></h2> -->
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
            <h2>Your Listing</h2>
            <button href="/pages/create-service.php" class="section-header-button">Create New Listing</button>
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