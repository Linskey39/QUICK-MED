# OTP Code Connections - Complete Breakdown

This document shows **ALL** the code lines that connect with OTP in your ambulance booking system.

---

## 🔗 **OTP Flow Overview**

```
1. OTP GENERATION → 2. STORAGE IN DATABASE → 3. EMAIL SENDING → 4. USER INPUT → 5. VERIFICATION → 6. CLEARANCE
```

---

## 📄 **1. OTP GENERATION FUNCTION**

### **File: `session_config.php`**

```php
// Lines 44-50: Function that generates OTP
function generateOTP() {
    return [
        'code' => rand(100000, 999999),    // Generates 6-digit random number
        'timestamp' => time()              // Gets current Unix timestamp
    ];
}
```

**What it does:**
- Generates a random 6-digit OTP (100000 to 999999)
- Creates a timestamp for expiration tracking
- Returns both as an array

**Called from:** `login.php` (line 29)

---

## 📄 **2. OTP EXPIRATION CHECK FUNCTION**

### **File: `session_config.php`**

```php
// Lines 33-42: Function that checks if OTP is expired
function checkOTPExpiration($otp_timestamp) {
    $otp_timeout = 300; // 5 minutes in seconds

    if (time() - $otp_timestamp > $otp_timeout) {
        return false; // OTP expired
    }

    return true; // OTP still valid
}
```

**What it does:**
- Checks if OTP is older than 5 minutes (300 seconds)
- Returns `false` if expired, `true` if still valid

**Called from:** `verify-otp.php` (line 32)

---

## 📄 **3. OTP STORAGE IN DATABASE (LOGIN)**

### **File: `login.php`**

```php
// Lines 29-36: Generate and store OTP after successful login
$otp_data = generateOTP();                                    // Line 29: Call generation function
$otp = $otp_data['code'];                                     // Line 30: Extract OTP code
$otp_timestamp = date('Y-m-d H:i:s', $otp_data['timestamp']); // Line 31: Convert timestamp to MySQL format

$updateOtp = $conn->prepare("UPDATE users SET otp = ?, otp_timestamp = ? WHERE email = ?");  // Line 33: Prepare SQL
$updateOtp->bind_param("sss", $otp, $otp_timestamp, $email);  // Line 34: Bind parameters
$updateOtp->execute();                                         // Line 35: Execute query
$updateOtp->close();                                           // Line 36: Close statement
```

**What it does:**
- Generates OTP after password verification
- Stores OTP code and timestamp in database
- Links to user's email address

**Database fields updated:**
- `users.otp` - The 6-digit code
- `users.otp_timestamp` - When OTP was created

---

## 📄 **4. OTP STORAGE IN DATABASE (SIGNUP)**

### **File: `signup.php`**

```php
// Lines 45-50: Generate and store OTP after user registration
$otp = rand(100000, 999999);                                  // Line 46: Generate 6-digit OTP
$updateOtp = $conn->prepare("UPDATE users SET otp = ? WHERE email = ?");  // Line 47: Prepare SQL
$updateOtp->bind_param("ss", $otp, $email);                  // Line 48: Bind parameters
$updateOtp->execute();                                        // Line 49: Execute query
$updateOtp->close();                                          // Line 50: Close statement
```

**What it does:**
- Generates OTP after successful user registration
- Stores OTP in database (timestamp not stored here, only in login)

**Note:** Signup uses simple `rand()` instead of `generateOTP()` function

---

## 📄 **5. OTP EMAIL SENDING (LOGIN)**

### **File: `login.php`**

```php
// Lines 38-84: Send OTP via email using PHPMailer
$mail = new PHPMailer(true);                                   // Line 38: Initialize PHPMailer
try {
    // ... email configuration (lines 47-63) ...

    $mail->setFrom('alinskey39@gmail.com', 'QuickMed OTP');    // Line 64: Set sender
    $mail->addAddress($email);                                 // Line 65: Add recipient
    $mail->isHTML(true);                                       // Line 66: Enable HTML
    $mail->Subject = 'Your QuickMed OTP Code';                 // Line 67: Email subject
    $mail->Body = '<p>Your OTP code is <b>' . $otp . '</b>. This code will expire in 5 minutes.</p>';  // Line 68: Email body with OTP

    if ($mail->send()) {                                       // Line 70: Send email
        $email_sent = true;
        error_log("OTP email sent successfully to: $email");   // Line 72: Log success
    } else {
        $error_msg = "Email sending failed: " . $mail->ErrorInfo;  // Line 75: Capture error
        error_log($error_msg);                                  // Line 76: Log error
        $_SESSION['email_error'] = $error_msg;                  // Line 77: Store in session
    }
} catch (Exception $e) {                                       // Line 79: Catch exceptions
    $error_msg = "PHPMailer Exception: " . $e->getMessage();   // Line 81: Get error message
    error_log($error_msg);                                     // Line 82: Log error
    $_SESSION['email_error'] = $error_msg;                     // Line 83: Store in session
}
```

