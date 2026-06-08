CREATE DATABASE IF NOT EXISTS bus_ticket_system;
USE bus_ticket_system;

-- 1. Table to store available bus trips
CREATE TABLE IF NOT EXISTS buses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bus_number VARCHAR(50) NOT NULL UNIQUE,
    route_from VARCHAR(100) NOT NULL,
    route_to VARCHAR(100) NOT NULL,
    departure_time DATETIME NOT NULL,
    total_seats INT NOT NULL,
    available_seats INT NOT NULL,
    price_tzs DECIMAL(10, 2) NOT NULL
) ENGINE=InnoDB;

-- 2. Table to store ticket bookings
CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bus_id INT NOT NULL,
    passenger_name VARCHAR(150) NOT NULL,
    passenger_phone VARCHAR(20) NOT NULL,
    booked_seat_no INT NOT NULL,
    booking_status VARCHAR(20) DEFAULT 'active', -- Custom 'Soft Delete' state placeholder
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bus_id) REFERENCES buses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Insert seed data representing typical routes
INSERT INTO buses (bus_number, route_from, route_to, departure_time, total_seats, available_seats, price_tzs) 
VALUES 
('BM-01', 'Morogoro (Mzumbe)', 'Dar es Salaam', '2026-06-15 07:00:00', 50, 50, 15000.00),
('BM-02', 'Morogoro (Mzumbe)', 'Dodoma', '2026-06-15 09:30:00', 45, 45, 18000.00);