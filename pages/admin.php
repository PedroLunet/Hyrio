<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../includes/auth.php');

$loggedInUser = Auth::getInstance()->getUser();

if (!$loggedInUser || $loggedInUser['role'] !== 'admin') {
    header('Location: /');
    exit();
}

head();
drawHeader();

// Add CSS for admin page
echo '<link rel="stylesheet" href="../css/forms.css">';
echo '<link rel="stylesheet" href="../css/admin.css">';

?><main class="admin-panel">
    <h1>Admin Panel</h1>
    <section class="admin-dashboard">
        <h2>Dashboard</h2>
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Total Users</h3>
                <p class="stat-number">100 </p>
            </div>
            <div class="stat-card">
                <h3>Total Services</h3>
                <p class="stat-number">50 </p>
            </div>
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <p class="stat-number">2000€ </p>
            </div>
        </div>
    </section>
    <section class="admin-actions">
        <h2>Management</h2>
        <div class="action-buttons"><button class="admin-button active" data-target="users-section">Manage Users</button><button class="admin-button" data-target="tickets-section">Manage Tickets</button><button class="admin-button" data-target="departments-section">Manage Departments</button><button class="admin-button" data-target="settings-section">System Settings</button></div>
    </section>
    <!-- Users Section -->
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
                    <tr>
                        <td>1</td>
                        <td>johndoe</td>
                        <td>john@example.com</td>
                        <td>user</td>
                        <td><button class="action-btn edit-btn">Edit</button><button class="action-btn delete-btn">Delete</button></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>janesmith</td>
                        <td>jane@example.com</td>
                        <td>admin</td>
                        <td><button class="action-btn edit-btn">Edit</button><button class="action-btn delete-btn">Delete</button></td>
                    </tr>
                </tbody>
            </table>
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
    <!-- Settings Section -->
    <section id="settings-section" class="admin-content-section">
        <h2>System Settings</h2>
        <div class="section-content">
            <form class="admin-form">
                <div class="form-group"><label for="site-name">Site Name</label><input type="text" id="site-name" class="form-control" value="LTW Ticketing System"></div>
                <div class="form-group"><label for="admin-email">Admin Email</label><input type="email" id="admin-email" class="form-control" value="admin@example.com"></div>
                <div class="form-group"><label for="maintenance-mode">Maintenance Mode</label><select id="maintenance-mode" class="form-control">
                        <option value="0">Off</option>
                        <option value="1">On</option>
                    </select></div>
                <div class="form-actions"><button type="submit" class="btn btn-primary">Save Settings</button></div>
            </form>
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
</script><?php drawFooter();
            ?>