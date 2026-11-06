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
        if ($_POST['action'] === 'update_profile') {
            $fullname = $_POST['fullname'];
            $phone = $_POST['phone'];
            
            $sql = "UPDATE users SET fullname = ?, phone = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $fullname, $phone, $user_id);
            
            if ($stmt->execute()) {
                $_SESSION['fullname'] = $fullname;
                $success_message = "Profile updated successfully!";
            } else {
                $error_message = "Error updating profile. Please try again.";
            }
        } elseif ($_POST['action'] === 'change_password') {
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            // Verify current password
            $sql = "SELECT password FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            
            if (password_verify($current_password, $user['password'])) {
                if ($new_password === $confirm_password) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET password = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $hashed_password, $user_id);
                    
                    if ($stmt->execute()) {
                        $success_message = "Password changed successfully!";
                    } else {
                        $error_message = "Error changing password. Please try again.";
                    }
                } else {
                    $error_message = "New passwords do not match.";
                }
            } else {
                $error_message = "Current password is incorrect.";
            }
        }
    }
}

// Fetch user data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Set variables for shared components
$dashboard_type = 'User';
$current_page = 'profile-settings';

// Include header
include '../includes/header.php';
include '../includes/sidebar.php';
?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Profile Settings Section -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Profile Settings</h2>
                
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
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Update Profile -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Profile</h3>
                        <form method="POST">
                            <input type="hidden" name="action" value="update_profile">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                                <p class="text-sm text-gray-500 mt-1">Email cannot be changed</p>
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Update Profile</button>
                        </form>
                    </div>
                    
                    <!-- Change Password -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h3>
                        <form method="POST">
                            <input type="hidden" name="action" value="change_password">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                <input type="password" name="current_password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                <input type="password" name="new_password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                <input type="password" name="confirm_password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Change Password</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>

<?php include '../includes/footer.php'; ?> 