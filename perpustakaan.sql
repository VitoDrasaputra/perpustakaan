-- Database: perpustakaan
-- Buat database dulu sebelum import file ini
-- CREATE DATABASE perpustakaan;
-- USE perpustakaan;

CREATE TABLE IF NOT EXISTS buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    pengarang VARCHAR(100) NOT NULL,
    penerbit VARCHAR(100) NOT NULL,
    tahun_terbit YEAR NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    cover VARCHAR(255) DEFAULT NULL,
    stok INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Data contoh buku
INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, kategori, stok) VALUES
('Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 'Novel', 5),
('Bumi Manusia', 'Pramoedya Ananta Toer', 'Hasta Mitra', 1980, 'Novel', 3),
('Filosofi Teras', 'Henry Manampiring', 'Kompas', 2018, 'Pengembangan Diri', 7),
('Sapiens', 'Yuval Noah Harari', 'Pustaka Alvabet', 2017, 'Sejarah', 4);

-- Akun admin default -> username: admin | password: admin123
-- SEGERA GANTI PASSWORD INI SETELAH LOGIN PERTAMA KALI
INSERT INTO users (username, password) VALUES
('admin', '$2b$10$gwrWewsdx0wODSJYMJM2xOspov02VZ4tzOdosJlr9L8vxkxl7kAQK');