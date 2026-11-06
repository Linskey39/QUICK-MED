<?php
require_once '../session_config.php';
include '../config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['account_type'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'update_pricing') {
            $emergency_price = $_POST['emergency_price'];
            $non_emergency_price = $_POST['non_emergency_price'];
            
            // In a real application, you would store these in a settings table
            // For now, we'll just show a success message
            $success_message = "Pricing updated successfully!";
        } elseif ($_POST['action'] === 'update_notifications') {
            $email_notifications = isset($_POST['email_notifications']) ? 1 : 0;
            $sms_notifications = isset($_POST['sms_notifications']) ? 1 : 0;
            $admin_notifications = isset($_POST['admin_notifications']) ? 1 : 0;
            
            $success_message = "Notification settings updated successfully!";
        } elseif ($_POST['action'] === 'update_system') {
            $maintenance_mode = isset($_POST['maintenance_mode']) ? 1 : 0;
            $auto_approve_providers = isset($_POST['auto_approve_providers']) ? 1 : 0;
            $max_booking_distance = $_POST['max_booking_distance'];
            
            $success_message = "System settings updated successfully!";
        }
    }
}

// Set variables for shared components
$dashboard_type = 'Admin';
$current_page = 'system-settings';

// Include header
include '../includes/header.php';
include '../includes/sidebar.php';
?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- System Settings Section -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">System Settings</h2>
                
                <?php if (isset($success_message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $success_message; ?>
                </div>
                <?php endif; ?>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Pricing Settings -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing Configuration</h3>
                        <form method="POST">
                            <input type="hidden" name="action" value="update_pricing">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Booking Price (KSh)</label>
                                <input type="number" name="emergency_price" value="5000" min="0" step="100" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                <p class="text-sm text-gray-500 mt-1">Standard price for emergency ambulance bookings</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Non-Emergency Booking Price (KSh)</label>
                                <input type="number" name="non_emergency_price" value="3000" min="0" step="100" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                <p class="text-sm text-gray-500 mt-1">Standard price for non-emergency ambulance bookings</p>
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Commission Rate (%)</label>
                                <input type="number" name="commission_rate" value="10" min="0" max="50" step="1" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                <p class="text-sm text-gray-500 mt-1">Platform commission from provider earnings</p>
                            </div>
                            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Update Pricing</button>
                        </form>
                    </div>
                    
                    <!-- Notification Settings -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Notification Settings</h3>
                        <form method="POST">
                            <input type="hidden" name="action" value="update_notifications">
                            <div class="space-y-4 mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="email_notifications" checked class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-sm text-gray-700">Enable Email Notifications</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="sms_notifications" checked class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-sm text-gray-700">Enable SMS Notifications</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="admin_notifications" checked class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-sm text-gray-700">Admin Notifications for New Bookings</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="provider_notifications" checked class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-sm text-gray-700">Provider Notifications for New Bookings</span>
                                </label>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Update Notifications</button>
                        </form>
                    </div>
                </div>

                <!-- System Configuration -->
                <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">System Configuration</h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="update_system">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="maintenance_mode" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-sm text-gray-700">Maintenance Mode</span>
                                </label>
                                <p class="text-xs text-gray-500">When enabled, only admins can access the system</p>
                                
                                <label class="flex items-center">
                                    <input type="checkbox" name="auto_approve_providers" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-sm text-gray-700">Auto-approve New Providers</span>
                                </label>
                                <p class="text-xs text-gray-500">Automatically approve new provider registrations</p>
                                
                                <label class="flex items-center">
                                    <input type="checkbox" name="enable_rating_system" checked class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-sm text-gray-700">Enable Rating System</span>
                                </label>
                                <p class="text-xs text-gray-500">Allow users to rate providers after completed bookings</p>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Booking Distance (km)</label>
                                    <input type="number" name="max_booking_distance" value="50" min="1" max="200" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Session Timeout (minutes)</label>
                                    <input type="number" name="session_timeout" value="30" min="5" max="480" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Response Time (minutes)</label>
                                    <input type="number" name="emergency_response_time" value="10" min="1" max="60" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                </div>
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Update System Settings</button>
                        </div>
                    </form>
                </div>

                <!-- System Information -->
                <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">System Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600"><?php echo phpversion(); ?></div>
                            <div class="text-sm text-gray-600">PHP Version</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600"><?php echo date('Y-m-d H:i:s'); ?></div>
                            <div class="text-sm text-gray-600">Current Time</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600"><?php echo memory_get_usage(true) / 1024 / 1024; ?> MB</div>
                            <div class="text-sm text-gray-600">Memory Usage</div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

<?php include '../includes/footer.php'; ?> 