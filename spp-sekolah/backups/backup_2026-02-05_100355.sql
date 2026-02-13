-- Database Backup
-- Generated: 2026-02-05 10:03:55
-- Database: spp_sekolah

SET FOREIGN_KEY_CHECKS = 0;

-- Table: guru
DROP TABLE IF EXISTS `guru`;
CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_guru` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `level` enum('admin','guru') NOT NULL DEFAULT 'guru',
  `foto` varchar(255) DEFAULT 'default.png',
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_guru`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `guru` VALUES("1","admin","$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi","Administrator","admin@sekolah.sch.id","&amp;amp;amp;am","admin","default.png","active","2026-02-05 07:23:42","2026-02-03 10:31:39","2026-02-05 07:23:42");
INSERT INTO `guru` VALUES("3","guru_1770094368321","$2y$10$952cbNtntoPGJ8nJV2cLtu0X9jauO2NKVdE3ZOcqcQg0r.0yhTbPO","Guru Test 1770094368321","guru1770094368321@test.com","08123456789","guru","default.png","active",NULL,"2026-02-03 11:52:49","2026-02-03 11:52:49");
INSERT INTO `guru` VALUES("4","guru_1770094402500","$2y$10$6GPr3gW8p0LX1Gl20QyV.uE2PvDcC0TzpKGvw7yMFR4vE7IbObQnq","Guru Test 1770094402500","guru1770094402500@test.com","08123456789","guru","default.png","active",NULL,"2026-02-03 11:53:23","2026-02-03 11:53:23");
INSERT INTO `guru` VALUES("5","guru_1770094459031","$2y$10$C8Vqd3J5oFgw5FttMIyAVuekWOk/F.A3YWighc/ZwCG2QT4zA1PeG","Guru Test 1770094459031","guru1770094459031@test.com","08123456789","guru","default.png","active",NULL,"2026-02-03 11:54:19","2026-02-03 11:54:19");
INSERT INTO `guru` VALUES("8","kasmi","$2y$10$Pa1M8h0V7DGiOFOjVRYZou19xXx7WAoq.bgzO//w1keAcvudRW70i","daskd","dashu@gmial.com","87979675765","guru","default.png","active","2026-02-03 19:37:01","2026-02-03 11:57:07","2026-02-03 19:37:01");

