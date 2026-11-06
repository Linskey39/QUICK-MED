<?php
require_once 'session_config.php';
include 'config.php';

if (!isset($_SESSION['pending_email'])) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['pending_email'];
$error = '';

// Check for email sending errors
$email_error = '';
if (isset($_SESSION['email_error'])) {
    $email_error = $_SESSION['email_error'];
    unset($_SESSION['email_error']); // Clear after displaying
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = trim($_POST['otp']);
    $stmt = $conn->prepare('SELECT otp, otp_timestamp FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($db_otp, $db_otp_timestamp);
    $stmt->fetch();
    $stmt->close();

    if ($db_otp !== null && $db_otp_timestamp !== null) {
        // Check if OTP has expired (5 minutes)
        $otp_timestamp = strtotime($db_otp_timestamp);
        if (!checkOTPExpiration($otp_timestamp)) {
            $error = 'OTP has expired. Please request a new OTP.';
        } elseif ($entered_otp === $db_otp) {
            // OTP is correct and not expired, clear OTP and allow login
            $update = $conn->prepare('UPDATE users SET otp = NULL, otp_timestamp = NULL WHERE email = ?');
            $update->bind_param('s', $email);
            $update->execute();
            $update->close();
            
            // Fetch account type
            $type_stmt = $conn->prepare('SELECT account_type, fullname, id FROM users WHERE email = ?');
            $type_stmt->bind_param('s', $email);
            $type_stmt->execute();
            $type_stmt->bind_result($account_type, $fullname, $user_id);
            $type_stmt->fetch();
            $type_stmt->close();
            
            unset($_SESSION['pending_email']);
            $_SESSION['otp_verified'] = true;
            $_SESSION['account_type'] = $account_type;
            $_SESSION['fullname'] = $fullname;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['last_activity'] = time(); // Set initial activity time
            
            // Redirect based on account type to the correct folder
            switch ($account_type) {
                case 'admin':
                    header('Location: admin/dashboard.php');
                    break;
                case 'provider':
                    header('Location: provider/dashboard.php');
                    break;
                case 'user':
                    header('Location: user/dashboard.php');
                    break;
                default:
                    header('Location: login.php');
                    break;
            }
            exit;
        } else {
            $error = 'Invalid OTP. Please try again.';
        }
    } else {
        $error = 'OTP Verified. Please login to proceed.';
    }
}

// Calculate remaining time for OTP
$stmt = $conn->prepare('SELECT otp_timestamp FROM users WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->bind_result($db_otp_timestamp);
$stmt->fetch();
$stmt->close();

$remaining_time = 0;
if ($db_otp_timestamp !== null) {
    $otp_timestamp = strtotime($db_otp_timestamp);
    $elapsed_time = time() - $otp_timestamp;
    $remaining_time = max(0, 300 - $elapsed_time); // 5 minutes = 300 seconds
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verify OTP - QuickMed</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center text-red-600 mb-6">Verify Your Email</h2>
        <?php if (!empty($error)): ?>
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-center font-semibold"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($email_error)): ?>
            <div class="mb-4 p-3 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded-lg text-sm">
                <strong>Email Error:</strong> <?php echo htmlspecialchars($email_error); ?>
                <br><small class="text-gray-600">Please check your PHP error log for more details. You can still try to enter the OTP if it was sent.</small>
            </div>
        <?php endif; ?>
        <form method="POST" action="verify-otp.php">
            <div class="mb-4">
                <label for="otp" class="block text-gray-700 font-medium mb-2">Enter OTP sent to your email</label>
                <input type="text" id="otp" name="otp" maxlength="6" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400" placeholder="6-digit OTP">
                <?php if ($remaining_time > 0): ?>
                    <div class="mt-2 text-sm text-gray-600">
                        OTP expires in: <span id="countdown"><?php echo floor($remaining_time / 60); ?>:<?php echo str_pad($remaining_time % 60, 2, '0', STR_PAD_LEFT); ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition">Verify OTP</button>
        </form>
        <div class="mt-4 text-center">
            <a href="login.php" class="text-sm text-red-600 hover:underline">Back to Login</a>
        </div>
    </div>

    <?php if ($remaining_time > 0): ?>
    <script>
        // Countdown timer for OTP expiration
        let timeLeft = <?php echo $remaining_time; ?>;
        const countdownElement = document.getElementById('countdown');
        
        const countdown = setInterval(function() {
            timeLeft--;
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            countdownElement.textContent = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
            
            if (timeLeft <= 0) {
                clearInterval(countdown);
                countdownElement.textContent = 'Expired';
                countdownElement.style.color = 'red';
            }
        }, 1000);
    </script>
    <?php endif; ?>
</body>
</html> 