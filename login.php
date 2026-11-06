<?php
require_once 'session_config.php';
include 'config.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Basic validation
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        // Prepare and execute query
        $stmt = $conn->prepare("SELECT id, fullname, email, password, account_type FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Login successful, but require OTP verification
                $otp_data = generateOTP();
                $otp = $otp_data['code'];
                $otp_timestamp = date('Y-m-d H:i:s', $otp_data['timestamp']);

                $updateOtp = $conn->prepare("UPDATE users SET otp = ?, otp_timestamp = ? WHERE email = ?");
                $updateOtp->bind_param("sss", $otp, $otp_timestamp, $email);
                $updateOtp->execute();
                $updateOtp->close();

                $mail = new PHPMailer(true);
                $email_sent = false;
                try {
                    // Enable verbose debug output for troubleshooting (set to 0 in production)
                    $mail->SMTPDebug = 2; // Shows detailed SMTP conversation (0 = off, 2 = verbose)
                    $mail->Debugoutput = function($str, $level) {
                        error_log("PHPMailer Debug: $str");
                    };

                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'alinskey39@gmail.com';
                    $mail->Password = 'bjiu vgnf mwef whla';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
                    $mail->CharSet = 'UTF-8';

                    // Fix SSL certificate verification issue for local development
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );
                    $mail->setFrom('alinskey39@gmail.com', 'QuickMed OTP');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = 'Your QuickMed OTP Code';
                    $mail->Body = '<p>Your OTP code is <b>' . $otp . '</b>. This code will expire in 5 minutes.</p>';

                    if ($mail->send()) {
                        $email_sent = true;
                        error_log("OTP email sent successfully to: $email");
                    } else {
                        // Log error
                        $error_msg = "Email sending failed: " . $mail->ErrorInfo;
                        error_log($error_msg);
                        $_SESSION['email_error'] = $error_msg;
                    }
                } catch (Exception $e) {
                    // Log the exception with full details
                    $error_msg = "PHPMailer Exception: " . $e->getMessage();
                    error_log($error_msg);
                    $_SESSION['email_error'] = $error_msg;
                }
                $_SESSION['pending_email'] = $email;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['account_type'] = $user['account_type'];
                header('Location: verify-otp.php');
                exit;
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "No account found with that email.";
        }
        $stmt->close();
    }
}
$conn->close();

// Check if session expired
$expired_message = '';
if (isset($_GET['expired']) && $_GET['expired'] == 1) {
    $expired_message = "Your session has expired due to inactivity. Please login again.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - QuickMed</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
  <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
    <div class="flex justify-center mb-4">
      <img src="https://i.pinimg.com/736x/75/9c/21/759c217a909362878f52d3893ab699a3.jpg" alt="QuickMed Logo" class="w-20 h-20 rounded-full object-cover shadow" />
    </div>
    <h2 class="text-3xl font-bold text-center text-blue-600 mb-8">Login to QuickMed</h2>
    <?php if (!empty($error)): ?>
      <div class="mb-4 text-red-600 text-center font-semibold"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if (!empty($expired_message)): ?>
      <div class="mb-4 text-orange-600 text-center font-semibold"><?php echo $expired_message; ?></div>
    <?php endif; ?>
    <form method="POST" action="login.php">
      <div class="mb-6">
        <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
        <input type="email" id="email" name="email" required class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400" placeholder="you@example.com">
      </div>
      <div class="mb-4 relative">
        <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
        <input type="password" id="password" name="password" required class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400" placeholder="Enter your password">
        <button type="button" id="togglePassword" class="absolute right-3 top-10 text-gray-500 hover:text-blue-600 focus:outline-none" tabindex="-1">Show</button>
      </div>
      <div class="flex justify-between items-center mb-6">
        <a href="#" class="text-sm text-red-600 hover:underline">Forgot password?</a>
      </div>
      <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">Login</button>
    </form>
    <p class="mt-6 text-center text-gray-600">Don't have an account?
      <a href="signup.php" class="text-blue-600 font-semibold hover:underline">Sign Up</a>
    </p>
  </div>
  <script>
    // Show/Hide password functionality
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    togglePassword.addEventListener('click', function () {
      const type = passwordInput.type === 'password' ? 'text' : 'password';
      passwordInput.type = type;
      this.textContent = type === 'password' ? 'Show' : 'Hide';
    });
  </script>
</body>
</html>