**What it does:**
- Configures SMTP email settings
- Creates HTML email with OTP code
- Sends email to user
- Handles errors and logs them

**Email Content:**
- Subject: "Your QuickMed OTP Code"
- Body: Shows the 6-digit OTP code
- Note: Expires in 5 minutes

---

## 📄 **6. OTP EMAIL SENDING (SIGNUP)**

### **File: `signup.php`**

```php
// Lines 52-79: Send OTP via email during registration
$mail = new PHPMailer(true);                                   // Line 53: Initialize PHPMailer
try {
    // ... email configuration (lines 55-70) ...

    $mail->setFrom('alinskey39@gmail.com', 'QuickMed OTP');    // Line 71: Set sender
    $mail->addAddress($email);                                 // Line 72: Add recipient
    $mail->isHTML(true);                                       // Line 73: Enable HTML
    $mail->Subject = 'Your QuickMed OTP Code';                 // Line 74: Email subject
    $mail->Body = '<p>Your OTP code is <b>' . $otp . '</b>. Please enter this code to verify your email address.</p>';  // Line 75: Email body with OTP
    $mail->send();                                             // Line 76: Send email
} catch (Exception $e) {
    // Optionally handle email error                        // Line 78: Silent error handling
}
```

**What it does:**
- Same as login, but simpler error handling
- Sends OTP for email verification during signup

**Difference from Login:**
- Different email message text
- No error logging (silent failure)

---

## 📄 **7. SESSION MANAGEMENT (REDIRECT TO OTP PAGE)**

### **File: `login.php`**

```php
// Line 85: Store email in session for OTP verification
$_SESSION['pending_email'] = $email;                           // Line 85: Store email
// ... other session variables (lines 86-88) ...
header('Location: verify-otp.php');                            // Line 89: Redirect to OTP page
exit;                                                           // Line 90: Stop execution
```

### **File: `signup.php`**

```php
// Line 80: Store email in session for OTP verification
$_SESSION['pending_email'] = $email;                           // Line 80: Store email
header('Location: verify-otp.php');                            // Line 81: Redirect to OTP page
exit;                                                           // Line 82: Stop execution
```

**What it does:**
- Stores user's email in session
- Redirects to OTP verification page

---

## 📄 **8. OTP VERIFICATION (USER INPUT)**

### **File: `verify-otp.php`**

```php
// Lines 20-27: Get OTP from database when user submits form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {                   // Line 20: Check if form submitted
    $entered_otp = trim($_POST['otp']);                        // Line 21: Get OTP from form input
    $stmt = $conn->prepare('SELECT otp, otp_timestamp FROM users WHERE email = ?');  // Line 22: Prepare query
    $stmt->bind_param('s', $email);                            // Line 23: Bind email parameter
    $stmt->execute();                                          // Line 24: Execute query
    $stmt->bind_result($db_otp, $db_otp_timestamp);           // Line 25: Bind result variables
    $stmt->fetch();                                            // Line 26: Fetch the row
    $stmt->close();                                            // Line 27: Close statement
```

**What it does:**
- Gets user-entered OTP from form
- Retrieves stored OTP and timestamp from database
- Prepares for comparison

**Form Input (Line 119):**
```php
<input type="text" id="otp" name="otp" maxlength="6" required ...>
```

---

## 📄 **9. OTP VALIDATION & EXPIRATION CHECK**

### **File: `verify-otp.php`**

