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
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_driver':
                $fullname = $_POST['fullname'];
                $phone = $_POST['phone'];
                $license_number = $_POST['license_number'];
                $ambulance_id = $_POST['ambulance_id'] ?: null;

                $sql = "INSERT INTO drivers (provider_id, ambulance_id, fullname, phone, license_number) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iisss", $provider_id, $ambulance_id, $fullname, $phone, $license_number);

                if ($stmt->execute()) {
                    $success_message = "Driver added successfully!";
                } else {
                    $error_message = "Error adding driver. Please try again.";
                }
                break;

            case 'delete_driver':
                $driver_id = $_POST['driver_id'];
                $sql = "DELETE FROM drivers WHERE id = ? AND provider_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $driver_id, $provider_id);

                if ($stmt->execute()) {
                    $success_message = "Driver deleted successfully!";
                } else {
                    $error_message = "Error deleting driver. Please try again.";
                }
                break;
        }
    }
}

// Fetch drivers data
$drivers = $conn->query("SELECT d.*, a.vehicle_number FROM drivers d LEFT JOIN ambulances a ON d.ambulance_id = a.id WHERE d.provider_id = $provider_id ORDER BY d.created_at DESC");
$available_ambulances = $conn->query("SELECT * FROM ambulances WHERE provider_id = $provider_id AND status = 'available'");

// Set variables for shared components
$dashboard_type = 'Provider';
$current_page = 'drivers';

// Include header
include '../includes/header.php';
include '../includes/sidebar.php';
?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Drivers Section -->
            <section class="mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">My Drivers</h2>
                        <button onclick="showAddDriverModal()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                            <i class="fas fa-plus mr-2"></i>Add Driver
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

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">License</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Ambulance</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($drivers->num_rows > 0): ?>
                                    <?php while ($driver = $drivers->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($driver['fullname']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($driver['phone']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($driver['license_number']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($driver['vehicle_number'] ?? 'Not assigned'); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="deleteDriver(<?php echo $driver['id']; ?>)" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No drivers found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>

    <!-- Add Driver Modal -->
    <div id="addDriverModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Add New Driver</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add_driver">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="fullname" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" name="phone" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">License Number</label>
                    <input type="text" name="license_number" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assign Ambulance (optional)</label>
                    <select name="ambulance_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">Not assigned</option>
                        <?php while ($ambulance = $available_ambulances->fetch_assoc()): ?>
                        <option value="<?php echo $ambulance['id']; ?>"><?php echo htmlspecialchars($ambulance['vehicle_number']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideAddDriverModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Add Driver</button>
                    
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white roundes-lg hover:bg-red-700"> Driver</button>
                    </div>


                        </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showAddDriverModal() {
            document.getElementById('addDriverModal').classList.remove('hidden');
            document.getElementById('addDriverModal').classList.add('flex');
        }

        function hideAddDriverModal() {
            document.getElementById('addDriverModal').classList.add('hidden');
            document.getElementById('addDriverModal').classList.remove('flex');
        }

        function deleteDriver(driverId) {
            if (confirm('Are you sure you want to delete this driver?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_driver">
                    <input type="hidden" name="driver_id" value="${driverId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

<?php include '../includes/footer.php'; ?>
