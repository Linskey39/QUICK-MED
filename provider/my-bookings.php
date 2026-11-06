<?php
require_once '../session_config.php';
include '../config.php';

// Check if user is logged in and is provider
if (!isset($_SESSION['user_id']) || $_SESSION['account_type'] !== 'provider') {
    header('Location: ../login.php');
    exit();
}

$provider_id = $_SESSION['user_id'];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update_booking_status') {
        $booking_id = $_POST['booking_id'];
        $status = $_POST['status'];
        $ambulance_id = $_POST['ambulance_id'] ?: null;
        
        $sql = "UPDATE bookings SET status = ?, ambulance_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $status, $ambulance_id, $booking_id);
        
        if ($stmt->execute()) {
            $success_message = "Booking status updated successfully!";
        } else {
            $error_message = "Error updating booking status. Please try again.";
        }
    }
}

// Fetch bookings data
$bookings = $conn->query("SELECT b.*, u.fullname as user_name, a.vehicle_number FROM bookings b JOIN users u ON b.user_id = u.id LEFT JOIN ambulances a ON b.ambulance_id = a.id WHERE b.ambulance_id IN (SELECT id FROM ambulances WHERE provider_id = $provider_id) ORDER BY b.created_at DESC");
$available_ambulances = $conn->query("SELECT * FROM ambulances WHERE provider_id = $provider_id AND status = 'available'");

// Set variables for shared components
$dashboard_type = 'Provider';
$current_page = 'my-bookings';

// Include header
include '../includes/header.php';
include '../includes/sidebar.php';
?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- My Bookings Section -->
            <section class="mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">My Bookings (Assigned to My Ambulances)</h2>
                    
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
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pickup</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">My Ambulance</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($bookings->num_rows > 0): ?>
                                    <?php while ($booking = $bookings->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($booking['user_name']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($booking['pickup_location']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($booking['destination']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($booking['booking_type']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($booking['status']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($booking['vehicle_number'] ?? 'Not assigned'); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">KSh <?php echo number_format($booking['amount'], 2); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="showUpdateBookingModal(<?php echo $booking['id']; ?>, '<?php echo $booking['status']; ?>')" class="text-blue-600 hover:text-blue-900">Update Status</button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No bookings found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>

    <!-- Update Booking Modal -->
    <div id="updateBookingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Update Booking Status</h3>
            <form method="POST">
                <input type="hidden" name="action" value="update_booking_status">
                <input type="hidden" name="booking_id" id="booking_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="booking_status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="pending">Pending</option>
                        <option value="accepted">Accepted</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assign Ambulance</label>
                    <select name="ambulance_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">Select ambulance</option>
                        <?php $available_ambulances->data_seek(0); while ($ambulance = $available_ambulances->fetch_assoc()): ?>
                        <option value="<?php echo $ambulance['id']; ?>"><?php echo htmlspecialchars($ambulance['vehicle_number']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideUpdateBookingModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showUpdateBookingModal(bookingId, currentStatus) {
            document.getElementById('booking_id').value = bookingId;
            document.getElementById('booking_status').value = currentStatus;
            document.getElementById('updateBookingModal').classList.remove('hidden');
            document.getElementById('updateBookingModal').classList.add('flex');
        }

        function hideUpdateBookingModal() {
            document.getElementById('updateBookingModal').classList.add('hidden');
            document.getElementById('updateBookingModal').classList.remove('flex');
        }
    </script>

<?php include '../includes/footer.php'; ?> 