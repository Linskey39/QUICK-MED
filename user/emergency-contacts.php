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
        switch ($_POST['action']) {
            case 'add_emergency_contact':
                $name = $_POST['name'];
                $phone = $_POST['phone'];
                $relationship = $_POST['relationship'];
                
                $sql = "INSERT INTO emergency_contacts (user_id, name, phone, relationship) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isss", $user_id, $name, $phone, $relationship);
                
                if ($stmt->execute()) {
                    $success_message = "Emergency contact added successfully!";
                } else {
                    $error_message = "Error adding emergency contact. Please try again.";
                }
                break;
                
            case 'delete_emergency_contact':
                $contact_id = $_POST['contact_id'];
                $sql = "DELETE FROM emergency_contacts WHERE id = ? AND user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $contact_id, $user_id);
                
                if ($stmt->execute()) {
                    $success_message = "Emergency contact deleted successfully!";
                } else {
                    $error_message = "Error deleting emergency contact. Please try again.";
                }
                break;
        }
    }
}

// Fetch emergency contacts data
$emergency_contacts = $conn->query("SELECT * FROM emergency_contacts WHERE user_id = $user_id ORDER BY created_at DESC");

// Set variables for shared components
$dashboard_type = 'User';
$current_page = 'emergency-contacts';

// Include header
include '../includes/header.php';
include '../includes/sidebar.php';
?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Emergency Contacts Section -->
            <section class="mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Emergency Contacts</h2>
                        <button onclick="showAddContactModal()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                            <i class="fas fa-plus mr-2"></i>Add Contact
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php if ($emergency_contacts->num_rows > 0): ?>
                            <?php while ($contact = $emergency_contacts->fetch_assoc()): ?>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($contact['name']); ?></h3>
                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($contact['relationship']); ?></p>
                                    </div>
                                    <button onclick="deleteContact(<?php echo $contact['id']; ?>)" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-green-600 mr-2"></i>
                                    <a href="tel:<?php echo htmlspecialchars($contact['phone']); ?>" class="text-green-600 hover:text-green-800">
                                        <?php echo htmlspecialchars($contact['phone']); ?>
                                    </a>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-span-full text-center py-8">
                                <i class="fas fa-address-book text-gray-400 text-4xl mb-4"></i>
                                <p class="text-gray-500">No emergency contacts found</p>
                                <p class="text-sm text-gray-400 mt-2">Add your first emergency contact to get started</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </main>

    <!-- Add Emergency Contact Modal -->
    <div id="addContactModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Add Emergency Contact</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add_emergency_contact">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="text" name="phone" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Relationship</label>
                    <input type="text" name="relationship" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="e.g., Family, Friend, Doctor">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideAddContactModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Add Contact</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showAddContactModal() {
            document.getElementById('addContactModal').classList.remove('hidden');
            document.getElementById('addContactModal').classList.add('flex');
        }

        function hideAddContactModal() {
            document.getElementById('addContactModal').classList.add('hidden');
            document.getElementById('addContactModal').classList.remove('flex');
        }

        function deleteContact(contactId) {
            if (confirm('Are you sure you want to delete this emergency contact?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_emergency_contact">
                    <input type="hidden" name="contact_id" value="${contactId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

<?php include '../includes/footer.php'; ?> 