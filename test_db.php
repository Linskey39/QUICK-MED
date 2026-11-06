<?php
require_once 'config.php';

echo "<h2>Database Connection Test</h2>";

// Test connection
if ($conn->ping()) {
    echo "<p style='color: green;'>✓ Database connection successful</p>";
} else {
    echo "<p style='color: red;'>✗ Database connection failed</p>";
    exit();
}

// Test required tables
$required_tables = [
    'users',
    'ambulances',
    'drivers', 
    'bookings',
    'emergency_contacts'
];

echo "<h3>Table Existence Check:</h3>";
foreach ($required_tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        echo "<p style='color: green;'>✓ Table '$table' exists</p>";
    } else {
        echo "<p style='color: red;'>✗ Table '$table' does not exist</p>";
    }
}

// Test table structures
echo "<h3>Table Structure Check:</h3>";

// Check users table
$result = $conn->query("DESCRIBE users");
if ($result) {
    echo "<p style='color: green;'>✓ Users table structure is accessible</p>";
    echo "<h4>Users table columns:</h4>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>{$row['Field']} - {$row['Type']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>✗ Cannot access users table structure</p>";
}

// Check ambulances table
$result = $conn->query("DESCRIBE ambulances");
if ($result) {
    echo "<p style='color: green;'>✓ Ambulances table structure is accessible</p>";
    echo "<h4>Ambulances table columns:</h4>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>{$row['Field']} - {$row['Type']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>✗ Cannot access ambulances table structure</p>";
}

// Check drivers table
$result = $conn->query("DESCRIBE drivers");
if ($result) {
    echo "<p style='color: green;'>✓ Drivers table structure is accessible</p>";
    echo "<h4>Drivers table columns:</h4>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>{$row['Field']} - {$row['Type']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>✗ Cannot access drivers table structure</p>";
}

// Check bookings table
$result = $conn->query("DESCRIBE bookings");
if ($result) {
    echo "<p style='color: green;'>✓ Bookings table structure is accessible</p>";
    echo "<h4>Bookings table columns:</h4>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>{$row['Field']} - {$row['Type']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>✗ Cannot access bookings table structure</p>";
}

// Check emergency_contacts table
$result = $conn->query("DESCRIBE emergency_contacts");
if ($result) {
    echo "<p style='color: green;'>✓ Emergency_contacts table structure is accessible</p>";
    echo "<h4>Emergency_contacts table columns:</h4>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>{$row['Field']} - {$row['Type']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>✗ Cannot access emergency_contacts table structure</p>";
}

// Test sample data
echo "<h3>Sample Data Check:</h3>";

// Check users
$result = $conn->query("SELECT COUNT(*) as count FROM users");
if ($result) {
    $count = $result->fetch_assoc()['count'];
    echo "<p style='color: green;'>✓ Users table has $count records</p>";
} else {
    echo "<p style='color: red;'>✗ Cannot count users</p>";
}

// Check ambulances
$result = $conn->query("SELECT COUNT(*) as count FROM ambulances");
if ($result) {
    $count = $result->fetch_assoc()['count'];
    echo "<p style='color: green;'>✓ Ambulances table has $count records</p>";
} else {
    echo "<p style='color: red;'>✗ Cannot count ambulances</p>";
}

// Check drivers
$result = $conn->query("SELECT COUNT(*) as count FROM drivers");
if ($result) {
    $count = $result->fetch_assoc()['count'];
    echo "<p style='color: green;'>✓ Drivers table has $count records</p>";
} else {
    echo "<p style='color: red;'>✗ Cannot count drivers</p>";
}

// Check bookings
$result = $conn->query("SELECT COUNT(*) as count FROM bookings");
if ($result) {
    $count = $result->fetch_assoc()['count'];
    echo "<p style='color: green;'>✓ Bookings table has $count records</p>";
} else {
    echo "<p style='color: red;'>✗ Cannot count bookings</p>";
}

// Check emergency_contacts
$result = $conn->query("SELECT COUNT(*) as count FROM emergency_contacts");
if ($result) {
    $count = $result->fetch_assoc()['count'];
    echo "<p style='color: green;'>✓ Emergency_contacts table has $count records</p>";
} else {
    echo "<p style='color: red;'>✗ Cannot count emergency_contacts</p>";
}

// Test related data queries
echo "<h3>Related Data Test:</h3>";

// Test bookings with related data
$result = $conn->query("SELECT b.*, u.fullname as user_name, a.vehicle_number, p.fullname as provider_name 
                       FROM bookings b 
                       JOIN users u ON b.user_id = u.id 
                       LEFT JOIN ambulances a ON b.ambulance_id = a.id 
                       LEFT JOIN users p ON a.provider_id = p.id 
                       ORDER BY b.created_at DESC");

if ($result && $result->num_rows > 0) {
    echo "<p style='color: green;'>✓ Related data query successful - Found {$result->num_rows} bookings with related data</p>";
    echo "<h4>Sample booking data with relations:</h4>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>User</th><th>Pickup</th><th>Destination</th><th>Provider</th><th>Ambulance</th><th>Status</th></tr>";
    
    $count = 0;
    while ($row = $result->fetch_assoc() && $count < 5) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['pickup_location']) . "</td>";
        echo "<td>" . htmlspecialchars($row['destination']) . "</td>";
        echo "<td>" . htmlspecialchars($row['provider_name'] ?? 'Not assigned') . "</td>";
        echo "<td>" . htmlspecialchars($row['vehicle_number'] ?? 'Not assigned') . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "</tr>";
        $count++;
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>✗ Related data query failed or no data found</p>";
}

$conn->close();
?> 