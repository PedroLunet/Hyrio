<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../components/card/card.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../database/classes/purchase.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../overlays/add_service.php');
require_once(__DIR__ . '/../overlays/view_order.php');

$loggedInUser = Auth::getInstance()->getUser();

if (!$loggedInUser || !$loggedInUser['is_seller']) {
    header('Location: /');
    exit;
}

head();
drawHeader();

if ($loggedInUser) {
    require_once(__DIR__ . '/../overlays/add_service.php');
    require_once(__DIR__ . '/../overlays/edit_service.php');
    require_once(__DIR__ . '/../overlays/complete_order.php');

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

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="message success-message">
            <?php
            echo htmlspecialchars($_SESSION['success_message']);
            unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="message error-message">
            <?php
            echo htmlspecialchars($_SESSION['error_message']);
            unset($_SESSION['error_message']);
            ?>
        </div>
    <?php endif; ?>

    <section>
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Listings</h3>
                <?php
                echo '<p class="stat-number">' . htmlspecialchars((string)Service::getTotalServicesBySeller($loggedInUser['id'])) . '</p>';
                ?>
            </div>
            <div class="stat-card">
                <h3>Current Orders</h3>
                <?php
                echo '<p class="stat-number">' . htmlspecialchars((string)Purchase::getTotalPendingPurchasesBySeller($loggedInUser['id'])) . '</p>';
                ?>
            </div>
        </div>
    </section>
    <?php
    $section = isset($_GET['section']) ? $_GET['section'] : 'listings';
    ?>
    <section class="page-actions">
        <h2>Management</h2>
        <div class="action-buttons">
            <button class="section-header-button <?php echo $section === 'listings' ? 'active' : ''; ?>" data-target="listings-section">Listings</button>
            <button class="section-header-button <?php echo $section === 'orders' ? 'active' : ''; ?>" data-target="orders-section">Orders</button>
        </div>
    </section>

    <section id="listings-section" class="panel-content-section <?php echo $section === 'listings' ? 'active' : ''; ?>">
        <div class="section-header">
            <h2>Your Listings</h2>
            <button class="section-header-button" id="add-listing-btn">Create New Listing</button>
        </div>
        <div class="section-content">
            <table class="panel-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $listings = Service::getServicesBySeller($loggedInUser['id']);
                    foreach ($listings as $listing) {
                        $category = Category::getCategoryById($listing['category']);
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars(strval($listing['name'])) . '</td>';
                        echo '<td>' . htmlspecialchars(strval($category->getName())) . '</td>';
                        echo '<td>' . htmlspecialchars(strval($listing['price'])) . ' €</td>';
                        echo '<td>
                            <button class="action-btn view-btn" onclick="window.location.href=\'/pages/service.php?id=' . $listing['id'] . '\'">View</button>
                            <button class="action-btn edit-btn" data-service-id="' . $listing['id'] . '">Edit</button>
                            <button class="action-btn delete-btn" data-id="' . $listing['id'] . '">Delete</button>
                        </td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <section id="orders-section" class="panel-content-section <?php echo $section === 'orders' ? 'active' : ''; ?>">
        <div class="section-header">
            <h2>Your Orders</h2>
        </div>
        <div class="section-content">
            <table class="panel-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Buyer</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $orders = Purchase::getBySeller($loggedInUser['id']);
                    foreach ($orders as $order) {
                        $buyer = User::getUserById($order['user_id']);
                        $service = Service::getServiceById($order['service_id']);
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars(strval($order['id'])) . '</td>';
                        echo '<td>' . htmlspecialchars(strval($buyer->getName())) . '</td>';
                        echo '<td>' . htmlspecialchars(strval($service->getName())) . '</td>';
                        echo '<td>' . htmlspecialchars(strval($order['status'])) . '</td>';
                        echo '<td>
                            <button class="action-btn view-btn" onclick="showOrderDetails(' . $order['id'] . ')">View</button>';

                        if ($order['status'] === 'pending') {
                            echo '<button class="action-btn edit-btn" data-order-id="' . $order['id'] . '" onclick="completeOrder(' . $order['id'] . ')">Complete</button>';
                            echo '<button class="action-btn delete-btn" data-order-id="' . $order['id'] . '" onclick="cancelOrder(' . $order['id'] . ')">Cancel</button>';
                        }

                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

</main>

<?php
drawFooter();
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.section-header-button[data-target]');
            const sections = document.querySelectorAll('.panel-content-section');

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    buttons.forEach(btn => btn.classList.remove('active'));
                    sections.forEach(section => section.classList.remove('active'));

                    this.classList.add('active');

                    const targetId = this.dataset.target;
                    document.getElementById(targetId).classList.add('active');

                    const sectionName = targetId.replace('-section', '');

                    const url = new URL(window.location.href);
                    url.searchParams.set('section', sectionName);

                    window.history.pushState({
                        section: sectionName
                    }, '', url);
                });
            });

            window.addEventListener('popstate', function(event) {
                if (event.state && event.state.section) {
                    const sectionName = event.state.section;
                    const targetId = sectionName + '-section';

                    buttons.forEach(btn => btn.classList.remove('active'));
                    sections.forEach(section => section.classList.remove('active'));

                    const button = document.querySelector(`.section-header-button[data-target="${targetId}"]`);
                    if (button) button.classList.add('active');

                    const section = document.getElementById(targetId);
                    if (section) section.classList.add('active');
                }
            });
            window.history.replaceState({
                section: '<?php echo $section; ?>'
            }, '', window.location.href);
        }

    );

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

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn') && !event.target.dataset.orderId) {
            event.preventDefault();

            const id = event.target.dataset.id;
            const row = event.target.closest('tr');

            let type = document.querySelector('.section-header-button.active').dataset.target.replace('-section', '');

            if (type === 'listings') type = 'listing';

            if (confirm(`Are you sure you want to delete this ${type}? This action cannot be undone.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/actions/service_action.php`;
                form.style.display = 'none';

                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                idInput.value = id;
                form.appendChild(idInput);

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete';
                form.appendChild(actionInput);

                const sectionInput = document.createElement('input');
                sectionInput.type = 'hidden';
                sectionInput.name = 'section';
                sectionInput.value = '<?php echo $section; ?>';
                form.appendChild(sectionInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    });

    function cancelOrder(orderId) {
        if (confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/actions/purchase_action.php';
            form.style.display = 'none';

            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id';
            idInput.value = orderId;
            form.appendChild(idInput);

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'cancel';
            form.appendChild(actionInput);

            const sectionInput = document.createElement('input');
            sectionInput.type = 'hidden';
            sectionInput.name = 'section';
            sectionInput.value = '<?php echo $section; ?>';
            form.appendChild(sectionInput);

            document.body.appendChild(form);
            form.submit();
        }
    }

    function completeOrder(orderId) {
        showCompleteOrderOverlay(orderId);
    }
</script>