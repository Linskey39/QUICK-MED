# Complete File Documentation - Ambulance Booking System

This document explains every file in the ambulance booking system and what code is used.

---

## 📁 **ROOT LEVEL FILES**

### **1. `config.php`** - Database Configuration
**Purpose:** Establishes connection to MySQL database

**Code Explanation:**
- Uses environment variables for security (falls back to defaults if not set)
- Creates MySQLi connection object `$conn`
- Handles connection errors gracefully
- **Key Variables:**
  - `$host` - Database server (default: localhost)
  - `$username` - Database user (default: root)
  - `$password` - Database password (from environment)
  - `$dbname` - Database name (default: ambulance)

**Used By:** Almost every PHP file that needs database access

---

### **2. `session_config.php`** - Session Management
**Purpose:** Manages user sessions, timeouts, and OTP generation

**Code Explanation:**
- **Session Settings:**
  - Timeout: 15 minutes (900 seconds) of inactivity
  - Starts PHP session if not already started
- **Functions:**
  1. `checkSessionTimeout()` - Validates if user session is still active
  2. `checkOTPExpiration($otp_timestamp)` - Checks if OTP is expired (5 minutes)
  3. `generateOTP()` - Creates 6-digit random OTP with timestamp
- **Auto-check:** On every page load, checks if session expired and redirects

**Used By:** All protected pages

---

### **3. `logout.php`** - User Logout
**Purpose:** Ends user session and redirects to login

**Code Explanation:**
- `session_unset()` - Removes all session variables
- `session_destroy()` - Destroys the session completely
- Redirects to login page

**Simple & Clean:** Just 6 lines, handles logout securely

---

### **4. `login.php`** - User Authentication
**Purpose:** Handles user login with email/password and OTP verification

**Code Explanation:**
- **Form Processing (POST):**
  1. Validates email and password are provided
  2. Queries database for user by email
  3. Verifies password using `password_verify()`
  4. If correct:
     - Generates OTP using `generateOTP()`
     - Stores OTP in database with timestamp
     - Sends OTP via email using PHPMailer
     - Stores user info in session
     - Redirects to `verify-otp.php`
- **Email Configuration:**
  - Uses Gmail SMTP
  - SSL/TLS configuration for secure email
  - Error handling with logging
- **Frontend:**
  - Login form with email/password fields
  - Show/hide password toggle
  - Error message display
  - Session expired message support

**Security Features:**
- Password hashing verification
- Two-factor authentication (OTP)
- Prepared statements (SQL injection prevention)

---

### **5. `signup.php`** - User Registration
**Purpose:** Creates new user accounts

**Code Explanation:**
- **Form Processing:**
  1. Validates all fields are filled
  2. Validates email format using `filter_var()`
  3. Checks if passwords match
  4. Checks if email already exists in database
  5. If valid:
     - Hashes password using `password_hash()`
     - Inserts user into database
     - Generates OTP
     - Sends OTP email
     - Redirects to OTP verification
- **Account Types:**
  - `user` - Regular user needing ambulance service
  - `provider` - Ambulance service provider
- **Frontend:**
  - Multi-field registration form
  - Account type selection
  - Password confirmation
  - Error handling

**Security:**
- Password hashing (bcrypt)
- Email validation
- Prepared statements
- OTP verification

---

### **6. `verify-otp.php`** - OTP Verification
**Purpose:** Verifies OTP code sent via email

**Code Explanation:**
- **Verification Process:**
  1. Checks if user has pending email in session
  2. Validates entered OTP against database
  3. Checks OTP expiration (5 minutes)
  4. If valid:
     - Clears OTP from database
     - Sets session variables
     - Redirects to appropriate dashboard based on account type
- **OTP Expiration:**
  - Calculates remaining time
  - JavaScript countdown timer
- **Account Type Routing:**
  - `admin` → `admin/dashboard.php`
  - `provider` → `provider/dashboard.php`
  - `user` → `user/dashboard.php`

**Frontend Features:**
- OTP input field (6 digits)
- Real-time countdown timer
- Error messages for invalid/expired OTP
- Email error display if sending failed

---

### **7. `index.php`** - Landing Page
**Purpose:** Public-facing homepage with information about the service

**Code Explanation:**
- **Structure:**
  1. Header with navigation
  2. Hero section with call-to-action
  3. Services section
  4. How it works section
  5. Features section
  6. Statistics section
  7. Testimonials section
  8. Footer
- **Styling:**
  - Tailwind CSS for responsive design
  - Font Awesome icons
  - Custom animations (fade-in, slide-up, etc.)
- **JavaScript:**
  - Smooth scrolling
  - Mobile menu toggle
  - Scroll reveal animations
  - Counter animations for statistics

