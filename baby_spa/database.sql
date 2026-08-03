-- ==========================================
-- SQL DATABASE DUMP FOR BABY SPA WEBSITE
-- Database Name: baby_spa_db
-- Import directly in phpMyAdmin
-- ==========================================

CREATE DATABASE IF NOT EXISTS `baby_spa_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `baby_spa_db`;

-- 1. Table users (Admin)
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `fullname` VARCHAR(100) NOT NULL,
  `role` VARCHAR(20) DEFAULT 'admin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default Admin User: admin / admin123
INSERT INTO `users` (`username`, `password`, `fullname`, `role`) VALUES
('admin', '$2y$10$w3b1fB91N2/mS4Jq1T.O9.Yt1U6pM5N/gX6rW5s2Q1h7L3j8k9m0C', 'Administrator Baby Spa', 'admin');

-- 2. Table services
DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `duration` VARCHAR(30) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `description` TEXT NOT NULL,
  `image` VARCHAR(255) DEFAULT 'hero.jpg',
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `services` (`name`, `category`, `duration`, `price`, `description`, `image`) VALUES
('Baby Hydrotherapy (Renang Bayi)', 'Pijat & Renang', '30 Menit', 120000.00, 'Terapi berenang di kolam air hangat dengan filter khusus steril untuk menstimulasi motorik bayi dan nafsu makan.', 'hero.jpg'),
('Baby Organic Massage (Pijat Bayi)', 'Pijat & Renang', '45 Menit', 135000.00, 'Pijat relaksasi menggunakan minyak organik pilihan dari terapis bersertifikat untuk kualitas tidur optimal.', 'massage.jpg'),
('Mom & Baby Bonding Package', 'Paket Hemat', '75 Menit', 230000.00, 'Kombinasi Hydrotherapy + Organic Massage untuk bayi plus massage relaksasi punggung hangat untuk Ibu.', 'massage.jpg'),
('Baby Bubble Bath Fun', 'Perawatan Khusus', '30 Menit', 95000.00, 'Sensasi mandi busa organik aman untuk kulit bayi sensitif lengkap dengan mainan sensori air.', 'bubble.jpg'),
('Kids Haircut & Styling', 'Grooming', '30 Menit', 75000.00, 'Cukur rambut bayi/balita dengan kapstok ramah anak, alat steril, dan terapis berpengalaman sabar.', 'hero.jpg'),
('Baby Gym & Motorik Training', 'Sensori & Fit', '45 Menit', 110000.00, 'Latihan fisik ringan & permainan sensori edukatif untuk melatih otot dan daya ingat si kecil.', 'hero.jpg');

-- 3. Table reservations
DROP TABLE IF EXISTS `reservations`;
CREATE TABLE `reservations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_code` VARCHAR(20) NOT NULL UNIQUE,
  `parent_name` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `baby_name` VARCHAR(100) NOT NULL,
  `baby_age_months` INT NOT NULL,
  `service_id` INT NOT NULL,
  `reservation_date` DATE NOT NULL,
  `reservation_time` TIME NOT NULL,
  `therapist_preference` VARCHAR(50) DEFAULT 'Sesuai Antrean',
  `notes` TEXT DEFAULT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `status` ENUM('Pending', 'Dikonfirmasi', 'Selesai', 'Dibatalkan') DEFAULT 'Pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Table testimonials
DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE `testimonials` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `parent_name` VARCHAR(100) NOT NULL,
  `baby_name` VARCHAR(100) NOT NULL,
  `rating` INT NOT NULL DEFAULT 5,
  `comment` TEXT NOT NULL,
  `is_featured` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `testimonials` (`parent_name`, `baby_name`, `rating`, `comment`) VALUES
('Bunda Anisa', 'Dek Kenzo (6 Bulan)', 5, 'Pelayanan ramah banget! Kenzo tidurnya jadi nyenyak banget sesudah diajak Hydrotherapy & Pijat di sini. Tempatnya bersih dan hangat!'),
('Mama Clarissa', 'Baby Rayyan (4 Bulan)', 5, 'Terapisnya sangat profesional & telaten melayani bayi yang rewel. Rekomended banget buat pasangan muda yang mau bikin bayi rileks.'),
('Bunda Rina', 'Dek Alma (9 Bulan)', 5, 'Paket Mom & Baby recommended banget. Bayi seneng berenang, bundanya bisa dapet massage punggung santai. Wajib langganan!');
