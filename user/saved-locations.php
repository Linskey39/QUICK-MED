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
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add_location') {
            $name = $_POST['name'];
            $address = $_POST['address'];
            $location_type = $_POST['location_type'];
            
            $sql = "INSERT INTO saved_locations (user_id, name, address, location_type) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $user_id, $name, $address, $location_type);
            
            if ($stmt->execute()) {
                $success_message = "Location saved successfully!";
            } else {
                $error_message = "Error saving location. Please try again.";
            }
        } elseif ($_POST['action'] === 'delete_location') {
            $location_id = $_POST['location_id'];
            
            $sql = "DELETE FROM saved_locations WHERE id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $location_id, $user_id);
            
            if ($stmt->execute()) {
                $success_message = "Location deleted successfully!";
            } else {
                $error_message = "Error deleting location. Please try again.";
            }
        }
    }
}

// Fetch saved locations
$sql = "SELECT * FROM saved_locations WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$saved_locations = $stmt->get_result();

// Set variables for shared components
$dashboard_type = 'User';
$current_page = 'saved-locations';

// Include header
include '../includes/header.php';
include '../includes/sidebar.php';
?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Saved Locations Section -->
            <section class="mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Saved Locations</h2>
                        <button onclick="showAddLocationModal()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                            <i class="fas fa-plus mr-2"></i>Add Location
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
                        <?php if ($saved_locations->num_rows > 0): ?>
                            <?php while ($location = $saved_locations->fetch_assoc()): ?>
                            <div class="bg-gray-50 rounded-lg p-4 border">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                                        <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($location['name']); ?></h3>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        <?php echo $location['location_type'] === 'pickup' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'; ?>">
                                        <?php echo ucfirst($location['location_type']); ?>
                                    </span>
                                </div>
                                <p class="text-gray-600 text-sm mb-3"><?php echo htmlspecialchars($location['address']); ?></p>
                                <div class="flex space-x-2">
                                    <button onclick="useLocation('<?php echo htmlspecialchars($location['address']); ?>', '<?php echo $location['location_type']; ?>')" 
                                            class="text-blue-600 hover:text-blue-900 text-sm">
                                        <i class="fas fa-arrow-right mr-1"></i>Use for Booking
                                    </button>
                                    <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this location?')">
                                        <input type="hidden" name="action" value="delete_location">
                                        <input type="hidden" name="location_id" value="<?php echo $location['id']; ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-span-full text-center py-8">
                                <i class="fas fa-map-marker-alt text-gray-400 text-4xl mb-4"></i>
                                <p class="text-gray-500">No saved locations yet</p>
                                <p class="text-sm text-gray-400">Add your frequently used locations for quick booking</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </main>

    <!-- Add Location Modal -->
    <div id="addLocationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Add Saved Location</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add_location">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="e.g., Home, Work, Hospital">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea name="address" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter full address"></textarea>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location Type</label>
                    <select name="location_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="pickup">Pickup Location</option>
                        <option value="destination">Destination</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideAddLocationModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Save Location</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showAddLocationModal() {
            document.getElementById('addLocationModal').classList.remove('hidden');
            document.getElementById('addLocationModal').classList.add('flex');
        }

        function hideAddLocationModal() {
            document.getElementById('addLocationModal').classList.add('hidden');
            document.getElementById('addLocationModal').classList.remove('flex');
        }

        function useLocation(address, type) {
            // Store the location in sessionStorage for use in booking
            sessionStorage.setItem('selectedLocation', JSON.stringify({
                address: address,
                type: type
            }));
            
            // Redirect to booking page
            window.location.href = 'book-ambulance.php';
        }
    </script>

<?php include '../includes/footer.php'; ?> 