```php
// Lines 29-34: Validate OTP and check expiration
if ($db_otp !== null && $db_otp_timestamp !== null) {         // Line 29: Check if OTP exists
    // Check if OTP has expired (5 minutes)
    $otp_timestamp = strtotime($db_otp_timestamp);             // Line 31: Convert to Unix timestamp
    if (!checkOTPExpiration($otp_timestamp)) {                 // Line 32: Check if expired
        $error = 'OTP has expired. Please request a new OTP.'; // Line 33: Set error message
    } elseif ($entered_otp === $db_otp) {                     // Line 34: Compare OTP codes
```

**What it does:**
- Checks if OTP exists in database
- Validates expiration (5 minutes)
- Compares entered OTP with stored OTP

**Validation Steps:**
1. OTP exists? ✓
2. OTP not expired? ✓
3. OTP matches? ✓

---

## 📄 **10. OTP CLEARANCE (AFTER SUCCESSFUL VERIFICATION)**

### **File: `verify-otp.php`**

```php
// Lines 36-39: Clear OTP from database after successful verification
$update = $conn->prepare('UPDATE users SET otp = NULL, otp_timestamp = NULL WHERE email = ?');  // Line 36: Prepare SQL
$update->bind_param('s', $email);                              // Line 37: Bind email
$update->execute();                                            // Line 38: Execute query
$update->close();                                              // Line 39: Close statement
```

**What it does:**
- Removes OTP from database after successful verification
- Sets both `otp` and `otp_timestamp` to NULL
- Prevents OTP reuse

**Security:** Once used, OTP is deleted (single-use)

---

## 📄 **11. OTP EXPIRATION COUNTDOWN (FRONTEND)**

### **File: `verify-otp.php`**

```php
// Lines 80-93: Calculate remaining time for OTP
$stmt = $conn->prepare('SELECT otp_timestamp FROM users WHERE email = ?');  // Line 81: Query timestamp
$stmt->bind_param('s', $email);                               // Line 82: Bind email
$stmt->execute();                                             // Line 83: Execute
$stmt->bind_result($db_otp_timestamp);                        // Line 84: Get timestamp
$stmt->fetch();                                               // Line 85: Fetch result
$stmt->close();                                               // Line 86: Close

$remaining_time = 0;                                          // Line 88: Initialize
if ($db_otp_timestamp !== null) {                             // Line 89: Check if exists
    $otp_timestamp = strtotime($db_otp_timestamp);            // Line 90: Convert to Unix time
    $elapsed_time = time() - $otp_timestamp;                  // Line 91: Calculate elapsed time
    $remaining_time = max(0, 300 - $elapsed_time);            // Line 92: Calculate remaining (5 min = 300 sec)
}
```

**What it does:**
- Calculates how much time is left before OTP expires
- Used to display countdown timer

**JavaScript Countdown (Lines 134-151):**
```javascript
let timeLeft = <?php echo $remaining_time; ?>;                // Line 136: Get remaining time from PHP
const countdown = setInterval(function() {                    // Line 139: Start countdown
    timeLeft--;                                               // Line 140: Decrease by 1 second
    // ... display countdown (lines 141-148) ...
}, 1000);                                                     // Update every second
```

---

## 📄 **12. DATABASE SCHEMA FOR OTP**

### **File: `database.sql`**

```sql
-- Lines 12-13: OTP columns in users table
otp VARCHAR(6) DEFAULT NULL,           -- Stores 6-digit OTP code
otp_timestamp TIMESTAMP NULL,          -- Stores when OTP was created
```

**What it does:**
- Defines database structure for OTP storage
- `otp` - Stores the 6-digit code (nullable)
- `otp_timestamp` - Stores creation time (nullable)

**Table:** `users`

---

## 🔄 **COMPLETE OTP FLOW DIAGRAM**

