CREATE DATABASE IF NOT EXISTS chikondicare CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE chikondicare;

CREATE TABLE roles (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(40) NOT NULL UNIQUE);
CREATE TABLE permissions (id INT AUTO_INCREMENT PRIMARY KEY, code VARCHAR(80) NOT NULL UNIQUE, description VARCHAR(180));
CREATE TABLE role_permissions (role_id INT NOT NULL, permission_id INT NOT NULL, PRIMARY KEY(role_id,permission_id), FOREIGN KEY(role_id) REFERENCES roles(id), FOREIGN KEY(permission_id) REFERENCES permissions(id));
CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(60) NOT NULL UNIQUE, full_name VARCHAR(120) NOT NULL,
 password_hash VARCHAR(255) NOT NULL, role_id INT NOT NULL, is_active TINYINT(1) NOT NULL DEFAULT 1,
 must_change_password TINYINT(1) NOT NULL DEFAULT 1, failed_attempts INT NOT NULL DEFAULT 0,
 locked_until BIGINT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(role_id) REFERENCES roles(id)
);
CREATE TABLE doctors (
 id INT AUTO_INCREMENT PRIMARY KEY, doctor_number VARCHAR(30) NOT NULL UNIQUE, name VARCHAR(120) NOT NULL,
 telephone VARCHAR(25) NOT NULL, room_number VARCHAR(30) NOT NULL, employment_type ENUM('full_time','visiting') NOT NULL,
 next_visit_date DATE NULL, next_visit_duration INT NULL, is_active TINYINT(1) NOT NULL DEFAULT 1
);
CREATE TABLE patients (
 id INT AUTO_INCREMENT PRIMARY KEY, patient_number VARCHAR(30) NOT NULL UNIQUE, full_name VARCHAR(150) NOT NULL,
 date_of_birth DATE NOT NULL, gender ENUM('Male','Female','Other') NOT NULL, address VARCHAR(255) NOT NULL,
 telephone VARCHAR(25) NOT NULL, next_of_kin VARCHAR(150) NULL, primary_doctor_id INT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(primary_doctor_id) REFERENCES doctors(id)
);
CREATE TABLE appointments (
 id INT AUTO_INCREMENT PRIMARY KEY, patient_id INT NOT NULL, doctor_id INT NOT NULL,
 appointment_date DATE NOT NULL, appointment_time TIME NOT NULL,
 status ENUM('booked','cancelled','checked_in','completed') NOT NULL DEFAULT 'booked',
 booked_by INT NOT NULL, checked_in_at DATETIME NULL, cancellation_reason VARCHAR(255) NULL,
 cancelled_at DATETIME NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY unique_active_slot (doctor_id,appointment_date,appointment_time,status),
 FOREIGN KEY(patient_id) REFERENCES patients(id), FOREIGN KEY(doctor_id) REFERENCES doctors(id),
 FOREIGN KEY(booked_by) REFERENCES users(id)
);
CREATE TABLE consultations (
 id INT AUTO_INCREMENT PRIMARY KEY, appointment_id INT NOT NULL UNIQUE, patient_id INT NOT NULL,
 doctor_id INT NOT NULL, diagnosis TEXT NOT NULL, notes TEXT NULL, consulted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(appointment_id) REFERENCES appointments(id), FOREIGN KEY(patient_id) REFERENCES patients(id), FOREIGN KEY(doctor_id) REFERENCES doctors(id)
);
CREATE TABLE medicines (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(150) NOT NULL UNIQUE, description TEXT NULL, is_active TINYINT(1) DEFAULT 1);
CREATE TABLE prescriptions (
 id INT AUTO_INCREMENT PRIMARY KEY, consultation_id INT NOT NULL, medicine_id INT NOT NULL,
 dosage VARCHAR(100) NOT NULL, frequency VARCHAR(100) NOT NULL, duration VARCHAR(100) NOT NULL,
 FOREIGN KEY(consultation_id) REFERENCES consultations(id), FOREIGN KEY(medicine_id) REFERENCES medicines(id)
);
CREATE TABLE payments (
 id INT AUTO_INCREMENT PRIMARY KEY, receipt_number VARCHAR(40) NOT NULL UNIQUE, patient_id INT NOT NULL,
 consultation_id INT NOT NULL, amount DECIMAL(12,2) NOT NULL, payment_method ENUM('Cash','Mobile Money','Bank') NOT NULL,
 paid_at DATETIME DEFAULT CURRENT_TIMESTAMP, received_by INT NOT NULL,
 FOREIGN KEY(patient_id) REFERENCES patients(id), FOREIGN KEY(consultation_id) REFERENCES consultations(id), FOREIGN KEY(received_by) REFERENCES users(id)
);
CREATE TABLE settings (id INT AUTO_INCREMENT PRIMARY KEY, setting_key VARCHAR(80) NOT NULL UNIQUE, setting_value VARCHAR(255) NOT NULL);
CREATE TABLE audit_logs (id BIGINT AUTO_INCREMENT PRIMARY KEY, user_id INT NULL, action VARCHAR(60) NOT NULL, details TEXT, ip_address VARCHAR(45), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL);

INSERT INTO roles(name) VALUES ('Administrator'),('Receptionist'),('Health Care Attendant'),('Doctor');
INSERT INTO permissions(code,description) VALUES
('dashboard.view','View dashboard'),('staff.manage','Manage staff'),('doctors.manage','Manage doctors'),
('patients.view','View patients'),('patients.manage','Register and update patients'),
('appointments.manage','Book and cancel appointments'),('checkin.manage','Check in patients'),
('consultations.manage','Record consultations and prescriptions'),('payments.manage','Record payments'),
('reports.view','View reports'),('audit.view','View audit logs');

INSERT INTO role_permissions(role_id,permission_id)
SELECT r.id,p.id FROM roles r CROSS JOIN permissions p
WHERE (r.name='Administrator')
OR (r.name='Receptionist' AND p.code IN ('dashboard.view','patients.view','appointments.manage','reports.view'))
OR (r.name='Health Care Attendant' AND p.code IN ('dashboard.view','patients.view','patients.manage','checkin.manage','payments.manage'))
OR (r.name='Doctor' AND p.code IN ('dashboard.view','patients.view','consultations.manage','reports.view'));
INSERT INTO settings(setting_key,setting_value) VALUES ('consultation_fee','10000.00');
