<?php
// session_config.php - Session configuration for ambulance booking system

// Set session timeout to 15 minutes (900 seconds) of inactivity
ini_set('session.gc_maxlifetime', 900);
ini_set('session.cookie_lifetime', 900);

// Set session timeout for garbage collection
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);

// Start session with custom settings
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user session is still valid
function checkSessionTimeout() {
    $timeout = 900; // 15 minutes in seconds
    
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        // Session has expired
        session_unset();
        session_destroy();
        return false;
    }
    
    // Update last activity time
    $_SESSION['last_activity'] = time();
    return true;
}

// Function to check OTP expiration (5 minutes)
function checkOTPExpiration($otp_timestamp) {
    $otp_timeout = 300; // 5 minutes in seconds
    
    if (time() - $otp_timestamp > $otp_timeout) {
        return false; // OTP expired
    }
    
    return true; // OTP still valid
}

// Function to generate OTP with timestamp
function generateOTP() {
    return [
        'code' => rand(100000, 999999),
        'timestamp' => time()
    ];
}

// Check session timeout on every page load
if (isset($_SESSION['user_id'])) {
    if (!checkSessionTimeout()) {
        // Redirect to login if session expired
        header('Location: login.php?expired=1');
        exit();
    }
}
?> 