<?php
require_once '../session_config.php';
include '../config.php';

// Check if user is logged in and is regular user
if (!isset($_SESSION['user_id']) || $_SESSION['account_type'] !== 'user') {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'book_ambulance') {
        $pickup_location = $_POST['pickup_location'];
        $destination = $_POST['destination'];
        $booking_type = $_POST['booking_type'];
        $amount = $_POST['booking_type'] === 'emergency' ? 5000.00 : 3000.00;
        
        $sql = "INSERT INTO bookings (user_id, pickup_location, destination, booking_type, amount) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssd", $user_id, $pickup_location, $destination, $booking_type, $amount);
        
        if ($stmt->execute()) {
            $success_message = "Ambulance booked successfully!";
        } else {
            $error_message = "Error booking ambulance. Please try again.";
        }
    }
}

// Set variables for shared components
$dashboard_type = 'User';
$current_page = 'book-ambulance';

// Include header
include '../includes/header.php';
include '../includes/sidebar.php';
?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Book Ambulance Section -->
            <section class="mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Book Ambulance</h2>
                        <button onclick="showBookAmbulanceModal()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                            <i class="fas fa-plus mr-2"></i>Book Now
                        </button>
                    </div>
                    
                    <?php if (isset($success_message)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $success_message; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo $error_message; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-red-50 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-exclamation-triangle text-red-600 text-2xl mr-3"></i>
                                <h3 class="text-lg font-semibold text-red-800">Emergency Booking</h3>
                            </div>
                            <p class="text-red-700 mb-4">For urgent medical emergencies requiring immediate attention.</p>
                            <ul class="text-sm text-red-600 space-y-1">
                                <li>• 24/7 availability</li>
                                <li>• Priority response</li>
                                <li>• Advanced life support</li>
                                <li>• KSh 5,000</li>
                            </ul>
                        </div>
                        
                        <div class="bg-blue-50 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-calendar-alt text-blue-600 text-2xl mr-3"></i>
                                <h3 class="text-lg font-semibold text-blue-800">Non-Emergency Booking</h3>
                            </div>
                            <p class="text-blue-700 mb-4">For scheduled medical transport and non-urgent situations.</p>
                            <ul class="text-sm text-blue-600 space-y-1">
                                <li>• Scheduled appointments</li>
                                <li>• Regular transport</li>
                                <li>• Basic medical support</li>
                                <li>• KSh 3,000</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </main>

    <!-- Book Ambulance Modal -->
    <div id="bookAmbulanceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Book Ambulance</h3>
            <form method="POST">
                <input type="hidden" name="action" value="book_ambulance">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Booking Type</label>
                    <select name="booking_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="emergency">Emergency</option>
                        <option value="non-emergency">Non-Emergency</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Location</label>
                    <input type="text" name="pickup_location" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter pickup address">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Destination</label>
                    <input type="text" name="destination" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter destination">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideBookAmbulanceModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Book Ambulance</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showBookAmbulanceModal() {
            document.getElementById('bookAmbulanceModal').classList.remove('hidden');
            document.getElementById('bookAmbulanceModal').classList.add('flex');
        }

        function hideBookAmbulanceModal() {
            document.getElementById('bookAmbulanceModal').classList.add('hidden');
            document.getElementById('bookAmbulanceModal').classList.remove('flex');
        }
    </script>

<?php include '../includes/footer.php'; ?> 