-- Table: kelas
DROP TABLE IF EXISTS `kelas`;
CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(20) NOT NULL,
  `id_tahun` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_kelas`),
  KEY `id_tahun` (`id_tahun`),
  CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`id_tahun`) REFERENCES `tahun_ajaran` (`id_tahun`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel data kelas untuk santri';

INSERT INTO `kelas` VALUES("1","Kelas 1770095875319",NULL,"2026-02-03 12:17:55");
INSERT INTO `kelas` VALUES("2","Xi",NULL,"2026-02-03 12:21:09");
INSERT INTO `kelas` VALUES("3","A1",NULL,"2026-02-03 17:44:25");

-- Table: log_aktivitas
DROP TABLE IF EXISTS `log_aktivitas`;
CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_guru` int(11) DEFAULT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `tabel` varchar(50) DEFAULT NULL,
  `data_id` varchar(50) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_log`),
  KEY `log_aktivitas_ibfk_1` (`id_guru`),
  CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `log_aktivitas` VALUES("1","1","Logout dari sistem","petugas","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 10:33:15");
INSERT INTO `log_aktivitas` VALUES("2","1","Login ke sistem","petugas","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 10:33:17");
INSERT INTO `log_aktivitas` VALUES("3","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:28:23");
INSERT INTO `log_aktivitas` VALUES("4","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:28:29");
INSERT INTO `log_aktivitas` VALUES("5","1","Mengubah profil","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:28:30");
INSERT INTO `log_aktivitas` VALUES("6","1","Mengubah profil","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:28:35");
INSERT INTO `log_aktivitas` VALUES("7","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:29:59");
INSERT INTO `log_aktivitas` VALUES("8","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:30:00");
INSERT INTO `log_aktivitas` VALUES("9","1","Mengubah profil","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:30:01");
INSERT INTO `log_aktivitas` VALUES("10","1","Mengubah profil","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:30:04");
INSERT INTO `log_aktivitas` VALUES("11","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:32:16");
INSERT INTO `log_aktivitas` VALUES("12","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:32:17");
INSERT INTO `log_aktivitas` VALUES("13","1","Mengubah profil","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:32:18");
INSERT INTO `log_aktivitas` VALUES("14","1","Mengubah profil","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:32:22");
INSERT INTO `log_aktivitas` VALUES("15","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:33:18");
INSERT INTO `log_aktivitas` VALUES("16","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:33:20");
INSERT INTO `log_aktivitas` VALUES("17","1","Mengubah profil","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:33:20");
INSERT INTO `log_aktivitas` VALUES("18","1","Mengubah profil","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:33:24");
INSERT INTO `log_aktivitas` VALUES("19","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:33:36");
INSERT INTO `log_aktivitas` VALUES("20","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:33:40");
INSERT INTO `log_aktivitas` VALUES("21","1","Mengubah profil","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:33:40");
INSERT INTO `log_aktivitas` VALUES("22","1","Mengubah profil","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:33:44");
INSERT INTO `log_aktivitas` VALUES("23","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 11:38:34");
INSERT INTO `log_aktivitas` VALUES("24","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:45:09");
INSERT INTO `log_aktivitas` VALUES("25","1","Menghapus data guru","guru","2","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 11:47:03");
INSERT INTO `log_aktivitas` VALUES("26","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:52:47");
INSERT INTO `log_aktivitas` VALUES("27","1","Menyimpan data guru (ditambahkan)","guru","3","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:52:49");
INSERT INTO `log_aktivitas` VALUES("28","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:52:57");
INSERT INTO `log_aktivitas` VALUES("29","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:53:22");
INSERT INTO `log_aktivitas` VALUES("30","1","Menyimpan data guru (ditambahkan)","guru","4","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:53:23");
INSERT INTO `log_aktivitas` VALUES("31","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:54:18");
INSERT INTO `log_aktivitas` VALUES("32","1","Menyimpan data guru (ditambahkan)","guru","5","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:54:19");
INSERT INTO `log_aktivitas` VALUES("33","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:55:29");
INSERT INTO `log_aktivitas` VALUES("34","1","Menyimpan data guru (ditambahkan)","guru","6","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:55:31");
INSERT INTO `log_aktivitas` VALUES("35","1","Menyimpan data guru (diubah)","guru","6","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:55:35");
INSERT INTO `log_aktivitas` VALUES("36","1","Menghapus data guru","guru","6","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:55:40");
INSERT INTO `log_aktivitas` VALUES("37","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:55:56");
INSERT INTO `log_aktivitas` VALUES("38","1","Menyimpan data guru (ditambahkan)","guru","7","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:55:57");
INSERT INTO `log_aktivitas` VALUES("39","1","Menyimpan data guru (diubah)","guru","7","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:56:04");
INSERT INTO `log_aktivitas` VALUES("40","1","Menghapus data guru","guru","7","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:56:08");
INSERT INTO `log_aktivitas` VALUES("41","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 11:56:12");
INSERT INTO `log_aktivitas` VALUES("42","1","Menyimpan data guru (ditambahkan)","guru","8","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 11:57:07");
INSERT INTO `log_aktivitas` VALUES("43","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:14:04");
INSERT INTO `log_aktivitas` VALUES("44","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:15:13");
INSERT INTO `log_aktivitas` VALUES("45","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:16:10");
INSERT INTO `log_aktivitas` VALUES("46","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:16:53");
INSERT INTO `log_aktivitas` VALUES("47","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:17:29");
INSERT INTO `log_aktivitas` VALUES("48","1","Menyimpan data SPP (ditambahkan)","spp","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:17:30");
INSERT INTO `log_aktivitas` VALUES("49","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:17:54");
INSERT INTO `log_aktivitas` VALUES("50","1","Menyimpan data kelas (ditambahkan)","kelas","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:17:55");
INSERT INTO `log_aktivitas` VALUES("51","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:17:57");
INSERT INTO `log_aktivitas` VALUES("52","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:18:23");
INSERT INTO `log_aktivitas` VALUES("53","1","Menambah data santri baru","santri","1770095904","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:18:24");
INSERT INTO `log_aktivitas` VALUES("54","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:19:32");
INSERT INTO `log_aktivitas` VALUES("55","1","Menambah data santri baru","santri","1770095975","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:19:35");
INSERT INTO `log_aktivitas` VALUES("56","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:20:22");
INSERT INTO `log_aktivitas` VALUES("57","1","Menambah data santri baru","santri","1770096022","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:20:23");
INSERT INTO `log_aktivitas` VALUES("58","1","Menyimpan data kelas (ditambahkan)","kelas","2","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 12:21:09");
INSERT INTO `log_aktivitas` VALUES("59","1","Mengubah pengaturan payment gateway","settings","payment","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 12:24:23");
INSERT INTO `log_aktivitas` VALUES("60","1","Mengubah pengaturan payment gateway","settings","payment","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 12:24:50");
INSERT INTO `log_aktivitas` VALUES("61","1","Export laporan pembayaran","laporan","Februari 2026","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 12:26:01");
INSERT INTO `log_aktivitas` VALUES("62","1","Export laporan pembayaran","laporan","Februari 2026","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 12:27:49");
INSERT INTO `log_aktivitas` VALUES("63","1","Input pembayaran SPP","pembayaran","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 12:28:35");
INSERT INTO `log_aktivitas` VALUES("64","1","Export laporan pembayaran","laporan","Februari 2026","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 12:28:49");
INSERT INTO `log_aktivitas` VALUES("65","1","Export laporan pembayaran","laporan","Februari 2026","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 12:30:34");
INSERT INTO `log_aktivitas` VALUES("66","1","Export laporan pembayaran","laporan","Februari 2026","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 12:30:42");
INSERT INTO `log_aktivitas` VALUES("67","1","Export laporan pembayaran","laporan","Februari 2026","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 12:31:33");
INSERT INTO `log_aktivitas` VALUES("68","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:43:40");
INSERT INTO `log_aktivitas` VALUES("69","1","Menambah data santri baru","santri","1770097420","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 12:43:42");
INSERT INTO `log_aktivitas` VALUES("70","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 16:09:28");
INSERT INTO `log_aktivitas` VALUES("71","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 16:43:49");
INSERT INTO `log_aktivitas` VALUES("72","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 16:45:27");
INSERT INTO `log_aktivitas` VALUES("73","1","Menambah data santri baru","santri","1770111928","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 16:45:30");
INSERT INTO `log_aktivitas` VALUES("74","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 17:01:20");
INSERT INTO `log_aktivitas` VALUES("75","1","Menambah data santri baru","santri","1770112882","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 17:01:23");
INSERT INTO `log_aktivitas` VALUES("76","1","Menambah data santri baru","santri","34324","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 17:09:34");
INSERT INTO `log_aktivitas` VALUES("77","1","Menyimpan data kelas (ditambahkan)","kelas","3","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 17:44:25");
INSERT INTO `log_aktivitas` VALUES("78","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:00:32");
INSERT INTO `log_aktivitas` VALUES("79","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:01:39");
INSERT INTO `log_aktivitas` VALUES("80","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:03:42");
INSERT INTO `log_aktivitas` VALUES("81","1","Input pembayaran SPP","pembayaran","2","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:03:43");
INSERT INTO `log_aktivitas` VALUES("82","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:05:19");
INSERT INTO `log_aktivitas` VALUES("83","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:06:55");
INSERT INTO `log_aktivitas` VALUES("84","1","Menambah data santri baru","santri","1770120416","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:06:57");
INSERT INTO `log_aktivitas` VALUES("85","1","Input pembayaran SPP","pembayaran","3","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:06:59");
INSERT INTO `log_aktivitas` VALUES("86","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:07:38");
INSERT INTO `log_aktivitas` VALUES("87","1","Menambah data santri baru","santri","1770120459","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:07:39");
INSERT INTO `log_aktivitas` VALUES("88","1","Input pembayaran SPP","pembayaran","4","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:07:41");
INSERT INTO `log_aktivitas` VALUES("89","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:08:21");
INSERT INTO `log_aktivitas` VALUES("90","1","Menambah data santri baru","santri","1770120502","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:08:22");
INSERT INTO `log_aktivitas` VALUES("91","1","Input pembayaran SPP","pembayaran","5","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:08:23");
INSERT INTO `log_aktivitas` VALUES("92","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:09:21");
INSERT INTO `log_aktivitas` VALUES("93","1","Menambah data santri baru","santri","1770120561","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:09:22");
INSERT INTO `log_aktivitas` VALUES("94","1","Input pembayaran SPP","pembayaran","6","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:09:23");
INSERT INTO `log_aktivitas` VALUES("95","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:10:30");
INSERT INTO `log_aktivitas` VALUES("96","1","Menambah data santri baru","santri","1770120631","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:10:31");
INSERT INTO `log_aktivitas` VALUES("97","1","Input pembayaran SPP","pembayaran","7","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:10:33");
INSERT INTO `log_aktivitas` VALUES("98","1","Export laporan pembayaran","laporan","Februari 2026","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 19:12:29");
INSERT INTO `log_aktivitas` VALUES("99","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:12:34");
INSERT INTO `log_aktivitas` VALUES("100","1","Menambah data santri baru","santri","1770120755","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:12:35");
INSERT INTO `log_aktivitas` VALUES("101","1","Input pembayaran SPP","pembayaran","8","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36","2026-02-03 19:12:36");
INSERT INTO `log_aktivitas` VALUES("102","1","Input pembayaran SPP","pembayaran","9","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 19:16:42");
INSERT INTO `log_aktivitas` VALUES("103","1","Export laporan pembayaran","laporan","Februari 2026","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 19:35:50");
INSERT INTO `log_aktivitas` VALUES("104","1","Menyimpan data guru (diubah)","guru","8","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 19:36:54");
INSERT INTO `log_aktivitas` VALUES("105","1","Logout dari sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 19:36:58");
INSERT INTO `log_aktivitas` VALUES("106","8","Login ke sistem","guru","8","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 19:37:01");
INSERT INTO `log_aktivitas` VALUES("107","8","Input pembayaran SPP","pembayaran","10","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 19:37:17");
INSERT INTO `log_aktivitas` VALUES("108","8","Export laporan pembayaran","laporan","Februari 2026","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 19:38:25");
INSERT INTO `log_aktivitas` VALUES("109","8","Logout dari sistem","guru","8","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 19:39:04");
INSERT INTO `log_aktivitas` VALUES("110","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 19:39:09");
INSERT INTO `log_aktivitas` VALUES("111","1","Mengubah pengaturan umum","settings","general","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 19:43:15");
INSERT INTO `log_aktivitas` VALUES("112","1","Mengubah pengaturan umum","settings","general","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 19:44:34");
INSERT INTO `log_aktivitas` VALUES("113","1","Mengubah pengaturan payment gateway","settings","payment","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 19:45:22");
INSERT INTO `log_aktivitas` VALUES("114","1","Mengubah pengaturan payment gateway","settings","payment","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-03 19:45:40");
INSERT INTO `log_aktivitas` VALUES("115","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-05 07:23:42");
INSERT INTO `log_aktivitas` VALUES("116","1","Export laporan pembayaran","laporan","Februari 2026","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-05 07:23:52");

-- Table: pembayaran
DROP TABLE IF EXISTS `pembayaran`;
CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT,
  `id_guru` int(11) NOT NULL,
  `nisn` varchar(10) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `bulan_dibayar` varchar(20) NOT NULL,
  `tahun_dibayar` varchar(4) NOT NULL,
  `id_spp` int(11) NOT NULL,
  `jumlah_bayar` decimal(15,2) NOT NULL,
  `metode_bayar` enum('tunai','transfer','online') DEFAULT 'tunai',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_pembayaran`),
  KEY `id_spp` (`id_spp`),
  KEY `pembayaran_ibfk_2` (`nisn`),
  KEY `pembayaran_ibfk_1` (`id_guru`),
  CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`),
  CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`nisn`) REFERENCES `santri` (`nisn`),
  CONSTRAINT `pembayaran_ibfk_3` FOREIGN KEY (`id_spp`) REFERENCES `spp` (`id_spp`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pembayaran` VALUES("1","1","1770095975","2026-02-03","Maret","2026","1","100000.00","tunai","","2026-02-03 12:28:35");
