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
    if (isset($_POST['action']) && $_POST['action'] === 'cancel_booking') {
        $booking_id = $_POST['booking_id'];
        $sql = "UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $booking_id, $user_id);
        
        if ($stmt->execute()) {
            $success_message = "Booking cancelled successfully!";
        } else {
            $error_message = "Error cancelling booking. Please try again.";
        }
    }
}

// Fetch bookings data
$bookings = $conn->query("SELECT b.*, a.vehicle_number, p.fullname as provider_name FROM bookings b LEFT JOIN ambulances a ON b.ambulance_id = a.id LEFT JOIN users p ON a.provider_id = p.id WHERE b.user_id = $user_id ORDER BY b.created_at DESC");

// Set variables for shared components
$dashboard_type = 'User';
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
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">My Bookings</h2>
                    
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pickup</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ambulance</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($bookings->num_rows > 0): ?>
                                    <?php while ($booking = $bookings->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($booking['pickup_location']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($booking['destination']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $booking['booking_type'] === 'emergency' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'; ?>">
                                                <?php echo htmlspecialchars($booking['booking_type']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
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
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($booking['provider_name'] ?? 'Not assigned'); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($booking['vehicle_number'] ?? 'Not assigned'); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">KSh <?php echo number_format($booking['amount'], 2); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo date('M d, Y', strtotime($booking['created_at'])); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <?php if ($booking['status'] === 'pending'): ?>
                                            <button onclick="cancelBooking(<?php echo $booking['id']; ?>)" class="text-red-600 hover:text-red-900">Cancel</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">No bookings found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>

    <script>
        function cancelBooking(bookingId) {
            if (confirm('Are you sure you want to cancel this booking?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="cancel_booking">
                    <input type="hidden" name="booking_id" value="${bookingId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

<?php include '../includes/footer.php'; ?> 