**No Authentication Required:** Public page

---

### **8. `database.sql`** - Database Schema
**Purpose:** SQL script to create database structure

**Code Explanation:**
- **Tables Created:**
  1. **users** - User accounts (user/provider/admin)
     - Stores credentials, OTP, account type, approval status
  2. **ambulances** - Ambulance vehicles
     - Links to provider, vehicle info, status
  3. **drivers** - Driver information
     - Links to provider and ambulance
  4. **bookings** - Booking records
     - Links user to ambulance, stores location, status, amount
  5. **emergency_contacts** - Emergency contact info
     - Links to user accounts
  6. **saved_locations** - Saved pickup/destination locations
  7. **service_areas** - Provider service coverage areas
  8. **system_settings** - Application configuration
- **Features:**
  - Foreign key relationships
  - Indexes for performance
  - Sample data for testing
  - Default system settings

**Relationships:**
- Users → Ambulances (provider)
- Users → Bookings (customer)
- Ambulances → Drivers
- Users → Emergency Contacts

---

### **9. `env.example`** - Environment Variables Template
**Purpose:** Template for environment configuration

**Variables:**
- `DB_HOST` - Database hostname
- `DB_USERNAME` - Database username
- `DB_PASSWORD` - Database password
- `DB_NAME` - Database name
- `EMAIL_HOST` - SMTP server
- `EMAIL_PORT` - SMTP port
- `EMAIL_USERNAME` - Email username
- `EMAIL_PASSWORD` - Email app password
- `SECRET_KEY` - Security key for encryption

**Usage:** Copy to `.env` and fill in actual values (never commit `.env`)

---

### **10. `test_db.php`** - Database Connection Tester
**Purpose:** Diagnostic tool to test database setup

**Code Explanation:**
- Tests database connection
- Checks if all required tables exist
- Displays table structures
- Shows record counts
- Tests related data queries (JOINs)
- Displays sample booking data with relationships

**Useful For:** Debugging database issues during setup

---

## 📁 **INCLUDES FOLDER** - Shared Components

### **11. `includes/header.php`** - Dashboard Header
**Purpose:** Reusable header for all dashboard pages

**Code Explanation:**
- Dynamic title based on dashboard type (User/Provider/Admin)
- Displays welcome message with user's fullname
- Logout button
- Includes Tailwind CSS and Font Awesome
- Styled header with logo and navigation

**Variables Required:**
- `$dashboard_type` - Type of dashboard (User/Provider/Admin)

---

### **12. `includes/sidebar.php`** - Navigation Sidebar
**Purpose:** Dynamic sidebar navigation based on user type

**Code Explanation:**
- **User Sidebar:**
  - Overview, Book Ambulance, My Bookings, Payment History
  - Saved Locations, Emergency Contacts, Profile Settings
- **Provider Sidebar:**
  - Overview, Earnings, My Ambulances, My Drivers
  - Service Areas, My Bookings, All Bookings, Profile Settings
- **Admin Sidebar:**
  - Overview, Reports, Users Management, Provider Approval
  - Ambulances, Drivers, Bookings, Emergency Contacts
  - System Settings, Backup & Restore

**Features:**
- Highlights current page
- Font Awesome icons
- Responsive design

**Variables Required:**
- `$dashboard_type` - User/Provider/Admin
- `$current_page` - Current page identifier

---

### **13. `includes/footer.php`** - Footer Component
**Purpose:** Simple footer closing tags

**Code Explanation:**
- Closes main content div
- Closes body and html tags

**Very Simple:** Just closing tags for consistent structure

---

## 📁 **USER FOLDER** - User Dashboard Pages

### **14. `user/dashboard.php`** - User Overview Dashboard
**Purpose:** Main dashboard for regular users

**Code Explanation:**
- **Authentication Check:**
  - Verifies user is logged in
  - Checks account type is 'user'
  - Redirects to login if not authorized
- **Statistics Queries:**
  - Active bookings count
  - Completed bookings count
  - Total bookings count
  - Emergency contacts count
- **Display:**
  - Statistics cards with icons
  - Quick action buttons
  - Recent activity (last 3 bookings)
- **Status Colors:**
  - Yellow = pending
  - Blue = accepted
  - Purple = in_progress
  - Green = completed
  - Red = cancelled

**Includes:** Header, Sidebar, Footer components

---

### **Other User Pages** (Similar Structure)
- **`user/book-ambulance.php`** - Book ambulance service
- **`user/my-bookings.php`** - View all bookings
- **`user/payment-history.php`** - Payment records
- **`user/saved-locations.php`** - Manage saved locations
- **`user/emergency-contacts.php`** - Manage emergency contacts
- **`user/profile-settings.php`** - Update profile information

