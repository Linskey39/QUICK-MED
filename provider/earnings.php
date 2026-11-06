<?php
require_once '../session_config.php';
include '../config.php';

// Check if user is logged in and is provider
if (!isset($_SESSION['user_id']) || $_SESSION['account_type'] !== 'provider') {
    header('Location: ../login.php');
    exit();
}

$provider_id = $_SESSION['user_id'];

// Fetch earnings data
$sql = "SELECT 
            SUM(CASE WHEN b.status = 'completed' THEN b.amount ELSE 0 END) as total_earnings,
            COUNT(CASE WHEN b.status = 'completed' THEN 1 END) as completed_bookings,
            COUNT(CASE WHEN b.status = 'pending' THEN 1 END) as pending_bookings,
            COUNT(CASE WHEN b.status = 'in_progress' THEN 1 END) as active_bookings,
            COUNT(*) as total_bookings
        FROM bookings b 
        JOIN ambulances a ON b.ambulance_id = a.id 
        WHERE a.provider_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $provider_id);
$stmt->execute();
$earnings_summary = $stmt->get_result()->fetch_assoc();

// Fetch monthly earnings for chart
$sql = "SELECT 
            DATE_FORMAT(b.created_at, '%Y-%m') as month,
            SUM(CASE WHEN b.status = 'completed' THEN b.amount ELSE 0 END) as earnings,
            COUNT(CASE WHEN b.status = 'completed' THEN 1 END) as bookings
        FROM bookings b 
        JOIN ambulances a ON b.ambulance_id = a.id 
        WHERE a.provider_id = ? 
        GROUP BY DATE_FORMAT(b.created_at, '%Y-%m')
        ORDER BY month DESC 
        LIMIT 12";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $provider_id);
$stmt->execute();
$monthly_earnings = $stmt->get_result();

// Fetch recent completed bookings
$sql = "SELECT b.*, u.fullname as user_name, a.vehicle_number 
        FROM bookings b 
        JOIN ambulances a ON b.ambulance_id = a.id 
        JOIN users u ON b.user_id = u.id 
        WHERE a.provider_id = ? AND b.status = 'completed' 
        ORDER BY b.created_at DESC 
        LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $provider_id);
$stmt->execute();
$recent_earnings = $stmt->get_result();

// Set variables for shared components
$dashboard_type = 'Provider';
$current_page = 'earnings';

// Include header
include '../includes/header.php';
include '../includes/sidebar.php';
?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Earnings Overview Section -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Earnings & Revenue</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-money-bill-wave text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Earnings</p>
                                <p class="text-2xl font-semibold text-gray-900">KSh <?php echo number_format($earnings_summary['total_earnings'] ?? 0, 2); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-check-circle text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Completed Bookings</p>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $earnings_summary['completed_bookings'] ?? 0; ?></p>
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
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $earnings_summary['pending_bookings'] ?? 0; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-route text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Active Bookings</p>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $earnings_summary['active_bookings'] ?? 0; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Earnings Chart -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Earnings</h3>
                    <div class="h-64">
                        <canvas id="earningsChart"></canvas>
                    </div>
                </div>

                <!-- Recent Earnings -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Completed Bookings</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ambulance</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($recent_earnings->num_rows > 0): ?>
                                    <?php while ($earning = $recent_earnings->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #<?php echo str_pad($earning['id'], 6, '0', STR_PAD_LEFT); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo htmlspecialchars($earning['user_name']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>
                                                <p class="font-medium"><?php echo htmlspecialchars($earning['pickup_location']); ?> → <?php echo htmlspecialchars($earning['destination']); ?></p>
                                                <p class="text-xs text-gray-400"><?php echo ucfirst($earning['booking_type']); ?></p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo htmlspecialchars($earning['vehicle_number']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                            KSh <?php echo number_format($earning['amount'], 2); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('M d, Y', strtotime($earning['created_at'])); ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No completed bookings yet</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Prepare data for chart
        const monthlyData = <?php 
            $chart_data = [];
            while ($row = $monthly_earnings->fetch_assoc()) {
                $chart_data[] = [
                    'month' => date('M Y', strtotime($row['month'] . '-01')),
                    'earnings' => $row['earnings']
                ];
            }
            echo json_encode(array_reverse($chart_data));
        ?>;

        // Create earnings chart
        const ctx = document.getElementById('earningsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyData.map(item => item.month),
                datasets: [{
                    label: 'Monthly Earnings (KSh)',
                    data: monthlyData.map(item => item.earnings),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'KSh ' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>

<?php include '../includes/footer.php'; ?> 