```
┌─────────────────────────────────────────────────────────────┐
│                     LOGIN/SIGNUP FLOW                        │
└─────────────────────────────────────────────────────────────┘

1. USER LOGS IN/SIGNS UP
   ↓
2. GENERATE OTP
   session_config.php → generateOTP() [Lines 45-50]
   ↓
3. STORE OTP IN DATABASE
   login.php → Lines 29-36
   signup.php → Lines 45-50
   ↓
4. SEND OTP VIA EMAIL
   login.php → Lines 38-84
   signup.php → Lines 52-79
   ↓
5. REDIRECT TO VERIFY-OTP PAGE
   login.php → Line 89
   signup.php → Line 81
   ↓
┌─────────────────────────────────────────────────────────────┐
│                   VERIFICATION FLOW                          │
└─────────────────────────────────────────────────────────────┘

6. USER ENTERS OTP
   verify-otp.php → Line 21 (Form input)
   ↓
7. RETRIEVE OTP FROM DATABASE
   verify-otp.php → Lines 22-27
   ↓
8. CHECK OTP EXPIRATION
   verify-otp.php → Line 32
   session_config.php → checkOTPExpiration() [Lines 34-42]
   ↓
9. VALIDATE OTP CODE
   verify-otp.php → Line 34 (Compare codes)
   ↓
10. CLEAR OTP FROM DATABASE
    verify-otp.php → Lines 36-39
    ↓
11. SET SESSION & REDIRECT
    verify-otp.php → Lines 50-70 (Set session, redirect to dashboard)
```

---

## 📊 **SUMMARY TABLE - ALL OTP CODE LINES**

| Action | File | Line Numbers | Description |
|--------|------|--------------|-------------|
| **Generate OTP** | `session_config.php` | 45-50 | Function that creates 6-digit OTP |
| **Check Expiration** | `session_config.php` | 34-42 | Function that validates OTP age |
| **Store OTP (Login)** | `login.php` | 29-36 | Generates & saves OTP to database |
| **Send Email (Login)** | `login.php` | 38-84 | Sends OTP via PHPMailer |
| **Store OTP (Signup)** | `signup.php` | 45-50 | Generates & saves OTP to database |
| **Send Email (Signup)** | `signup.php` | 52-79 | Sends OTP via PHPMailer |
| **Get OTP Input** | `verify-otp.php` | 21 | Retrieves user-entered OTP |
| **Fetch from DB** | `verify-otp.php` | 22-27 | Gets stored OTP from database |
| **Validate Expiration** | `verify-otp.php` | 31-33 | Checks if OTP expired |
| **Compare Codes** | `verify-otp.php` | 34 | Matches entered vs stored OTP |
| **Clear OTP** | `verify-otp.php` | 36-39 | Deletes OTP after verification |
| **Countdown Timer** | `verify-otp.php` | 80-93, 134-151 | Calculates & displays time remaining |

---

## 🔐 **KEY OTP VARIABLES**

| Variable | Type | Description | Location |
|----------|------|-------------|----------|
| `$otp` | String | The 6-digit code | Multiple files |
| `$otp_timestamp` | Timestamp | When OTP was created | `login.php`, `verify-otp.php` |
| `$entered_otp` | String | User input OTP | `verify-otp.php` Line 21 |
| `$db_otp` | String | Stored OTP from DB | `verify-otp.php` Line 25 |
| `$remaining_time` | Integer | Seconds until expiry | `verify-otp.php` Line 92 |

---

## 📝 **DATABASE CONNECTIONS**

**Table:** `users`

**OTP Columns:**
- `otp VARCHAR(6)` - Stores the code
- `otp_timestamp TIMESTAMP` - Stores creation time

**SQL Operations:**

1. **INSERT/UPDATE OTP:**
   ```sql
   UPDATE users SET otp = ?, otp_timestamp = ? WHERE email = ?
   ```

2. **SELECT OTP:**
   ```sql
   SELECT otp, otp_timestamp FROM users WHERE email = ?
   ```

3. **CLEAR OTP:**
   ```sql
   UPDATE users SET otp = NULL, otp_timestamp = NULL WHERE email = ?
   ```

---

## 🎯 **QUICK REFERENCE - MAIN OTP FUNCTIONS**

### **Generation:**
- **Function:** `generateOTP()`
- **File:** `session_config.php` Lines 45-50
- **Returns:** Array with `['code' => 123456, 'timestamp' => 1234567890]`

### **Expiration Check:**
- **Function:** `checkOTPExpiration($otp_timestamp)`
- **File:** `session_config.php` Lines 34-42
- **Returns:** `true` if valid, `false` if expired

### **Email Sending:**
- **Library:** PHPMailer
- **Location:** `login.php` Lines 38-84, `signup.php` Lines 52-79
- **Method:** SMTP via Gmail

---

**This is EVERY line of code that connects with OTP in your system!** 🔗


