<?php
require_once '../session_config.php';
include '../config.php';

// Check if user is logged in and is regular user
if (!isset($_SESSION['user_id']) || $_SESSION['account_type'] !== 'user') {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch payment history (bookings with amounts)
$sql = "SELECT b.*, a.vehicle_number, p.fullname as provider_name FROM bookings b 
        LEFT JOIN ambulances a ON b.ambulance_id = a.id 
        LEFT JOIN users p ON a.provider_id = p.id 
        WHERE b.user_id = ? AND b.amount > 0 
        ORDER BY b.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$payments = $stmt->get_result();

// Set variables for shared components
$dashboard_type = 'User';
$current_page = 'payment-history';

// Include header
include '../includes/header.php';
include '../includes/sidebar.php';
?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Payment History Section -->
            <section class="mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Payment History & Receipts</h2>
                        <div class="text-sm text-gray-600">
                            Total Payments: <?php echo $payments->num_rows; ?>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($payments->num_rows > 0): ?>
                                    <?php while ($payment = $payments->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #<?php echo str_pad($payment['id'], 6, '0', STR_PAD_LEFT); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('M d, Y H:i', strtotime($payment['created_at'])); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>
                                                <p class="font-medium"><?php echo htmlspecialchars($payment['pickup_location']); ?> → <?php echo htmlspecialchars($payment['destination']); ?></p>
                                                <p class="text-xs text-gray-400"><?php echo ucfirst($payment['booking_type']); ?></p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo htmlspecialchars($payment['provider_name'] ?? 'Not assigned'); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            KSh <?php echo number_format($payment['amount'], 2); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                <?php 
                                                switch($payment['status']) {
                                                    case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                                    case 'accepted': echo 'bg-blue-100 text-blue-800'; break;
                                                    case 'in_progress': echo 'bg-purple-100 text-purple-800'; break;
                                                    case 'completed': echo 'bg-green-100 text-green-800'; break;
                                                    case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                                                }
                                                ?>">
                                                <?php echo htmlspecialchars($payment['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <button onclick="viewReceipt(<?php echo $payment['id']; ?>)" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-receipt mr-1"></i>View Receipt
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No payment history found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>

    <!-- Receipt Modal -->
    <div id="receiptModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Receipt</h3>
                <button onclick="hideReceipt()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="receiptContent">
                <!-- Receipt content will be loaded here -->
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="printReceipt()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 mr-2">
                    <i class="fas fa-print mr-1"></i>Print
                </button>
                <button onclick="hideReceipt()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function viewReceipt(bookingId) {
            // In a real application, you would fetch receipt data from the server
            const receiptContent = `
                <div class="text-center mb-4">
                    <h4 class="text-xl font-bold text-gray-900">QuickMed Ambulance Service</h4>
                    <p class="text-sm text-gray-600">Receipt</p>
                </div>
                <div class="border-t border-b py-4 mb-4">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Booking ID:</span>
                        <span>#${String(bookingId).padStart(6, '0')}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Date:</span>
                        <span>${new Date().toLocaleDateString()}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Service:</span>
                        <span>Ambulance Transport</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Amount:</span>
                        <span class="font-bold">KSh 5,000.00</span>
                    </div>
                </div>
                <div class="text-center text-sm text-gray-600">
                    <p>Thank you for choosing QuickMed!</p>
                    <p>For support, contact: support@quickmed.com</p>
                </div>
            `;
            
            document.getElementById('receiptContent').innerHTML = receiptContent;
            document.getElementById('receiptModal').classList.remove('hidden');
            document.getElementById('receiptModal').classList.add('flex');
        }

        function hideReceipt() {
            document.getElementById('receiptModal').classList.add('hidden');
            document.getElementById('receiptModal').classList.remove('flex');
        }

        function printReceipt() {
            const printWindow = window.open('', '_blank');
            const receiptContent = document.getElementById('receiptContent').innerHTML;
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Receipt</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 20px; }
                            .receipt { max-width: 400px; margin: 0 auto; }
                            .header { text-align: center; margin-bottom: 20px; }
                            .details { border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding: 15px 0; margin: 15px 0; }
                            .row { display: flex; justify-content: space-between; margin-bottom: 8px; }
                            .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
                        </style>
                    </head>
                    <body>
                        <div class="receipt">
                            ${receiptContent}
                        </div>
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }
    </script>

<?php include '../includes/footer.php'; ?> 