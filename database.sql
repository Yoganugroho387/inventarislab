-- Database Schema for Campus Laboratory Inventory System
-- Create database if not exists and use it
CREATE DATABASE IF NOT EXISTS inventarislab;
USE inventarislab;

-- Disable foreign key checks to make recreations easier
SET FOREIGN_KEY_CHECKS = 0;

-- Drop tables if they exist
DROP TABLE IF EXISTS settings;
DROP TABLE IF EXISTS activity_logs;
DROP TABLE IF EXISTS borrowing_details;
DROP TABLE IF EXISTS borrowings;
DROP TABLE IF EXISTS items;
DROP TABLE IF EXISTS units;
DROP TABLE IF EXISTS locations;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;

SET FOREIGN_KEY_CHECKS = 1;

-- 1. Roles Table
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Categories Table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Locations Table
CREATE TABLE locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Units Table
CREATE TABLE units (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Items Table
CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    category_id INT NOT NULL,
    location_id INT NOT NULL,
    unit_id INT NOT NULL,
    item_type ENUM('inventaris', 'habis_pakai') NOT NULL,
    condition_status ENUM('tersedia', 'rusak', 'maintenance', 'habis') DEFAULT 'tersedia',
    stock INT DEFAULT 0,
    minimum_stock INT DEFAULT 0,
    procurement_year YEAR DEFAULT NULL,
    funding_source VARCHAR(100) DEFAULT NULL,
    acquisition_price DECIMAL(15,2) DEFAULT 0.00,
    item_status ENUM('tersedia', 'dipinjam', 'rusak', 'maintenance', 'habis') DEFAULT 'tersedia',
    description TEXT DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE RESTRICT,
    FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Borrowings Table
CREATE TABLE borrowings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    borrower_name VARCHAR(100) NOT NULL,
    borrower_identity VARCHAR(50) NOT NULL,
    borrower_phone VARCHAR(20) DEFAULT NULL,
    borrower_type ENUM('mahasiswa', 'dosen', 'staf', 'umum') NOT NULL,
    loan_date DATE NOT NULL,
    due_date DATE NOT NULL,
    status ENUM('menunggu', 'disetujui', 'ditolak', 'dipinjam', 'dikembalikan', 'terlambat') DEFAULT 'menunggu',
    notes TEXT DEFAULT NULL,
    created_by INT NOT NULL,
    approved_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Borrowing Details Table
CREATE TABLE borrowing_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    borrowing_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    return_date DATE DEFAULT NULL,
    return_condition ENUM('baik', 'rusak', 'hilang') DEFAULT NULL,
    return_notes TEXT DEFAULT NULL,
    FOREIGN KEY (borrowing_id) REFERENCES borrowings(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Activity Logs Table
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Settings Table
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ==========================================
-- SEED DATA
-- ==========================================

-- Insert Roles
INSERT INTO roles (id, name) VALUES 
(1, 'admin'),
(2, 'staff');

-- Insert Default Users (Passwords: admin123 and staff123)
INSERT INTO users (id, name, username, password, role_id, is_active) VALUES
(1, 'Administrator Lab', 'admin', '$2y$10$.TSA45hciRmpPo4GJqYBtO8mfaOvrK.x7GVGBnWPhjwBcuiAmLwf6', 1, 1),
(2, 'Laboran Petugas', 'staff', '$2y$10$CsCgoQFmZzNiHzp99zxr1eYFPLawdCQSwaLOYKJkvajnGunoh5UwS', 2, 1);

-- Insert Default Categories
INSERT INTO categories (id, name) VALUES
(1, 'Peralatan Elektronik'),
(2, 'Alat Ukur'),
(3, 'Bahan Kimia & Kaca'),
(4, 'Komponen Habis Pakai');

-- Insert Default Locations
INSERT INTO locations (id, name) VALUES
(1, 'Lab Jaringan & Komputer'),
(2, 'Lab Sistem Tertanam & IoT'),
(3, 'Ruang Penyimpanan Alat'),
(4, 'Lab Kimia Dasar');

-- Insert Default Units
INSERT INTO units (id, name) VALUES
(1, 'Unit / Pcs'),
(2, 'Box'),
(3, 'Liter'),
(4, 'Roll'),
(5, 'Meter');

-- Insert Default Settings
INSERT INTO settings (setting_key, setting_value) VALUES
('app_name', 'Sistem Inventaris Lab'),
('lab_name', 'Laboratorium Komputer & Teknik Elektro'),
('lab_head', 'Dr. Ir. Budi Santoso, M.T.'),
('lab_address', 'Gedung C Lantai 2, Kampus Terpadu'),
('qr_prefix', 'LAB-INF-');

-- Insert Initial Items for demonstration (mix of assets & consumables)
INSERT INTO items (code, name, category_id, location_id, unit_id, item_type, condition_status, stock, minimum_stock, procurement_year, funding_source, acquisition_price, item_status, description) VALUES
('LAB-INF-001', 'PC Server Dell PowerEdge T440', 1, 1, 1, 'inventaris', 'tersedia', 2, 1, 2023, 'Dana Hibah Universitas', 25000000.00, 'tersedia', 'Server utama untuk virtualisasi lab dan database lokal.'),
('LAB-INF-002', 'Oscilloscope Rigol DS1202Z-E', 2, 2, 1, 'inventaris', 'tersedia', 5, 2, 2022, 'Rektorat Kampus', 6500000.00, 'tersedia', 'Alat ukur gelombang listrik untuk praktikum mikroprosesor.'),
('LAB-INF-003', 'Kabel LAN Cat6 Belden', 4, 3, 4, 'habis_pakai', 'tersedia', 10, 3, 2024, 'Operasional Lab', 1200000.00, 'tersedia', 'Kabel UTP Cat6 untuk kebutuhan crimping & jaringan.'),
('LAB-INF-004', 'Tinta Printer Epson L3110 Black', 4, 3, 1, 'habis_pakai', 'tersedia', 15, 5, 2024, 'Operasional Lab', 115000.00, 'tersedia', 'Tinta hitam habis pakai printer administrasi lab.'),
('LAB-INF-005', 'Arduino Uno R3 Starter Kit', 1, 2, 1, 'inventaris', 'tersedia', 20, 5, 2023, 'Fakultas Teknik', 250000.00, 'tersedia', 'Kit board mikrokontroler Arduino Uno untuk praktikum IoT.');

-- ==========================================
-- ADDED IN V2 (NEW FEATURES)
-- ==========================================

-- 11. Item Disbursements (Consumables Outflow)
CREATE TABLE IF NOT EXISTS item_disbursements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    receiver_name VARCHAR(100) NOT NULL,
    receiver_identity VARCHAR(50) NOT NULL,
    purpose TEXT DEFAULT NULL,
    given_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE RESTRICT,
    FOREIGN KEY (given_by) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Maintenance Logs
CREATE TABLE IF NOT EXISTS maintenance_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE DEFAULT NULL,
    cost DECIMAL(15,2) DEFAULT 0.00,
    vendor_name VARCHAR(150) DEFAULT NULL,
    issue_description TEXT NOT NULL,
    repair_action TEXT DEFAULT NULL,
    status ENUM('proses', 'selesai', 'tidak_bisa_diperbaiki') DEFAULT 'proses',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- WhatsApp Settings Seeding
INSERT INTO settings (setting_key, setting_value) VALUES 
('whatsapp_token', ''),
('whatsapp_enabled', '0'),
('institution_name', 'Politeknik Negeri Jakarta'),
('institution_logo', '')
ON DUPLICATE KEY UPDATE setting_key=setting_key;


