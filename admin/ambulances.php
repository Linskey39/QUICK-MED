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
        switch ($_POST['action']) {
            case 'add_ambulance':
                $provider_id = $_POST['provider_id'];
                $vehicle_number = $_POST['vehicle_number'];
                $vehicle_type = $_POST['vehicle_type'];
                
                $sql = "INSERT INTO ambulances (provider_id, vehicle_number, vehicle_type) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iss", $provider_id, $vehicle_number, $vehicle_type);
                
                if ($stmt->execute()) {
                    $success_message = "Ambulance added successfully!";
                } else {
                    $error_message = "Error adding ambulance. Please try again.";
                }
                break;
                
            case 'delete_ambulance':
                $ambulance_id = $_POST['ambulance_id'];
                $sql = "DELETE FROM ambulances WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $ambulance_id);
                
                if ($stmt->execute()) {
                    $success_message = "Ambulance deleted successfully!";
                } else {
                    $error_message = "Error deleting ambulance. Please try again.";
                }
                break;
        }
    }
}

// Fetch ambulances data
$ambulances = $conn->query("SELECT a.*, u.fullname as provider_name FROM ambulances a JOIN users u ON a.provider_id = u.id ORDER BY a.created_at DESC");
$providers = $conn->query("SELECT * FROM users WHERE account_type = 'provider'");

// Set variables for shared components
$dashboard_type = 'Admin';
$current_page = 'ambulances';

// Include header
include '../includes/header.php';
include '../includes/sidebar.php';
?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Ambulances Section -->
            <section class="mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Ambulances</h2>
                        <button onclick="showAddAmbulanceModal()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                            <i class="fas fa-plus mr-2"></i>Add Ambulance
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($ambulances->num_rows > 0): ?>
                                    <?php while ($ambulance = $ambulances->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($ambulance['vehicle_number']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($ambulance['vehicle_type']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($ambulance['provider_name']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($ambulance['status']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="deleteAmbulance(<?php echo $ambulance['id']; ?>)" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No ambulances found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>

    <!-- Add Ambulance Modal -->
    <div id="addAmbulanceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Add New Ambulance</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add_ambulance">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Provider</label>
                    <select name="provider_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        <?php while ($provider = $providers->fetch_assoc()): ?>
                        <option value="<?php echo $provider['id']; ?>"><?php echo htmlspecialchars($provider['fullname']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Number</label>
                    <input type="text" name="vehicle_number" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Type</label>
                    <input type="text" name="vehicle_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideAddAmbulanceModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Add Ambulance</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showAddAmbulanceModal() {
            document.getElementById('addAmbulanceModal').classList.remove('hidden');
            document.getElementById('addAmbulanceModal').classList.add('flex');
        }

        function hideAddAmbulanceModal() {
            document.getElementById('addAmbulanceModal').classList.add('hidden');
            document.getElementById('addAmbulanceModal').classList.remove('flex');
        }

        function deleteAmbulance(ambulanceId) {
            if (confirm('Are you sure you want to delete this ambulance?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_ambulance">
                    <input type="hidden" name="ambulance_id" value="${ambulanceId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

<?php include '../includes/footer.php'; ?> 