<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../database/classes/category.php');

$loggedInUser = Auth::getInstance()->getUser();

if (!$loggedInUser || !$loggedInUser['is_admin']) {
    header('Location: /');
    exit();
}

head();
drawHeader();

echo '<link rel="stylesheet" href="../css/forms.css">';
echo '<link rel="stylesheet" href="../css/panel.css">';
echo '<script src="/js/overlay.js"></script>';

?><?php
    if ($loggedInUser && $loggedInUser['is_admin']) {
        require_once(__DIR__ . '/../overlays/edit_category.php');
        require_once(__DIR__ . '/../overlays/add_category.php');
    }
    ?><main class="admin-panel">
    <h1>Admin Panel</h1>

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

    <section class="admin-dashboard">
        <h2>Dashboard</h2>
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Total Users</h3>
                <?php
                echo '<p class="stat-number">' . User::getTotalUsers() . '</p>';
                ?>
            </div>
            <div class="stat-card">
                <h3>Total Services</h3>
                <?php
                echo '<p class="stat-number">' . Service::getTotalServices() . '</p>';
                ?>
            </div>
            <!-- <div class="stat-card">
                <h3>Total Revenue</h3>
                <p class="stat-number">2000€ </p>
            </div> -->
        </div>
    </section>
    <?php
    $section = isset($_GET['section']) ? $_GET['section'] : 'users';
    ?>
    <section class="admin-actions">
        <h2>Management</h2>
        <div class="action-buttons">
            <button class="section-header-button <?php echo $section === 'users' ? 'active' : ''; ?>" data-target="users-section">Manage Users</button>
            <button class="section-header-button <?php echo $section === 'services' ? 'active' : ''; ?>" data-target="services-section">Manage Services</button>
            <button class="section-header-button <?php echo $section === 'categories' ? 'active' : ''; ?>" data-target="categories-section">Manage Categories</button>
        </div>
    </section>

    <section id="users-section" class="panel-content-section <?php echo $section === 'users' ? 'active' : ''; ?>">
        <h2>Users Management</h2>
        <div class="section-content">
            <table class="panel-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $page = isset($_GET['user_page']) ? (int)$_GET['user_page'] : 1;
                    $perPage = 50;
                    $offset = ($page - 1) * $perPage;

                    $users = User::getAllUsers($offset, $perPage);
                    $totalUsers = User::getTotalUsers();
                    $totalPages = ceil($totalUsers / $perPage);

                    foreach ($users as $user) {
                        if ($user['is_admin'] && $user['is_seller']) {
                            $role = 'seller, admin';
                        } elseif ($user['is_admin']) {
                            $role = 'admin';
                        } elseif ($user['is_seller']) {
                            $role = 'seller';
                        } else {
                            $role = 'user';
                        }
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars(strval($user['id'])) . '</td>';
                        echo '<td>' . htmlspecialchars(strval($user['username'])) . '</td>';
                        echo '<td>' . htmlspecialchars(strval($user['email'])) . '</td>';
                        echo '<td>' . htmlspecialchars(strval($role)) . '</td>';
                        if ($user['is_admin']) {
                            echo '<td>
                        <button class="action-btn view-btn" onclick="window.location.href=\'/pages/profile.php?username=' . $user['username'] . '\'">View</button>
                        <button class="action-btn delete-btn" data-id="' . $user['id'] . '">Delete</button>
                        <button class="action-btn demote-btn" data-id="' . $user['id'] . '">Demote</button>
                                  </td>';
                        } else {
                            echo '<td>
                        <button class="action-btn view-btn" onclick="window.location.href=\'/pages/profile.php?username=' . $user['username'] . '\'">View</button>
                        <button class="action-btn delete-btn" data-id="' . $user['id'] . '">Delete</button>
                        <button class="action-btn promote-btn" data-id="' . $user['id'] . '">Promote</button>
                                  </td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

            <?php
            require_once(__DIR__ . '/../components/pagination/pagination.php');
            Pagination::render($page, intval($totalPages), 'user_page', ['section' => $section]);
            ?>
        </div>
    </section>
    <section id="services-section" class="panel-content-section <?php echo $section === 'services' ? 'active' : ''; ?>">
        <h2>Services Management</h2>
        <div class="section-content">
            <table class="panel-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Seller</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $page = isset($_GET['service_page']) ? (int)$_GET['service_page'] : 1;
                    $perPage = 50;
                    $offset = ($page - 1) * $perPage;

                    $services = Service::getAllServices($offset, $perPage);
                    $totalServices = Service::getTotalServices();
                    $totalPages = ceil($totalServices / $perPage);

                    foreach ($services as $service) {
                        $user = User::getUserById($service['seller']);
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars(strval($service['id'])) . '</td>';
                        echo '<td>' . htmlspecialchars(strval($service['name'])) . '</td>';
                        echo '<td>' . htmlspecialchars(strval($user->getName())) . '</td>';
                        echo '<td>' . htmlspecialchars(strval($service['category'])) . '</td>';
                        echo '<td>' . htmlspecialchars(strval($service['price'])) . '</td>';
                        echo '<td>
                            <button class="action-btn view-btn" onclick="window.location.href=\'/pages/service.php?id=' . $service['id'] . '\'">View</button>
                            <button class="action-btn delete-btn" data-id="' . $service['id'] . '">Delete</button>
                        </td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

            <?php
            require_once(__DIR__ . '/../components/pagination/pagination.php');
            Pagination::render($page, intval($totalPages), 'service_page', ['section' => $section]);
            ?>
        </div>
    </section>
    <section id="categories-section" class="panel-content-section <?php echo $section === 'categories' ? 'active' : ''; ?>">
        <div class="section-header">
            <h2>Categories Management</h2>
            <button class="section-header-button" id="add-category-btn">Add Category</button>
        </div>
        <div class="section-content">
            <table class="panel-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $page = isset($_GET['category_page']) ? (int)$_GET['category_page'] : 1;
                    $perPage = 50;
                    $offset = ($page - 1) * $perPage;

                    $categories = Category::getAllCategories($offset, $perPage);
                    $totalCategories = Category::getTotalCategories();
                    $totalPages = ceil($totalCategories / $perPage);

                    foreach ($categories as $category) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars(strval($category['id'])) . '</td>';
                        echo '<td>' . htmlspecialchars($category['name']) . '</td>';
                        echo '<td>
                            <button class="action-btn edit-btn" data-category-id="' . $category['id'] . '">Edit</button>
                            <button class="action-btn delete-btn" data-id="' . $category['id'] . '">Delete</button>
                        </td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

            <?php
            require_once(__DIR__ . '/../components/pagination/pagination.php');
            Pagination::render($page, intval($totalPages), 'category_page', ['section' => $section]);
            ?>
        </div>
    </section>
</main>
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

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            event.preventDefault();

            const id = event.target.dataset.id;
            const row = event.target.closest('tr');

            let type = document.querySelector('.section-header-button.active').dataset.target.replace('-section', '');

            if (type === 'users') type = 'user';
            else if (type === 'services') type = 'service';
            else if (type === 'categories') type = 'category';

            if (confirm(`Are you sure you want to delete this ${type}? This action cannot be undone.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/actions/admin_action.php`;
                form.style.display = 'none';

                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                idInput.value = id;
                form.appendChild(idInput);

                const typeInput = document.createElement('input');
                typeInput.type = 'hidden';
                typeInput.name = 'type';
                typeInput.value = type;
                form.appendChild(typeInput);

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

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('promote-btn')) {
            event.preventDefault();

            const id = event.target.dataset.id;
            const row = event.target.closest('tr');

            if (confirm(`Are you sure you want to promote this user to admin?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/actions/admin_action.php`;
                form.style.display = 'none';

                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                idInput.value = id;
                form.appendChild(idInput);

                const typeInput = document.createElement('input');
                typeInput.type = 'hidden';
                typeInput.name = 'type';
                typeInput.value = 'user';
                form.appendChild(typeInput);

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'promote';
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

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('demote-btn')) {
            event.preventDefault();

            const id = event.target.dataset.id;
            const row = event.target.closest('tr');

            if (confirm(`Are you sure you want to demote this user?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/actions/admin_action.php`;
                form.style.display = 'none';

                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                idInput.value = id;
                form.appendChild(idInput);

                const typeInput = document.createElement('input');
                typeInput.type = 'hidden';
                typeInput.name = 'type';
                typeInput.value = 'user';
                form.appendChild(typeInput);

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'demote';
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

    document.addEventListener("DOMContentLoaded", function() {
        if (typeof OverlaySystem !== 'undefined' && OverlaySystem.init) {
            OverlaySystem.init();
        }

        const addCategoryBtn = document.getElementById('add-category-btn');
        if (addCategoryBtn) {
            addCategoryBtn.addEventListener('click', function() {
                OverlaySystem.open('add-category-overlay');
            });
        }
    });
</script>

<?php drawFooter(); ?>