INSERT INTO `pembayaran` VALUES("2","1","34324","2026-02-03","Februari","2026","1","100000.00","tunai","","2026-02-03 19:03:43");
INSERT INTO `pembayaran` VALUES("3","1","1770120416","2026-02-03","Februari","2026","1","100000.00","tunai","","2026-02-03 19:06:59");
INSERT INTO `pembayaran` VALUES("4","1","1770120459","2026-02-03","Februari","2026","1","100000.00","tunai","","2026-02-03 19:07:40");
INSERT INTO `pembayaran` VALUES("5","1","1770120502","2026-02-03","Februari","2026","1","100000.00","tunai","","2026-02-03 19:08:23");
INSERT INTO `pembayaran` VALUES("6","1","1770120561","2026-02-03","Februari","2026","1","100000.00","tunai","","2026-02-03 19:09:23");
INSERT INTO `pembayaran` VALUES("7","1","1770120631","2026-02-03","Februari","2026","1","100000.00","tunai","","2026-02-03 19:10:33");
INSERT INTO `pembayaran` VALUES("8","1","1770120755","2026-02-03","Februari","2026","1","100000.00","tunai","","2026-02-03 19:12:36");
INSERT INTO `pembayaran` VALUES("9","1","1770120561","2026-02-03","Mei","2026","1","100000.00","tunai","","2026-02-03 19:16:42");
INSERT INTO `pembayaran` VALUES("10","8","34324","2026-02-03","Januari","2026","1","100000.00","tunai","","2026-02-03 19:37:17");

