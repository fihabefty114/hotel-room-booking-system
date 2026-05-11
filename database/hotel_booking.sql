CREATE DATABASE IF NOT EXISTS hotel_booking_system;
USE hotel_booking_system;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    nationality VARCHAR(50),
    id_number VARCHAR(50),
    role ENUM('guest', 'receptionist', 'housekeeping', 'admin') NOT NULL,
    profile_pic VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS room_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price_per_night DECIMAL(10,2) NOT NULL,
    max_capacity INT NOT NULL,
    thumbnail_path VARCHAR(255),
    amenities JSON
);

CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_type_id INT NOT NULL,
    room_number VARCHAR(20) NOT NULL UNIQUE,
    floor INT NOT NULL,
    status ENUM('available', 'occupied', 'dirty', 'maintenance', 'blocked') DEFAULT 'available',
    notes TEXT,
    FOREIGN KEY (room_type_id) REFERENCES room_types(id)
);

CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guest_id INT NOT NULL,
    room_id INT,
    room_type_id INT NOT NULL,
    checkin_date DATE NOT NULL,
    checkout_date DATE NOT NULL,
    num_guests INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled') DEFAULT 'pending',
    source ENUM('online', 'walk_in') DEFAULT 'online',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (guest_id) REFERENCES users(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    FOREIGN KEY (room_type_id) REFERENCES room_types(id)
);

CREATE TABLE IF NOT EXISTS billing (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    guest_id INT NOT NULL,
    base_amount DECIMAL(10,2) NOT NULL,
    extras_amount DECIMAL(10,2) DEFAULT 0,
    discount_amount DECIMAL(10,2) DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50),
    payment_status ENUM('pending', 'paid') DEFAULT 'pending',
    paid_at DATETIME,
    receipt_path VARCHAR(255),
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (guest_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS service_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    guest_id INT NOT NULL,
    room_id INT NOT NULL,
    service_type ENUM('extra_bed', 'toiletries', 'laundry', 'room_service', 'other') NOT NULL,
    description TEXT,
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (guest_id) REFERENCES users(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

CREATE TABLE IF NOT EXISTS housekeeping_tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT NOT NULL,
    assigned_to INT NOT NULL,
    task_type ENUM('cleaning', 'inspection', 'maintenance') NOT NULL,
    priority ENUM('normal', 'urgent') DEFAULT 'normal',
    status ENUM('pending', 'in_progress', 'done') DEFAULT 'pending',
    notes TEXT,
    scheduled_date DATE,
    completed_at DATETIME,
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS maintenance_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT NOT NULL,
    reported_by INT NOT NULL,
    description TEXT NOT NULL,
    severity ENUM('low', 'medium', 'high') DEFAULT 'low',
    status ENUM('open', 'in_progress', 'resolved') DEFAULT 'open',
    reported_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at DATETIME,
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    FOREIGN KEY (reported_by) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS seasonal_pricing (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_type_id INT NOT NULL,
    label VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    price_per_night DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (room_type_id) REFERENCES room_types(id)
);

CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    guest_id INT NOT NULL,
    overall_rating INT,
    cleanliness_rating INT,
    service_rating INT,
    review_text TEXT,
    admin_reply TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (guest_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS loyalty_points (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guest_id INT NOT NULL,
    booking_id INT,
    points_earned INT DEFAULT 0,
    points_used INT DEFAULT 0,
    balance INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (guest_id) REFERENCES users(id),
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);

INSERT INTO users
(name, email, password_hash, phone, nationality, id_number, role, profile_pic, is_active)
VALUES
('Admin User', 'admin@gmail.com', 'admin123', '01700000000', 'Bangladeshi', 'ADMIN001', 'admin', NULL, 1),
('Guest User', 'guest@gmail.com', 'guest123', '01800000000', 'Bangladeshi', 'GUEST001', 'guest', NULL, 1),
('Receptionist User', 'receptionist@gmail.com', 'recep123', '01900000000', 'Bangladeshi', 'REC001', 'receptionist', NULL, 1),
('Housekeeping User', 'housekeeping@gmail.com', 'house123', '01600000000', 'Bangladeshi', 'HK001', 'housekeeping', NULL, 1);

INSERT INTO room_types
(name, description, price_per_night, max_capacity, thumbnail_path, amenities)
VALUES
('Standard', 'Basic room with standard facilities', 3000.00, 2, NULL, '["WiFi", "AC", "TV"]'),
('Deluxe', 'Comfortable deluxe room with better facilities', 5000.00, 3, NULL, '["WiFi", "AC", "TV", "Mini Fridge"]'),
('Suite', 'Premium suite for family and luxury stay', 8000.00, 4, NULL, '["WiFi", "AC", "TV", "Mini Bar", "Bathtub"]');

INSERT INTO rooms
(room_type_id, room_number, floor, status, notes)
VALUES
(1, '101', 1, 'available', 'Standard room'),
(1, '102', 1, 'available', 'Standard room'),
(2, '201', 2, 'available', 'Deluxe room'),
(2, '202', 2, 'dirty', 'Needs cleaning'),
(3, '301', 3, 'available', 'Suite room');

INSERT INTO seasonal_pricing
(room_type_id, label, start_date, end_date, price_per_night)
VALUES
(1, 'Eid Holiday', '2026-03-20', '2026-03-30', 3800.00),
(2, 'Eid Holiday', '2026-03-20', '2026-03-30', 6200.00),
(3, 'Eid Holiday', '2026-03-20', '2026-03-30', 9500.00);