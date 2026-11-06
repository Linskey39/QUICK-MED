<?php
require_once '../session_config.php';
include '../config.php';

// Check if user is logged in and is provider
if (!isset($_SESSION['user_id']) || $_SESSION['account_type'] !== 'provider') {
    header('Location: ../login.php');
    exit();
}

$provider_id = $_SESSION['user_id'];

// Fetch data for overview
$ambulances = $conn->query("SELECT * FROM ambulances WHERE provider_id = $provider_id ORDER BY created_at DESC");
$drivers = $conn->query("SELECT d.*, a.vehicle_number FROM drivers d LEFT JOIN ambulances a ON d.ambulance_id = a.id WHERE d.provider_id = $provider_id ORDER BY d.created_at DESC");
$bookings = $conn->query("SELECT b.*, u.fullname as user_name, a.vehicle_number FROM bookings b JOIN users u ON b.user_id = u.id LEFT JOIN ambulances a ON b.ambulance_id = a.id WHERE b.ambulance_id IN (SELECT id FROM ambulances WHERE provider_id = $provider_id) ORDER BY b.created_at DESC");
$available_ambulances = $conn->query("SELECT * FROM ambulances WHERE provider_id = $provider_id AND status = 'available'");
$pending_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'");

// Set variables for shared components
$dashboard_type = 'Provider';
$current_page = 'overview';

// Include header
include '../includes/header.php';
include '../includes/sidebar.php';
?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Overview Section -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard Overview</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-100 text-red-600">
                                <i class="fas fa-ambulance text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Ambulances</p>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $ambulances->num_rows; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-user-tie text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Drivers</p>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $drivers->num_rows; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-calendar-check text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">My Bookings</p>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $bookings->num_rows; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-clock text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Pending Bookings</p>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $pending_bookings->fetch_assoc()['count']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="ambulances.php" class="block w-full bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 text-center">
                                <i class="fas fa-ambulance mr-2"></i>Manage Ambulances
                            </a>
                            <a href="drivers.php" class="block w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 text-center">
                                <i class="fas fa-user-tie mr-2"></i>Manage Drivers
                            </a>
                            <a href="my-bookings.php" class="block w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 text-center">
                                <i class="fas fa-calendar-check mr-2"></i>View My Bookings
                            </a>
                            <a href="all-bookings.php" class="block w-full bg-purple-600 text-white px-4 py-3 rounded-lg hover:bg-purple-700 text-center">
                                <i class="fas fa-list mr-2"></i>View All Bookings
                            </a>
                        </div>
                    </div>
                    
                <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                        <div class="space-y-3">
                            <?php
                            $recent_bookings = $conn->query("SELECT b.*, u.fullname as user_name FROM bookings b JOIN users u ON b.user_id = u.id WHERE b.ambulance_id IN (SELECT id FROM ambulances WHERE provider_id = $provider_id) ORDER BY b.created_at DESC LIMIT 3");
                            if ($recent_bookings->num_rows > 0):
                                while ($booking = $recent_bookings->fetch_assoc()):
                            ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900"><?php echo htmlspecialchars($booking['user_name']); ?></p>
                                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($booking['pickup_location']); ?> → <?php echo htmlspecialchars($booking['destination']); ?></p>
                    </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    <?php 
                                    switch($booking['status']) {
                                        case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                        case 'accepted': echo 'bg-blue-100 text-blue-800'; break;
                                        case 'in_progress': echo 'bg-purple-100 text-purple-800'; break;
                                        case 'completed': echo 'bg-green-100 text-green-800'; break;
                                        case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                                    }
                                    ?>">
                                    <?php echo htmlspecialchars($booking['status']); ?>
                                </span>
                </div>
                            <?php 
                                endwhile;
                            else:
                            ?>
                            <p class="text-gray-500 text-center py-4">No recent bookings</p>
                                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>

<?php include '../includes/footer.php'; ?> 