**Common Features:**
- Authentication checks
- CRUD operations (Create, Read, Update, Delete)
- Form validation
- Database queries with prepared statements

---

## 📁 **PROVIDER FOLDER** - Provider Dashboard Pages

**Similar structure to user folder but with provider-specific features:**

- **`provider/dashboard.php`** - Provider overview
- **`provider/earnings.php`** - Revenue and earnings
- **`provider/ambulances.php`** - Manage ambulance fleet
- **`provider/drivers.php`** - Manage drivers
- **`provider/service-areas.php`** - Define service coverage
- **`provider/my-bookings.php`** - Provider's bookings
- **`provider/all-bookings.php`** - All available bookings
- **`provider/profile-settings.php`** - Provider profile

**Provider-Specific Features:**
- Ambulance management (add, edit, status)
- Driver assignment to ambulances
- Service area definition
- Booking acceptance/rejection

---

## 📁 **ADMIN FOLDER** - Admin Panel Pages

**Admin has access to everything:**

- **`admin/dashboard.php`** - Admin overview with system stats
- **`admin/reports.php`** - Analytics and reports
- **`admin/users.php`** - User management
- **`admin/provider-approval.php`** - Approve/reject providers
- **`admin/ambulances.php`** - View all ambulances
- **`admin/drivers.php`** - View all drivers
- **`admin/bookings.php`** - View all bookings
- **`admin/emergency-contacts.php`** - Manage emergency contacts
- **`admin/system-settings.php`** - System configuration
- **`admin/backup-restore.php`** - Database backup/restore

**Admin Powers:**
- Approve/reject providers
- View all data across system
- System configuration
- Backup and restore database

---

## 📁 **PHPMailer FOLDER** - Email Library

### **PHPMailer Files:**
- **`PHPMailer/PHPMailer.php`** - Main email class
- **`PHPMailer/SMTP.php`** - SMTP transport class
- **`PHPMailer/Exception.php`** - Exception handling

**Purpose:** Third-party library for sending emails via SMTP

**Usage:** Used in `login.php` and `signup.php` for OTP emails

---

## 📁 **BACKUPS FOLDER**

### **`backups/ambulance_backup_YYYY-MM-DD_HH-MM-SS.sql`**
**Purpose:** Database backup files

**Created By:** Admin backup system

---

## 🔐 **SECURITY FEATURES**

### **Throughout the System:**

1. **Password Hashing:**
   - `password_hash()` - Bcrypt hashing
   - `password_verify()` - Secure verification

2. **SQL Injection Prevention:**
   - Prepared statements everywhere
   - `bind_param()` for parameter binding

3. **XSS Prevention:**
   - `htmlspecialchars()` for output escaping

4. **Session Security:**
   - 15-minute timeout
   - Session validation on every page
   - Secure session handling

5. **Two-Factor Authentication:**
   - OTP via email
   - 5-minute expiration

6. **Access Control:**
   - Role-based access (user/provider/admin)
   - Page-level authorization checks

---

## 📊 **DATABASE STRUCTURE**

### **Key Relationships:**

```
users (1) ──→ (many) ambulances (provider_id)
users (1) ──→ (many) bookings (user_id)
users (1) ──→ (many) emergency_contacts (user_id)
ambulances (1) ──→ (many) drivers (ambulance_id)
bookings (many) ──→ (1) ambulances (ambulance_id)
```

---

## 🎨 **FRONTEND TECHNOLOGIES**

- **Tailwind CSS** - Utility-first CSS framework
- **Font Awesome** - Icon library
- **JavaScript** - Client-side interactivity
- **Responsive Design** - Mobile-first approach

---

## 🔄 **COMMON PATTERNS**

### **Typical Page Structure:**

```php
<?php
// 1. Include session config
require_once 'session_config.php';

// 2. Include database config
include 'config.php';

// 3. Authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['account_type'] !== 'user') {
    header('Location: login.php');
    exit();
}

// 4. Set dashboard variables
$dashboard_type = 'User';
$current_page = 'overview';

// 5. Process forms (if POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
}

// 6. Fetch data from database
// ... queries ...

// 7. Include header
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<!-- 8. HTML Content -->
...

<?php include 'includes/footer.php'; ?>
```

---

## 📝 **SUMMARY**

This is a **full-stack PHP/MySQL web application** with:
- **Frontend:** HTML, CSS (Tailwind), JavaScript
- **Backend:** PHP with MySQLi
- **Authentication:** Two-factor (Password + OTP)
- **Security:** Prepared statements, password hashing, session management
- **Architecture:** MVC-like structure with shared components
- **User Roles:** User, Provider, Admin with different dashboards

All files work together to create a complete ambulance booking and management system!


