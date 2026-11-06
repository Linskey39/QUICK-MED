-- database.sql: Create users table for ambulance system
CREATE DATABASE IF NOT EXISTS ambulance;
USE ambulance;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    account_type ENUM('user', 'provider', 'admin') NOT NULL,
    password VARCHAR(255) NOT NULL,
    otp VARCHAR(6) DEFAULT NULL,
    otp_timestamp TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE,
    is_approved BOOLEAN DEFAULT TRUE,
    rejection_reason TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Ambulances table
CREATE TABLE IF NOT EXISTS ambulances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_id INT NOT NULL,
    vehicle_number VARCHAR(20) NOT NULL,
    vehicle_type VARCHAR(50) NOT NULL,
    status ENUM('available', 'busy', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (provider_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Drivers table
CREATE TABLE IF NOT EXISTS drivers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_id INT NOT NULL,
    ambulance_id INT,
    fullname VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    license_number VARCHAR(50) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (provider_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (ambulance_id) REFERENCES ambulances(id) ON DELETE SET NULL
);

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    ambulance_id INT,
    pickup_location VARCHAR(255) NOT NULL,
    destination VARCHAR(255) NOT NULL,
    booking_type ENUM('emergency', 'non-emergency') NOT NULL,
    status ENUM('pending', 'accepted', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    amount DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (ambulance_id) REFERENCES ambulances(id) ON DELETE SET NULL
);

-- Emergency contacts table
CREATE TABLE IF NOT EXISTS emergency_contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    relationship VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Saved locations table
CREATE TABLE IF NOT EXISTS saved_locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    location_type ENUM('pickup', 'destination') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Service areas table
CREATE TABLE IF NOT EXISTS service_areas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_id INT NOT NULL,
    area_name VARCHAR(100) NOT NULL,
    description TEXT,
    radius_km DECIMAL(5,2) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (provider_id) REFERENCES users(id) ON DELETE CASCADE
);

-- System settings table
CREATE TABLE IF NOT EXISTS system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample users for testing
INSERT INTO users (fullname, email, phone, account_type, password) VALUES
('Admin User', 'sinn68990@gmail.com'  , '0700000000', 'admin', '$2y$10$A9goIFHw6kCY.nhDhW22Y.aqb6QQfKwDyvg9Q8yLO6o0N88bRr9Zm'),
('Regular User', 'user@ambuconnect.ac.ke', '0700000001', 'user', '$2y$10$A9goIFHw6kCY.nhDhW22Y.aqb6QQfKwDyvg9Q8yLO6o0N88bRr9Zm'),
('Provider User', 'provider@ambuconnect.ac.ke', '0700000002', 'provider', '$2y$10$A9goIFHw6kCY.nhDhW22Y.aqb6QQfKwDyvg9Q8yLO6o0N88bRr9Zm');

-- Insert sample data for testing
INSERT INTO ambulances (provider_id, vehicle_number, vehicle_type, status) VALUES
(3, 'AMB001', 'Basic Ambulance', 'available'),
(3, 'AMB002', 'Advanced Life Support', 'available');

INSERT INTO drivers (provider_id, ambulance_id, fullname, phone, license_number) VALUES
(3, 1, 'John Driver', '0712345678', 'DL001'),
(3, 2, 'Jane Driver', '0787654321', 'DL002');

INSERT INTO bookings (user_id, ambulance_id, pickup_location, destination, booking_type, status, amount) VALUES
(2, 1, 'Nairobi CBD', 'Kenyatta Hospital', 'emergency', 'completed', 5000.00),
(2, 2, 'Westlands', 'Aga Khan Hospital', 'non-emergency', 'pending', 3000.00);

INSERT INTO emergency_contacts (user_id, name, phone, relationship) VALUES
(2, 'Emergency Contact 1', '0798765432', 'Family'),
(2, 'Emergency Contact 2', '0765432109', 'Friend');

-- Insert default system settings
INSERT INTO system_settings (setting_key, setting_value, setting_type, description) VALUES
('emergency_price', '5000', 'number', 'Default emergency booking price in KSh'),
('non_emergency_price', '3000', 'number', 'Default non-emergency booking price in KSh'),
('commission_rate', '10', 'number', 'Platform commission rate in percentage'),
('email_notifications', '1', 'boolean', 'Enable email notifications'),
('sms_notifications', '1', 'boolean', 'Enable SMS notifications'),
('admin_notifications', '1', 'boolean', 'Enable admin notifications for new bookings'),
('provider_notifications', '1', 'boolean', 'Enable provider notifications for new bookings'),
('maintenance_mode', '0', 'boolean', 'Enable maintenance mode'),
('auto_approve_providers', '0', 'boolean', 'Auto-approve new provider registrations'),
('enable_rating_system', '1', 'boolean', 'Enable rating system'),
('max_booking_distance', '50', 'number', 'Maximum booking distance in km'),
('session_timeout', '30', 'number', 'Session timeout in minutes'),
('emergency_response_time', '10', 'number', 'Emergency response time in minutes');

-- Add indexes for better performance
CREATE INDEX idx_saved_locations_user_id ON saved_locations(user_id);
CREATE INDEX idx_service_areas_provider_id ON service_areas(provider_id);
CREATE INDEX idx_service_areas_active ON service_areas(is_active);
CREATE INDEX idx_users_approved ON users(is_approved);
CREATE INDEX idx_users_active ON users(is_active);

