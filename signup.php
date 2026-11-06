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
    // Sanitize and retrieve form inputs
    $fullname       = trim($_POST["fullname"]);
    $email          = trim($_POST["email"]);
    $phone          = trim($_POST["phone"]);
    $accountType    = $_POST["accountType"];
    $password       = $_POST["password"];
    $confirmPassword= $_POST["confirmPassword"];

    // Basic validation
    if (empty($fullname) || empty($email) || empty($phone) || empty($accountType) || empty($password) || empty($confirmPassword)) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email is already registered.";
        } else {
            // Hash the password securely
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, phone, account_type, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $fullname, $email, $phone, $accountType, $hashedPassword);

            if ($stmt->execute()) {
                // Generate OTP
                $otp = rand(100000, 999999);
                $updateOtp = $conn->prepare("UPDATE users SET otp = ? WHERE email = ?");
                $updateOtp->bind_param("ss", $otp, $email);
                $updateOtp->execute();
                $updateOtp->close();

                // Send OTP email
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'alinskey39@gmail.com'; // Change to your email
                    $mail->Password = 'bjiu vgnf mwef whla'; // Change to your app password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
                    
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
                    $mail->Body = '<p>Your OTP code is <b>' . $otp . '</b>. Please enter this code to verify your email address.</p>';
                    $mail->send();
                } catch (Exception $e) {
                    // Optionally handle email error
                }
                $_SESSION['pending_email'] = $email;
                header('Location: verify-otp.php');
                exit;
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up - QuickMed</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-lg bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-center mb-4">
            <img src="https://i.pinimg.com/736x/75/9c/21/759c217a909362878f52d3893ab699a3.jpg" alt="QuickMed Logo"
                class="w-20 h-20 rounded-full object-cover shadow" />
        </div>
        <h2 class="text-3xl font-bold text-center text-red-600 mb-6">Create Your QuickMed Account</h2>
        <?php if (!empty($error)): ?>
            <div class="mb-4 text-red-600 text-center font-semibold"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="signup.php" class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
            <div class="mb-4">
                <label for="fullname" class="block text-gray-700 font-medium mb-2">Full Name</label>
                <input type="text" id="fullname" name="fullname" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400"
                    placeholder="John Doe">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400"
                    placeholder="you@example.com">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 font-medium mb-2">Phone Number</label>
                <input type="tel" id="phone" name="phone" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400"
                    placeholder="+254 700 123 456">
            </div>
            <div class="mb-4">
                <label for="accountType" class="block text-gray-700 font-medium mb-2">Account Type</label>
                <select id="accountType" name="accountType" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400">
                    <option value="user">User (I need an ambulance)</option>
                    <option value="provider">Provider (I have an ambulance)</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400"
                    placeholder="Create a strong password">
            </div>
            <div class="mb-4">
                <label for="confirmPassword" class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400"
                    placeholder="Confirm your password">
            </div>
            <div class="col-span-1 md:col-span-2">
                <button type="submit"
                    class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition mt-4">Create
                    Account</button>
            </div>
        </form>
        <p class="mt-6 text-center text-gray-600">Already have an account?
            <a href="login.php" class="text-red-600 font-semibold hover:underline">Login</a>
        </p>
    </div>
</body>
</html>
