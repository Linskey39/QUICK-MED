<?php
require_once '../session_config.php';
include '../config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['account_type'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Fetch system statistics
$sql = "SELECT 
            COUNT(*) as total_users,
            COUNT(CASE WHEN account_type = 'user' THEN 1 END) as regular_users,
            COUNT(CASE WHEN account_type = 'provider' THEN 1 END) as providers,
            COUNT(CASE WHEN account_type = 'admin' THEN 1 END) as admins
        FROM users";
$users_stats = $conn->query($sql)->fetch_assoc();

$sql = "SELECT 
            COUNT(*) as total_bookings,
            COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_bookings,
            COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_bookings,
            COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled_bookings,
            SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as total_revenue,
            AVG(CASE WHEN status = 'completed' THEN amount ELSE NULL END) as avg_booking_value
        FROM bookings";
$bookings_stats = $conn->query($sql)->fetch_assoc();

$sql = "SELECT 
            COUNT(*) as total_ambulances,
            COUNT(CASE WHEN status = 'available' THEN 1 END) as available_ambulances,
            COUNT(CASE WHEN status = 'busy' THEN 1 END) as busy_ambulances
        FROM ambulances";
$ambulances_stats = $conn->query($sql)->fetch_assoc();

// Fetch monthly revenue data
$sql = "SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as month,
            COUNT(*) as bookings,
            SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as revenue
        FROM bookings 
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month DESC 
        LIMIT 12";
$monthly_data = $conn->query($sql);

// Fetch top providers by revenue
$sql = "SELECT 
            p.fullname as provider_name,
            COUNT(b.id) as total_bookings,
            SUM(CASE WHEN b.status = 'completed' THEN b.amount ELSE 0 END) as revenue
        FROM users p 
        JOIN ambulances a ON p.id = a.provider_id 
        JOIN bookings b ON a.id = b.ambulance_id 
        WHERE p.account_type = 'provider'
        GROUP BY p.id 
        ORDER BY revenue DESC 
        LIMIT 10";
$top_providers = $conn->query($sql);

// Set variables for shared components
$dashboard_type = 'Admin';
$current_page = 'reports';

// Include header
include '../includes/header.php';
include '../includes/sidebar.php';
?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- System Reports Section -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">System Reports & Analytics</h2>
                
                <!-- Key Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Users</p>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $users_stats['total_users']; ?></p>
                                <p class="text-xs text-gray-500">
                                    <?php echo $users_stats['regular_users']; ?> Regular, 
                                    <?php echo $users_stats['providers']; ?> Providers
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-money-bill-wave text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                                <p class="text-2xl font-semibold text-gray-900">KSh <?php echo number_format($bookings_stats['total_revenue'] ?? 0, 2); ?></p>
                                <p class="text-xs text-gray-500">
                                    <?php echo $bookings_stats['completed_bookings']; ?> completed bookings
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-ambulance text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $bookings_stats['total_bookings']; ?></p>
                                <p class="text-xs text-gray-500">
                                    <?php echo $bookings_stats['pending_bookings']; ?> pending
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-100 text-red-600">
                                <i class="fas fa-car text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Ambulances</p>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $ambulances_stats['total_ambulances']; ?></p>
                                <p class="text-xs text-gray-500">
                                    <?php echo $ambulances_stats['available_ambulances']; ?> available
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Monthly Revenue Chart -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Revenue</h3>
                        <div class="h-64">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Booking Status Chart -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Status Distribution</h3>
                        <div class="h-64">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Providers -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Providers by Revenue</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Bookings</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg per Booking</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($top_providers->num_rows > 0): ?>
                                    <?php while ($provider = $top_providers->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($provider['provider_name']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo $provider['total_bookings']; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                            KSh <?php echo number_format($provider['revenue'], 2); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            KSh <?php echo $provider['total_bookings'] > 0 ? number_format($provider['revenue'] / $provider['total_bookings'], 2) : '0.00'; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No provider data available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detailed Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- User Statistics -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">User Statistics</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Regular Users</span>
                                <span class="text-sm font-medium text-gray-900"><?php echo $users_stats['regular_users']; ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Providers</span>
                                <span class="text-sm font-medium text-gray-900"><?php echo $users_stats['providers']; ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Admins</span>
                                <span class="text-sm font-medium text-gray-900"><?php echo $users_stats['admins']; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Booking Statistics -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Statistics</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Completed</span>
                                <span class="text-sm font-medium text-green-600"><?php echo $bookings_stats['completed_bookings']; ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Pending</span>
                                <span class="text-sm font-medium text-yellow-600"><?php echo $bookings_stats['pending_bookings']; ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Cancelled</span>
                                <span class="text-sm font-medium text-red-600"><?php echo $bookings_stats['cancelled_bookings']; ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Avg Booking Value</span>
                                <span class="text-sm font-medium text-gray-900">KSh <?php echo number_format($bookings_stats['avg_booking_value'] ?? 0, 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Prepare data for charts
        const monthlyData = <?php 
            $chart_data = [];
            while ($row = $monthly_data->fetch_assoc()) {
                $chart_data[] = [
                    'month' => date('M Y', strtotime($row['month'] . '-01')),
                    'revenue' => $row['revenue'],
                    'bookings' => $row['bookings']
                ];
            }
            echo json_encode(array_reverse($chart_data));
        ?>;

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: monthlyData.map(item => item.month),
                datasets: [{
                    label: 'Revenue (KSh)',
                    data: monthlyData.map(item => item.revenue),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
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
                }
            }
        });

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending', 'Cancelled'],
                datasets: [{
                    data: [
                        <?php echo $bookings_stats['completed_bookings']; ?>,
                        <?php echo $bookings_stats['pending_bookings']; ?>,
                        <?php echo $bookings_stats['cancelled_bookings']; ?>
                    ],
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(234, 179, 8)',
                        'rgb(239, 68, 68)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>

<?php include '../includes/footer.php'; ?> 