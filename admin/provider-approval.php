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
        if ($_POST['action'] === 'approve_provider') {
            $provider_id = $_POST['provider_id'];
            
            $sql = "UPDATE users SET is_approved = 1 WHERE id = ? AND account_type = 'provider'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $provider_id);
            
            if ($stmt->execute()) {
                $success_message = "Provider approved successfully!";
            } else {
                $error_message = "Error approving provider. Please try again.";
            }
        } elseif ($_POST['action'] === 'reject_provider') {
            $provider_id = $_POST['provider_id'];
            $rejection_reason = $_POST['rejection_reason'];
            
            $sql = "UPDATE users SET is_approved = 0, rejection_reason = ? WHERE id = ? AND account_type = 'provider'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $rejection_reason, $provider_id);
            
            if ($stmt->execute()) {
                $success_message = "Provider rejected successfully!";
            } else {
                $error_message = "Error rejecting provider. Please try again.";
            }
        } elseif ($_POST['action'] === 'suspend_provider') {
            $provider_id = $_POST['provider_id'];
            
            $sql = "UPDATE users SET is_active = 0 WHERE id = ? AND account_type = 'provider'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $provider_id);
            
            if ($stmt->execute()) {
                $success_message = "Provider suspended successfully!";
            } else {
                $error_message = "Error suspending provider. Please try again.";
            }
        } elseif ($_POST['action'] === 'activate_provider') {
            $provider_id = $_POST['provider_id'];
            
            $sql = "UPDATE users SET is_active = 1 WHERE id = ? AND account_type = 'provider'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $provider_id);
            
            if ($stmt->execute()) {
                $success_message = "Provider activated successfully!";
            } else {
                $error_message = "Error activating provider. Please try again.";
            }
        }
    }
}

// Fetch providers for approval
$sql = "SELECT u.*, 
            COUNT(a.id) as ambulance_count,
            COUNT(d.id) as driver_count,
            COUNT(b.id) as booking_count
        FROM users u 
        LEFT JOIN ambulances a ON u.id = a.provider_id 
        LEFT JOIN drivers d ON u.id = d.provider_id 
        LEFT JOIN bookings b ON a.id = b.ambulance_id 
        WHERE u.account_type = 'provider' 
        GROUP BY u.id 
        ORDER BY u.created_at DESC";
$providers = $conn->query($sql);

// Set variables for shared components
$dashboard_type = 'Admin';
$current_page = 'provider-approval';

// Include header
include '../includes/header.php';
include '../includes/sidebar.php';
?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Provider Approval Section -->
            <section class="mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Provider Approval Management</h2>
                    
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resources</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($providers->num_rows > 0): ?>
                                    <?php while ($provider = $providers->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                                        <i class="fas fa-user text-red-600"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo htmlspecialchars($provider['fullname']); ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?php echo htmlspecialchars($provider['email']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo htmlspecialchars($provider['phone']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col space-y-1">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                    <?php 
                                                    if (!$provider['is_active']) {
                                                        echo 'bg-red-100 text-red-800';
                                                    } elseif (!$provider['is_approved']) {
                                                        echo 'bg-yellow-100 text-yellow-800';
                                                    } else {
                                                        echo 'bg-green-100 text-green-800';
                                                    }
                                                    ?>">
                                                    <?php 
                                                    if (!$provider['is_active']) {
                                                        echo 'Suspended';
                                                    } elseif (!$provider['is_approved']) {
                                                        echo 'Pending Approval';
                                                    } else {
                                                        echo 'Approved & Active';
                                                    }
                                                    ?>
                                                </span>
                                                <?php if ($provider['rejection_reason']): ?>
                                                <span class="text-xs text-red-600">
                                                    Reason: <?php echo htmlspecialchars($provider['rejection_reason']); ?>
                                                </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex space-x-4">
                                                <span class="flex items-center">
                                                    <i class="fas fa-ambulance mr-1"></i>
                                                    <?php echo $provider['ambulance_count']; ?>
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="fas fa-user-tie mr-1"></i>
                                                    <?php echo $provider['driver_count']; ?>
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="fas fa-calendar-check mr-1"></i>
                                                    <?php echo $provider['booking_count']; ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('M d, Y', strtotime($provider['created_at'])); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <?php if (!$provider['is_approved']): ?>
                                                    <button onclick="approveProvider(<?php echo $provider['id']; ?>)" 
                                                            class="text-green-600 hover:text-green-900">
                                                        <i class="fas fa-check mr-1"></i>Approve
                                                    </button>
                                                    <button onclick="rejectProvider(<?php echo $provider['id']; ?>)" 
                                                            class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-times mr-1"></i>Reject
                                                    </button>
                                                <?php elseif ($provider['is_active']): ?>
                                                    <button onclick="suspendProvider(<?php echo $provider['id']; ?>)" 
                                                            class="text-yellow-600 hover:text-yellow-900">
                                                        <i class="fas fa-pause mr-1"></i>Suspend
                                                    </button>
                                                <?php else: ?>
                                                    <button onclick="activateProvider(<?php echo $provider['id']; ?>)" 
                                                            class="text-green-600 hover:text-green-900">
                                                        <i class="fas fa-play mr-1"></i>Activate
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No providers found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>

    <!-- Reject Provider Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Reject Provider</h3>
            <form method="POST" id="rejectForm">
                <input type="hidden" name="action" value="reject_provider">
                <input type="hidden" name="provider_id" id="rejectProviderId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                    <textarea name="rejection_reason" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Please provide a reason for rejection"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideRejectModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Reject Provider</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function approveProvider(providerId) {
            if (confirm('Are you sure you want to approve this provider?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="approve_provider">
                    <input type="hidden" name="provider_id" value="${providerId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function rejectProvider(providerId) {
            document.getElementById('rejectProviderId').value = providerId;
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
        }

        function suspendProvider(providerId) {
            if (confirm('Are you sure you want to suspend this provider?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="suspend_provider">
                    <input type="hidden" name="provider_id" value="${providerId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function activateProvider(providerId) {
            if (confirm('Are you sure you want to activate this provider?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="activate_provider">
                    <input type="hidden" name="provider_id" value="${providerId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

<?php include '../includes/footer.php'; ?> 