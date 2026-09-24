-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 24, 2026 at 07:40 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pkl`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_akademik`
--

CREATE TABLE `tbl_akademik` (
  `kode_akd` varchar(10) NOT NULL,
  `semester` enum('GN','GL') NOT NULL,
  `tahun` char(4) NOT NULL,
  `is_active` enum('1','0') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_akademik`
--

INSERT INTO `tbl_akademik` (`kode_akd`, `semester`, `tahun`, `is_active`) VALUES
('1', 'GL', '2023', '1'),
('2', 'GN', '2024', '1'),
('3', 'GL', '2021', '1'),
('4', 'GN', '2022', '1'),
('5', 'GL', '2025', '1'),
('6', 'GN', '2020', '1'),
('8', 'GN', '2026', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dosen`
--

CREATE TABLE `tbl_dosen` (
  `nik` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kontak` varchar(13) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `jenis_kelamin` char(1) DEFAULT NULL,
  `img` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_dosen`
--

INSERT INTO `tbl_dosen` (`nik`, `nama`, `kontak`, `email`, `jenis_kelamin`, `img`) VALUES
('123', 'sorikhi, M.kom', '08643232415', 'sorikhi@gmail.com', 'L', '../asetweb/img/foto-dosen1787564362.png'),
('1234', 'khurotul aeni, M.kom', '08645242434', 'aeni@gmail.com', 'P', '../asetweb/img/foto-dosen1787561981.jfif'),
('12345', 'Nurul mega saraswati, M.kom', '08765232344', 'mega@gmail.com', 'P', '../asetweb/img/foto-dosen1787561989.jfif'),
('123456', 'fatulloh M.kom', '08543276265', 'fatulloh@gmail.com', 'L', '../asetweb/img/foto-dosen1787554616.png'),
('1234567', 'tezhar rayendra M.kom', '08754243425', 'tezhar@gmail.com', 'L', '../asetweb/img/foto-dosen1787562015.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jurusan`
--

CREATE TABLE `tbl_jurusan` (
  `kode_jurusan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_jurusan`
--

INSERT INTO `tbl_jurusan` (`kode_jurusan`, `nama_jurusan`) VALUES
('JR001', 'Manajemen'),
('JR002', 'akuntansi'),
('JR003', 'pendidikan guru sekolah dasar'),
('JR004', 'Informatika'),
('JR005', 'agribisnis'),
('JR006', 'sistem informasi');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kelas_matkul`
--

CREATE TABLE `tbl_kelas_matkul` (
  `kode_kelas` int NOT NULL,
  `kode_akd` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `kode_matkul` varchar(10) NOT NULL,
  `kode_jurusan` varchar(50) NOT NULL,
  `nik` varchar(10) NOT NULL,
  `nama_kelas` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_kelas_matkul`
--

INSERT INTO `tbl_kelas_matkul` (`kode_kelas`, `kode_akd`, `kode_matkul`, `kode_jurusan`, `nik`, `nama_kelas`) VALUES
(1, '', '', '', '', ''),
(5, '2', 'MK007', 'JR003', '12345', '11.5'),
(6, '2', 'MK001', 'JR001', '123', '12.3'),
(7, '1', 'MK001', 'JR001', '123', '12.7'),
(15, '4', 'MK003', 'JR003', '123456', '10 2'),
(16, '1', 'MK003', 'JR004', '1234', '12.5'),
(17, '2', 'MK009', 'JR004', '123', '12.5');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mahasiswa`
--

CREATE TABLE `tbl_mahasiswa` (
  `nim` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kontak` varchar(13) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `jenis_kelamin` char(1) DEFAULT NULL,
  `img` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_mahasiswa`
--

INSERT INTO `tbl_mahasiswa` (`nim`, `nama`, `kontak`, `email`, `jenis_kelamin`, `img`) VALUES
('42423011', 'aurelia', '08643232415', 'aurelia@gmail.com', 'P', '../asetweb/img/foto-mhs1787564260.jfif'),
('42423013', 'NAJWA SABHIRA', '909834364327', 'najwa@gmail.com', 'P', '../asetweb/img/foto-mhs1787561578.jfif'),
('42423025', 'jendral', '08743543245', 'jenral@gmail.com', 'L', '../asetweb/img/foto-mhs1787546946.png'),
('42423042', 'TENA ERFIANA', '08543276267', 'tena@gmail.com', 'P', ''),
('42423043', 'alfi resti zelia', '0864352465', 'alfi@gmail.com', 'P', '../asetweb/img/foto-mhs1787554419.jfif'),
('42423045', 'SASI MAELANI', '08543276265', 'sasi@gmail.com', 'P', '../asetweb/img/foto-mhs1787561554.jfif'),
('42423051', 'adil kusuma', '0875424325', 'adil@gmail.com', 'L', '../asetweb/img/foto-mhs1787554706.png'),
('424233030', 'sabiru', '0862345214', 'biru@gmail.com', 'L', '../asetweb/img/foto-mhs1787546980.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mapel`
--

CREATE TABLE `tbl_mapel` (
  `id` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_matkul`
--

CREATE TABLE `tbl_matkul` (
  `kode_matkul` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama_matkul` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `jml_sks` int NOT NULL,
  `jml_cpmk` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_matkul`
--

INSERT INTO `tbl_matkul` (`kode_matkul`, `nama_matkul`, `jml_sks`, `jml_cpmk`) VALUES
('MK001', 'komputer kuantum', 2, 4),
('MK002', 'matematika', 2, 4),
('MK003', 'Data Mining', 2, 4),
('MK004', 'tata kelola TI', 2, 2),
('MK007', 'Analsisi big data', 3, 4),
('MK008', 'pemrograman tersetuktur', 3, 2),
('MK009', 'komputer', 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pengguna`
--

CREATE TABLE `tbl_pengguna` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `sandi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `peran` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pin` int NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_pengguna`
--

INSERT INTO `tbl_pengguna` (`id`, `username`, `sandi`, `peran`, `pin`, `nama`) VALUES
(3, 'adminprodi', '0d2ba6209fe18b56eb9bea6e06bd902ca6de183e', 'A', 345, 'matien hakim f.b,s.kom'),
(137, '42423043', '7c52826b740c1a2b53248e4befc56861b7c50b1c', 'M', 1234, 'alfi resti zelia'),
(139, '42423013', '0688df815d1deebf8290987641a4eaceee6c865e', 'M', 1234, 'najwa shabira'),
(140, '424233030', '3719042c9ec3e64452e4d3f1591cd19c5c29b7eb', 'M', 1234, 'sabiru'),
(141, '42423025', 'cd55db213a36e7ed4c048cc20107500e34da25f4', 'M', 1234, 'jendral'),
(145, '123', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'D', 1234, 'sorikhi, M.kom'),
(146, '1234', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'D', 1234, 'khurotul aeni, M.kom'),
(147, '12345', '8cb2237d0679ca88db6464eac60da96345513964', 'D', 1234, 'Nurul mega saraswati, M.kom'),
(148, '1234567809', 'd45f2643f60b90e7628c1cccac8a8fbd55a587de', 'D', 696969, 'ALFI RESTI ZELIA'),
(149, '42423045', '740bd8bb00bb55dee72e5c5c35882ccb4718b6f9', 'M', 123456, 'SASI MAELANI'),
(150, '123456', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'D', 696969, 'fatulloh M.kom'),
(151, '42423051', '1040f34976650b7220f7d4b19b78c4300c4ac5dd', 'M', 123456, 'adil kusuma'),
(152, '42423011', 'a539657d43427e91ad7446f5450e55b5379ea4a6', 'M', 123456, 'aurelia'),
(153, '1234567', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'D', 696969, 'tezhar rayendra M.kom'),
(154, '42423042', 'c7e92618fcec045a0261caf1d0cff75b16ba050e', 'M', 123456, 'TENA ERFIANA');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pertemuan`
--

CREATE TABLE `tbl_pertemuan` (
  `id_pertemuan` int NOT NULL,
  `kode_kelas` varchar(10) NOT NULL,
  `tgl` date NOT NULL,
  `judul_pertemuan` varchar(255) NOT NULL,
  `status` char(1) NOT NULL,
  `pertemuan_ke` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_pertemuan`
--

INSERT INTO `tbl_pertemuan` (`id_pertemuan`, `kode_kelas`, `tgl`, `judul_pertemuan`, `status`, `pertemuan_ke`) VALUES
(1, '1', '2026-09-02', 'belajar komputer kuantum', '1', 1),
(2, '16', '2026-09-03', 'belajar komputer ', '1', 2),
(4, '16', '2026-09-05', 'belajar bedah jurnal', '1', 3),
(5, '7', '2026-09-11', 'detail computer vision', '0', 2),
(7, '7', '2026-09-03', 'memahami kuantitatif dan kualitatif', '0', 3),
(8, '7', '2026-09-04', 'belajar kode', '1', 4),
(9, '7', '2026-09-01', 'belajar machine learning', '0', 5),
(10, '16', '2026-09-05', 'comvis', '1', 4),
(11, '16', '2026-09-12', 'metopen', '1', 5),
(12, '16', '2026-09-23', 'computer', '0', 6),
(13, '7', '2026-09-07', 'belajar', '0', 6);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_peserta`
--

CREATE TABLE `tbl_peserta` (
  `id_peserta` int NOT NULL,
  `kode_kelas` int NOT NULL,
  `kode_matkul` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nim` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_peserta`
--

INSERT INTO `tbl_peserta` (`id_peserta`, `kode_kelas`, `kode_matkul`, `nim`) VALUES
(1, 0, '7', '42423013'),
(2, 0, '7', '42423013'),
(3, 0, '7', '42423011'),
(4, 0, 'MK001', '42423011'),
(5, 0, '', '42423043'),
(6, 0, '7', '42423013'),
(7, 0, '7', '42423042'),
(8, 0, '7', '42423013'),
(9, 0, '7', '42423013'),
(10, 0, '7', '42423013'),
(11, 0, '7', '42423013'),
(12, 0, '7', '42423013'),
(13, 0, '7', '42423013'),
(14, 0, '7', '42423051'),
(15, 0, '7', '42423051'),
(16, 0, '7', '424233030'),
(17, 0, '7', '42423045'),
(18, 0, '7', '424233030'),
(19, 7, 'MK001', '42423013'),
(21, 7, 'MK001', '42423042'),
(22, 7, 'MK001', '424233030'),
(23, 7, 'MK001', '42423051'),
(24, 16, 'MK003', '42423011'),
(25, 16, 'MK003', '42423051'),
(27, 7, 'MK001', '42423043'),
(28, 7, 'MK001', '42423025');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_presensi`
--

CREATE TABLE `tbl_presensi` (
  `id_presensi` int NOT NULL,
  `id_pertemuan` int NOT NULL,
  `nim` varchar(10) NOT NULL,
  `status_kehadiran` enum('hadir','alfa','izin','sakit') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_presensi`
--

INSERT INTO `tbl_presensi` (`id_presensi`, `id_pertemuan`, `nim`, `status_kehadiran`) VALUES
(1, 9, '', 'sakit'),
(2, 9, '', 'izin'),
(3, 9, '', 'hadir'),
(4, 9, '', 'sakit'),
(5, 9, '', 'hadir'),
(6, 9, '', 'hadir'),
(7, 9, '', 'hadir'),
(8, 9, '', 'izin'),
(9, 9, '', 'hadir'),
(10, 9, '', 'izin'),
(11, 9, '42423042', 'hadir'),
(12, 9, '42423013', 'izin'),
(13, 9, '424233030', 'sakit'),
(14, 12, '42423011', 'hadir'),
(15, 13, '42423013', 'hadir'),
(16, 13, '42423042', 'hadir'),
(17, 13, '424233030', 'izin'),
(18, 13, '42423025', 'hadir'),
(19, 5, '42423013', 'izin'),
(20, 5, '42423025', 'hadir'),
(21, 5, '42423042', 'hadir'),
(22, 5, '424233030', 'hadir'),
(23, 5, '42423051', 'sakit'),
(24, 7, '42423013', 'hadir'),
(25, 7, '42423025', 'hadir'),
(26, 7, '42423042', 'hadir'),
(27, 7, '424233030', 'izin'),
(28, 7, '42423051', 'izin'),
(29, 8, '42423013', 'hadir'),
(30, 8, '42423025', 'hadir'),
(31, 8, '42423042', 'hadir'),
(32, 8, '424233030', 'hadir'),
(33, 8, '42423051', 'hadir'),
(34, 7, '42423043', 'hadir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_akademik`
--
ALTER TABLE `tbl_akademik`
  ADD PRIMARY KEY (`kode_akd`);

--
-- Indexes for table `tbl_dosen`
--
ALTER TABLE `tbl_dosen`
  ADD PRIMARY KEY (`nik`);

--
-- Indexes for table `tbl_jurusan`
--
ALTER TABLE `tbl_jurusan`
  ADD PRIMARY KEY (`kode_jurusan`);

--
-- Indexes for table `tbl_kelas_matkul`
--
ALTER TABLE `tbl_kelas_matkul`
  ADD PRIMARY KEY (`kode_kelas`);

--
-- Indexes for table `tbl_mahasiswa`
--
ALTER TABLE `tbl_mahasiswa`
  ADD PRIMARY KEY (`nim`);

--
-- Indexes for table `tbl_mapel`
--
ALTER TABLE `tbl_mapel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_matkul`
--
ALTER TABLE `tbl_matkul`
  ADD PRIMARY KEY (`kode_matkul`);

--
-- Indexes for table `tbl_pengguna`
--
ALTER TABLE `tbl_pengguna`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_pertemuan`
--
ALTER TABLE `tbl_pertemuan`
  ADD PRIMARY KEY (`id_pertemuan`);

--
-- Indexes for table `tbl_peserta`
--
ALTER TABLE `tbl_peserta`
  ADD PRIMARY KEY (`id_peserta`);

--
-- Indexes for table `tbl_presensi`
--
ALTER TABLE `tbl_presensi`
  ADD PRIMARY KEY (`id_presensi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_kelas_matkul`
--
ALTER TABLE `tbl_kelas_matkul`
  MODIFY `kode_kelas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_pengguna`
--
ALTER TABLE `tbl_pengguna`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `tbl_pertemuan`
--
ALTER TABLE `tbl_pertemuan`
  MODIFY `id_pertemuan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_peserta`
--
ALTER TABLE `tbl_peserta`
  MODIFY `id_peserta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tbl_presensi`
--
ALTER TABLE `tbl_presensi`
  MODIFY `id_presensi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
