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
        if ($_POST['action'] === 'create_backup') {
            // Create backup filename
            $backup_filename = 'ambulance_backup_' . date('Y-m-d_H-i-s') . '.sql';
            $backup_path = '../backups/' . $backup_filename;
            
            // Create backups directory if it doesn't exist
            if (!is_dir('../backups')) {
                mkdir('../backups', 0755, true);
            }
            
            // Get database configuration
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'ambulance';
            
            // Create backup command
            $command = "mysqldump --host=$host --user=$username --password=$password $database > $backup_path";
            
            // Execute backup
            $output = shell_exec($command);
            
            if (file_exists($backup_path)) {
                $success_message = "Backup created successfully: $backup_filename";
            } else {
                $error_message = "Error creating backup. Please check permissions.";
            }
        } elseif ($_POST['action'] === 'delete_backup') {
            $backup_file = $_POST['backup_file'];
            $backup_path = '../backups/' . $backup_file;
            
            if (file_exists($backup_path) && unlink($backup_path)) {
                $success_message = "Backup deleted successfully!";
            } else {
                $error_message = "Error deleting backup file.";
            }
        }
    }
}

// Get list of existing backups
$backups = [];
$backup_dir = '../backups/';
if (is_dir($backup_dir)) {
    $files = scandir($backup_dir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
            $file_path = $backup_dir . $file;
            $backups[] = [
                'filename' => $file,
                'size' => filesize($file_path),
                'date' => filemtime($file_path)
            ];
        }
    }
    // Sort by date (newest first)
    usort($backups, function($a, $b) {
        return $b['date'] - $a['date'];
    });
}

// Set variables for shared components
$dashboard_type = 'Admin';
$current_page = 'backup-restore';

// Include header
include '../includes/header.php';
include '../includes/sidebar.php';
?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Backup & Restore Section -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Backup & Restore</h2>
                
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
                    <!-- Create Backup -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Create New Backup</h3>
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-4">
                                Create a complete backup of the database including all tables and data.
                            </p>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                                    <div>
                                        <h4 class="text-sm font-medium text-yellow-800">Important</h4>
                                        <p class="text-sm text-yellow-700 mt-1">
                                            Backups are stored in the backups directory. Ensure you have sufficient disk space.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form method="POST">
                            <input type="hidden" name="action" value="create_backup">
                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-download mr-2"></i>Create Backup
                            </button>
                        </form>
                    </div>
                    
                    <!-- Restore Backup -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Restore from Backup</h3>
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-4">
                                Restore the database from a previously created backup file.
                            </p>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex">
                                    <i class="fas fa-exclamation-triangle text-red-600 mt-1 mr-3"></i>
                                    <div>
                                        <h4 class="text-sm font-medium text-red-800">Warning</h4>
                                        <p class="text-sm text-red-700 mt-1">
                                            This will overwrite all current data. Make sure to backup current data first.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500 mb-4">Restore functionality requires manual database access</p>
                            <button type="button" disabled class="w-full bg-gray-400 text-white px-4 py-2 rounded-lg cursor-not-allowed">
                                <i class="fas fa-upload mr-2"></i>Restore (Manual)
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Existing Backups -->
                <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Existing Backups</h3>
                        <span class="text-sm text-gray-500"><?php echo count($backups); ?> backup(s) found</span>
                    </div>
                    
                    <?php if (count($backups) > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Backup File</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($backups as $backup): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <i class="fas fa-file-archive text-blue-600 mr-2"></i>
                                            <?php echo htmlspecialchars($backup['filename']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo number_format($backup['size'] / 1024, 2); ?> KB
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('M d, Y H:i:s', $backup['date']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="../backups/<?php echo urlencode($backup['filename']); ?>" 
                                                   download class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-download mr-1"></i>Download
                                                </a>
                                                <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this backup?')">
                                                    <input type="hidden" name="action" value="delete_backup">
                                                    <input type="hidden" name="backup_file" value="<?php echo htmlspecialchars($backup['filename']); ?>">
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-trash mr-1"></i>Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <i class="fas fa-database text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500">No backups found</p>
                            <p class="text-sm text-gray-400">Create your first backup to get started</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Backup Information -->
                <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Backup Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600"><?php echo count($backups); ?></div>
                            <div class="text-sm text-gray-600">Total Backups</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">
                                <?php 
                                $total_size = 0;
                                foreach ($backups as $backup) {
                                    $total_size += $backup['size'];
                                }
                                echo number_format($total_size / 1024 / 1024, 2);
                                ?> MB
                            </div>
                            <div class="text-sm text-gray-600">Total Size</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">
                                <?php echo count($backups) > 0 ? date('M d, Y', $backups[0]['date']) : 'N/A'; ?>
                            </div>
                            <div class="text-sm text-gray-600">Latest Backup</div>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">Backup Instructions</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Backups include all database tables and data</li>
                            <li>• Backup files are stored in the backups directory</li>
                            <li>• Regular backups are recommended for data safety</li>
                            <li>• To restore, use phpMyAdmin or MySQL command line</li>
                            <li>• Keep backup files in a secure location</li>
                        </ul>
                    </div>
                </div>
            </section>
        </main>

<?php include '../includes/footer.php'; ?> 