-- Table: santri
DROP TABLE IF EXISTS `santri`;
CREATE TABLE `santri` (
  `nisn` varchar(10) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nspp` varchar(30) DEFAULT NULL,
  `satuan_pendidikan` varchar(50) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `alamat` text DEFAULT NULL,
  `nama_wali` varchar(100) DEFAULT NULL,
  `telp_wali` varchar(15) DEFAULT NULL,
  `id_spp` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT 'default.png',
  `status` enum('active','inactive','alumni') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`nisn`),
  UNIQUE KEY `nis` (`nik`),
  KEY `id_kelas` (`id_kelas`),
  KEY `id_spp` (`id_spp`),
  CONSTRAINT `santri_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`),
  CONSTRAINT `santri_ibfk_2` FOREIGN KEY (`id_spp`) REFERENCES `spp` (`id_spp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `santri` VALUES("1770095904","NIS1770095904153",NULL,NULL,"Santri Test 1770095904153",NULL,NULL,"L","1","Jl. Test No. 123","Wali 1770095904153","081987654321","1","default.png","active","2026-02-03 12:18:24","2026-02-03 12:18:24");
INSERT INTO `santri` VALUES("1770095975","NIS1770095975184",NULL,NULL,"Santri Test 1770095975184",NULL,NULL,"L","1","Jl. Test No. 123","Wali 1770095975184","081987654321","1","default.png","active","2026-02-03 12:19:35","2026-02-03 12:19:35");
INSERT INTO `santri` VALUES("1770096022","NIS1770096022913",NULL,NULL,"Santri Test 1770096022913",NULL,NULL,"L","1","Jl. Test No. 123","Wali 1770096022913","081987654321","1","default.png","active","2026-02-03 12:20:23","2026-02-03 12:20:23");
INSERT INTO `santri` VALUES("1770097420","NIS1770097420904",NULL,NULL,"Santri Test 1770097420904",NULL,NULL,"L","1","Jl. Test No. 123","Wali 1770097420904","081987654321","1","default.png","active","2026-02-03 12:43:42","2026-02-03 12:43:42");
INSERT INTO `santri` VALUES("1770111928","3201770111928256",NULL,NULL,"Santri Test 1770111928256","Jakarta","2010-01-01","L","1","Jl. Test No. 123","Wali 1770111928256","081987654321","1","default.png","active","2026-02-03 16:45:30","2026-02-03 16:45:30");
INSERT INTO `santri` VALUES("1770112882","3201770112882101","1234567890","SDIT Test","Santri Test 1770112882101","Jakarta","2010-01-01","L","1","Jl. Test No. 123","Wali 1770112882101","081987654321","1","default.png","active","2026-02-03 17:01:23","2026-02-03 17:01:23");
INSERT INTO `santri` VALUES("1770120416","1770120416357","","","Santri Test 1770120416357","","0000-00-00","L","3","Address","Wali","","1","default.png","active","2026-02-03 19:06:57","2026-02-03 19:06:57");
INSERT INTO `santri` VALUES("1770120459","1770120459449","","","Santri Test 1770120459449","","0000-00-00","L","3","Address","Wali","","1","default.png","active","2026-02-03 19:07:39","2026-02-03 19:07:39");
INSERT INTO `santri` VALUES("1770120502","1770120502634","","","Santri Test 1770120502634","","0000-00-00","L","3","Address","Wali","","1","default.png","active","2026-02-03 19:08:22","2026-02-03 19:08:22");
INSERT INTO `santri` VALUES("1770120561","1770120561828","","","Santri Test 1770120561828","","0000-00-00","L","3","Address","Wali","","1","default.png","active","2026-02-03 19:09:22","2026-02-03 19:09:22");
INSERT INTO `santri` VALUES("1770120631","1770120631634","","","Santri Test 1770120631634","","0000-00-00","L","3","Address","Wali","","1","default.png","active","2026-02-03 19:10:31","2026-02-03 19:10:31");
INSERT INTO `santri` VALUES("1770120755","1770120755708","","","Santri Test 1770120755708","","0000-00-00","L","3","Address","Wali","","1","default.png","active","2026-02-03 19:12:35","2026-02-03 19:12:35");
INSERT INTO `santri` VALUES("34324","23121","08796576","tpq","dasman","peor","2020-10-01","L","1","sdafgda","dsadsad","1342525","1","default.png","active","2026-02-03 17:09:34","2026-02-03 17:09:34");

-- Table: settings
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','password','email','number','boolean','json') DEFAULT 'text',
  `setting_group` varchar(50) DEFAULT 'general',
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `settings` VALUES("1","nama_sekolah","TPQ Al-Ikhlas Metro Parung","text","general","Nama sekolah","2026-02-03 10:31:39","2026-02-03 19:43:15");
INSERT INTO `settings` VALUES("2","alamat_sekolah","PERUM METRO PARUNG, BLOK A5, Rt. 02, Rw. 07","text","general","Alamat sekolah","2026-02-03 10:31:39","2026-02-03 19:44:34");
INSERT INTO `settings` VALUES("3","telp_sekolah","021-12345678","text","general","Telepon sekolah","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("4","email_sekolah","info@smkn1contoh.sch.id","email","general","Email sekolah","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("5","website_sekolah","https://smkn1contoh.sch.id","text","general","Website sekolah","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("6","logo_sekolah","logo.png","text","general","Logo sekolah","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("7","smtp_host","smtp.gmail.com","text","email","SMTP Host","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("8","smtp_port","587","number","email","SMTP Port","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("9","smtp_username","","email","email","SMTP Username/Email","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("10","smtp_password","","password","email","SMTP Password/App Password","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("11","smtp_encryption","tls","text","email","SMTP Encryption (tls/ssl)","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("12","email_from_name","Sistem SPP Sekolah","text","email","Nama pengirim email","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("13","wa_api_provider","fonnte","text","whatsapp","Provider API WhatsApp (fonnte/wablas/custom)","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("14","wa_api_url","https://api.fonnte.com/send","text","whatsapp","URL API WhatsApp","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("15","wa_api_token","","password","whatsapp","Token API WhatsApp","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("16","wa_sender","","text","whatsapp","Nomor pengirim WhatsApp","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("17","payment_enabled","0","boolean","payment","Enable online payment","2026-02-03 10:31:39","2026-02-03 12:24:50");
INSERT INTO `settings` VALUES("18","midtrans_server_key","password","password","payment","Midtrans Server Key","2026-02-03 10:31:39","2026-02-03 12:24:23");
INSERT INTO `settings` VALUES("19","midtrans_client_key","","text","payment","Midtrans Client Key","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("20","midtrans_is_production","0","boolean","payment","Midtrans Production Mode","2026-02-03 10:31:39","2026-02-03 19:45:40");
INSERT INTO `settings` VALUES("21","notif_email_enabled","0","boolean","notification","Enable email notifications","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("22","notif_wa_enabled","0","boolean","notification","Enable WhatsApp notifications","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("23","notif_payment_template","Pembayaran SPP untuk {nama} bulan {bulan} sebesar Rp {jumlah} telah diterima. Terima kasih.","text","notification","Template notifikasi pembayaran","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("24","auto_backup_enabled","0","boolean","backup","Enable auto backup","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("25","backup_interval","weekly","text","backup","Backup interval (daily/weekly/monthly)","2026-02-03 10:31:39","2026-02-03 10:31:39");
INSERT INTO `settings` VALUES("26","backup_keep_days","30","number","backup","Jumlah hari backup disimpan","2026-02-03 10:31:39","2026-02-03 10:31:39");

-- Table: spp
DROP TABLE IF EXISTS `spp`;
CREATE TABLE `spp` (
  `id_spp` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` int(4) NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_spp`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `spp` VALUES("1","2026","100000.00","","2026-02-03 12:17:30");

-- Table: tahun_ajaran
DROP TABLE IF EXISTS `tahun_ajaran`;
CREATE TABLE `tahun_ajaran` (
  `id_tahun` int(11) NOT NULL AUTO_INCREMENT,
  `tahun_ajar` varchar(9) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL DEFAULT 'Tidak Aktif',
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_tahun`),
  UNIQUE KEY `tahun_ajar` (`tahun_ajar`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


SET FOREIGN_KEY_CHECKS = 1;
