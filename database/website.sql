CREATE DATABASE sekolah;
USE sekolah;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'redaksi') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE konten (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    konten TEXT NOT NULL,
    kategori ENUM('berita', 'artikel', 'kegiatan') NOT NULL,
    gambar VARCHAR(255),
    penulis VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    gambar VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample users
INSERT INTO users (username, password, role) VALUES
('admin', password('admin123'), 'admin'),
('redaksi', password('redaksi123'), 'redaksi');

-- Insert sample content
INSERT INTO konten (judul, konten, kategori, gambar, penulis) VALUES
('Penerimaan Siswa Baru 2024', 'Sekolah kami membuka pendaftaran siswa baru untuk tahun ajaran 2024/2025...', 'berita', 'berita1.jpg', 'Admin'),
('Prestasi Olimpiade Matematika', 'Tim olimpiade matematika sekolah kami berhasil meraih juara 1 tingkat provinsi...', 'berita', 'berita2.jpg', 'Admin'),
('Renovasi Laboratorium Komputer', 'Laboratorium komputer sekolah telah direnovasi dengan peralatan terbaru...', 'berita', 'berita3.jpg', 'Admin'),
('Program Beasiswa Prestasi', 'Sekolah menyediakan program beasiswa untuk siswa berprestasi...', 'berita', 'berita4.jpg', 'Admin'),
('Kerjasama dengan Universitas', 'Sekolah menjalin kerjasama dengan universitas ternama untuk program pengembangan...', 'berita', 'berita5.jpg', 'Admin'),
('Tips Belajar Efektif', 'Belajar efektif membutuhkan strategi yang tepat. Berikut adalah beberapa tips...', 'artikel', 'artikel1.jpg', 'Redaksi'),
('Manfaat Ekstrakurikuler', 'Ekstrakurikuler memiliki banyak manfaat untuk pengembangan siswa...', 'artikel', 'artikel2.jpg', 'Redaksi'),
('Pentingnya Literasi Digital', 'Di era digital, literasi digital menjadi skill yang sangat penting...', 'artikel', 'artikel3.jpg', 'Redaksi'),
('Strategi Menghadapi Ujian', 'Menghadapi ujian membutuhkan persiapan yang matang dan strategi yang tepat...', 'artikel', 'artikel4.jpg', 'Redaksi'),
('Pengembangan Karakter Siswa', 'Pendidikan karakter merupakan bagian penting dari kurikulum sekolah...', 'artikel', 'artikel5.jpg', 'Redaksi'),
('Festival Seni Sekolah', 'Festival seni tahunan sekolah berlangsung meriah dengan berbagai penampilan...', 'kegiatan', 'kegiatan1.jpg', 'Redaksi'),
('Outbound Kelas 12', 'Kelas 12 mengikuti kegiatan outbound untuk mempererat kekompakan...', 'kegiatan', 'kegiatan2.jpg', 'Redaksi'),
('Seminar Nasional Pendidikan', 'Sekolah mengadakan seminar nasional tentang inovasi pendidikan...', 'kegiatan', 'kegiatan3.jpg', 'Redaksi'),
('Bakti Sosial', 'Siswa dan guru mengadakan bakti sosial di panti asuhan...', 'kegiatan', 'kegiatan4.jpg', 'Redaksi'),
('Lomba Debat Antar Kelas', 'Lomba debat antar kelas meningkatkan kemampuan berpikir kritis siswa...', 'kegiatan', 'kegiatan5.jpg', 'Redaksi');

-- Insert sample gallery
INSERT INTO galeri (judul, gambar, deskripsi) VALUES
('Gedung Sekolah', 'gedung1.jpg', 'Tampak depan gedung sekolah yang megah'),
('Laboratorium IPA', 'lab1.jpg', 'Laboratorium IPA yang lengkap dengan peralatan modern'),
('Perpustakaan', 'perpus1.jpg', 'Perpustakaan dengan koleksi buku yang lengkap'),
('Lapangan Olahraga', 'lapangan1.jpg', 'Lapangan olahraga yang luas dan nyaman'),
('Ruang Kelas', 'kelas1.jpg', 'Ruang kelas yang nyaman untuk belajar'),
('Aula Serbaguna', 'aula1.jpg', 'Aula serbaguna untuk berbagai kegiatan'),
('Kantin Sekolah', 'kantin1.jpg', 'Kantin sekolah yang bersih dan nyaman'),
('Taman Sekolah', 'taman1.jpg', 'Taman sekolah yang asri dan hijau'),
('Guru dan Siswa', 'guru1.jpg', 'Kegiatan belajar mengajar yang interaktif'),
('Kegiatan Ekstrakurikuler', 'ekstra1.jpg', 'Berbagai kegiatan ekstrakurikuler menarik');