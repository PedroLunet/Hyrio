<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../database/classes/service.php');

$loggedInUser = Auth::getInstance()->getUser();

if (!$loggedInUser || $loggedInUser['role'] !== 'admin') {
    header('Location: /');
    exit();
}

head();
drawHeader();

echo '<link rel="stylesheet" href="../css/forms.css">';
echo '<link rel="stylesheet" href="../css/admin.css">';

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
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <p class="stat-number">2000€ </p>
            </div>
        </div>
    </section>
    <section class="admin-actions">
        <h2>Management</h2>
        <div class="action-buttons"><button class="admin-button active" data-target="users-section">Manage Users</button><button class="admin-button" data-target="tickets-section">Manage Services</button><button class="admin-button" data-target="departments-section">Manage Categories</button></div>
    </section>

    <section id="users-section" class="admin-content-section active">
        <h2>Users Management</h2>
        <div class="section-content">
            <table class="admin-table">
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
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars(strval($user['id'])) . '</td>';
                        echo '<td>' . htmlspecialchars(strval($user['username'])) . '</td>';
                        echo '<td>' . htmlspecialchars(strval($user['email'])) . '</td>';
                        echo '<td>' . htmlspecialchars(strval($user['role'])) . '</td>';
                        if ($user['role'] === 'admin') {
                            echo '<td>
                        <button class="action-btn delete-btn" data-id="' . $user['id'] . '">Delete</button>
                        <button class="action-btn demote-btn" data-id="' . $user['id'] . '">Demote</button>
                                  </td>';
                        } else {
                            echo '<td>
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
            Pagination::render($page, intval($totalPages), 'user_page');
            ?>
        </div>
    </section>
    <!-- Tickets Section -->
    <section id="tickets-section" class="admin-content-section">
        <h2>Tickets Management</h2>
        <div class="section-content">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Payment Issue</td>
                        <td>johndoe</td>
                        <td>Open</td>
                        <td>2023-05-10</td>
                        <td><button class="action-btn edit-btn">View</button><button class="action-btn delete-btn">Close</button></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Account Access</td>
                        <td>janesmith</td>
                        <td>Resolved</td>
                        <td>2023-05-09</td>
                        <td><button class="action-btn edit-btn">View</button><button class="action-btn delete-btn">Close</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    <!-- Departments Section -->
    <section id="departments-section" class="admin-content-section">
        <h2>Departments Management</h2>
        <div class="section-content">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Technical Support</td>
                        <td>Handle technical issues and bug reports</td>
                        <td><button class="action-btn edit-btn">Edit</button><button class="action-btn delete-btn">Delete</button></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Customer Service</td>
                        <td>Handle customer inquiries and concerns</td>
                        <td><button class="action-btn edit-btn">Edit</button><button class="action-btn delete-btn">Delete</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function() {
            // Get all buttons and content sections
            const buttons = document.querySelectorAll('.admin-button[data-target]');
            const sections = document.querySelectorAll('.admin-content-section');

            // Add click event to each button
            buttons.forEach(button => {
                    button.addEventListener('click', function() {
                            // Remove active class from all buttons and sections
                            buttons.forEach(btn => btn.classList.remove('active'));
                            sections.forEach(section => section.classList.remove('active'));

                            // Add active class to clicked button
                            this.classList.add('active');

                            // Get target section and activate it
                            const targetId = this.dataset.target;
                            document.getElementById(targetId).classList.add('active');
                        }

                    );
                }

            );
        }

    );

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            event.preventDefault();

            const id = event.target.dataset.id;
            const row = event.target.closest('tr');

            let type = document.querySelector('.admin-button.active').dataset.target.replace('-section', '');

            if (type === 'users') type = 'user';
            else if (type === 'tickets') type = 'service';
            else if (type === 'departments') type = 'category';

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

                document.body.appendChild(form);
                form.submit();
            }
        }
    });
</script><?php drawFooter();
            ?>