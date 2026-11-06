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
        if ($_POST['action'] === 'add_service_area') {
            $area_name = $_POST['area_name'];
            $description = $_POST['description'];
            $radius_km = $_POST['radius_km'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            $sql = "INSERT INTO service_areas (provider_id, area_name, description, radius_km, is_active) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issdi", $provider_id, $area_name, $description, $radius_km, $is_active);
            
            if ($stmt->execute()) {
                $success_message = "Service area added successfully!";
            } else {
                $error_message = "Error adding service area. Please try again.";
            }
        } elseif ($_POST['action'] === 'update_service_area') {
            $area_id = $_POST['area_id'];
            $area_name = $_POST['area_name'];
            $description = $_POST['description'];
            $radius_km = $_POST['radius_km'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            $sql = "UPDATE service_areas SET area_name = ?, description = ?, radius_km = ?, is_active = ? WHERE id = ? AND provider_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdiii", $area_name, $description, $radius_km, $is_active, $area_id, $provider_id);
            
            if ($stmt->execute()) {
                $success_message = "Service area updated successfully!";
            } else {
                $error_message = "Error updating service area. Please try again.";
            }
        } elseif ($_POST['action'] === 'delete_service_area') {
            $area_id = $_POST['area_id'];
            
            $sql = "DELETE FROM service_areas WHERE id = ? AND provider_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $area_id, $provider_id);
            
            if ($stmt->execute()) {
                $success_message = "Service area deleted successfully!";
            } else {
                $error_message = "Error deleting service area. Please try again.";
            }
        }
    }
}

// Fetch service areas
$sql = "SELECT * FROM service_areas WHERE provider_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $provider_id);
$stmt->execute();
$service_areas = $stmt->get_result();

// Set variables for shared components
$dashboard_type = 'Provider';
$current_page = 'service-areas';

// Include header
include '../includes/header.php';
include '../includes/sidebar.php';
?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Service Areas Section -->
            <section class="mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Service Areas</h2>
                        <button onclick="showAddAreaModal()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                            <i class="fas fa-plus mr-2"></i>Add Service Area
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php if ($service_areas->num_rows > 0): ?>
                            <?php while ($area = $service_areas->fetch_assoc()): ?>
                            <div class="bg-gray-50 rounded-lg p-4 border">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marked-alt text-red-600 mr-2"></i>
                                        <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($area['area_name']); ?></h3>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        <?php echo $area['is_active'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                        <?php echo $area['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                <p class="text-gray-600 text-sm mb-3"><?php echo htmlspecialchars($area['description']); ?></p>
                                <p class="text-gray-500 text-xs mb-3">
                                    <i class="fas fa-circle mr-1"></i>
                                    Coverage: <?php echo $area['radius_km']; ?> km radius
                                </p>
                                <div class="flex space-x-2">
                                    <button onclick="editArea(<?php echo htmlspecialchars(json_encode($area)); ?>)" 
                                            class="text-blue-600 hover:text-blue-900 text-sm">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>
                                    <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this service area?')">
                                        <input type="hidden" name="action" value="delete_service_area">
                                        <input type="hidden" name="area_id" value="<?php echo $area['id']; ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-span-full text-center py-8">
                                <i class="fas fa-map-marked-alt text-gray-400 text-4xl mb-4"></i>
                                <p class="text-gray-500">No service areas defined</p>
                                <p class="text-sm text-gray-400">Add service areas to define your coverage zones</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </main>

    <!-- Add/Edit Service Area Modal -->
    <div id="serviceAreaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold mb-4" id="modalTitle">Add Service Area</h3>
            <form method="POST" id="serviceAreaForm">
                <input type="hidden" name="action" id="formAction" value="add_service_area">
                <input type="hidden" name="area_id" id="areaId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Area Name</label>
                    <input type="text" name="area_name" id="areaName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="e.g., Nairobi CBD, Westlands">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="areaDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Describe the service area"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Coverage Radius (km)</label>
                    <input type="number" name="radius_km" id="radiusKm" required min="1" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="10">
                </div>
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" id="isActive" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-700">Active Service Area</span>
                    </label>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideServiceAreaModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Save Area</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showAddAreaModal() {
            document.getElementById('modalTitle').textContent = 'Add Service Area';
            document.getElementById('formAction').value = 'add_service_area';
            document.getElementById('areaId').value = '';
            document.getElementById('areaName').value = '';
            document.getElementById('areaDescription').value = '';
            document.getElementById('radiusKm').value = '';
            document.getElementById('isActive').checked = true;
            
            document.getElementById('serviceAreaModal').classList.remove('hidden');
            document.getElementById('serviceAreaModal').classList.add('flex');
        }

        function editArea(area) {
            document.getElementById('modalTitle').textContent = 'Edit Service Area';
            document.getElementById('formAction').value = 'update_service_area';
            document.getElementById('areaId').value = area.id;
            document.getElementById('areaName').value = area.area_name;
            document.getElementById('areaDescription').value = area.description;
            document.getElementById('radiusKm').value = area.radius_km;
            document.getElementById('isActive').checked = area.is_active == 1;
            
            document.getElementById('serviceAreaModal').classList.remove('hidden');
            document.getElementById('serviceAreaModal').classList.add('flex');
        }

        function hideServiceAreaModal() {
            document.getElementById('serviceAreaModal').classList.add('hidden');
            document.getElementById('serviceAreaModal').classList.remove('flex');
        }
    </script>

<?php include '../includes/footer.php'; ?> 