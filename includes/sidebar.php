<?php
// Get the dashboard type and current page from the calling page
$dashboard_type = isset($dashboard_type) ? $dashboard_type : 'User';
$current_page = isset($current_page) ? $current_page : 'overview';
?>
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-sm min-h-screen">
            <nav class="mt-8">
                <div class="px-4 space-y-2">
                    <?php if ($dashboard_type === 'User'): ?>
                        <a href="dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'overview' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-tachometer-alt mr-3"></i>Overview
                        </a>
                        <a href="book-ambulance.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'book-ambulance' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-phone-alt mr-3"></i>Book Ambulance
                        </a>
                        <a href="my-bookings.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'my-bookings' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-calendar-check mr-3"></i>My Bookings
                        </a>
                        <a href="payment-history.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'payment-history' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-receipt mr-3"></i>Payment History
                        </a>
                        <a href="saved-locations.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'saved-locations' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-map-marker-alt mr-3"></i>Saved Locations
                        </a>
                        <a href="emergency-contacts.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'emergency-contacts' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-address-book mr-3"></i>Emergency Contacts
                        </a>
                        <a href="profile-settings.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'profile-settings' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-user-cog mr-3"></i>Profile Settings
                        </a>
                    <?php elseif ($dashboard_type === 'Provider'): ?>
                        <a href="dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'overview' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-tachometer-alt mr-3"></i>Overview
                        </a>
                        <a href="earnings.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'earnings' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-money-bill-wave mr-3"></i>Earnings
                        </a>
                        <a href="ambulances.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'ambulances' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-ambulance mr-3"></i>My Ambulances
                        </a>
                        <a href="drivers.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'drivers' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-user-tie mr-3"></i>My Drivers
                        </a>
                        <a href="service-areas.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'service-areas' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-map-marked-alt mr-3"></i>Service Areas
                        </a>
                        <a href="my-bookings.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'my-bookings' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-calendar-check mr-3"></i>My Bookings
                        </a>
                        <a href="all-bookings.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'all-bookings' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-list mr-3"></i>All Bookings
                        </a>
                        <a href="profile-settings.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'profile-settings' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-user-cog mr-3"></i>Profile Settings
                        </a>
                    <?php elseif ($dashboard_type === 'Admin'): ?>
                        <a href="dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'overview' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-tachometer-alt mr-3"></i>Overview
                        </a>
                        <a href="reports.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'reports' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-chart-bar mr-3"></i>Reports & Analytics
                        </a>
                        <a href="users.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'users' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-users mr-3"></i>Users Management
                        </a>
                        <a href="provider-approval.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'provider-approval' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-user-check mr-3"></i>Provider Approval
                        </a>
                        <a href="ambulances.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'ambulances' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-ambulance mr-3"></i>Ambulances
                        </a>
                        <a href="drivers.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'drivers' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-user-tie mr-3"></i>Drivers
                        </a>
                        <a href="bookings.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'bookings' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-calendar-check mr-3"></i>Bookings
                        </a>
                        <a href="emergency-contacts.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'emergency-contacts' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-address-book mr-3"></i>Emergency Contacts
                        </a>
                        <a href="system-settings.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'system-settings' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-cog mr-3"></i>System Settings
                        </a>
                        <a href="backup-restore.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg <?php echo $current_page === 'backup-restore' ? 'bg-red-50 text-red-600' : ''; ?>">
                            <i class="fas fa-database mr-3"></i>Backup & Restore
                        </a>
                    <?php endif; ?>
                </div>
            </nav>
        </aside> 