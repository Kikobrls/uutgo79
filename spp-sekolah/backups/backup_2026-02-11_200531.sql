-- Database Backup
-- Generated: 2026-02-11 20:05:31
-- Database: keuangan_tpq

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `guru` VALUES("1","admin","$2y$10$14SUbatq9oqXRSp4w8zPt.ZitBBRYTaz0CYQRpmprfXAXTu4tzS/.","Administrator","admin@sekolah.sch.id","081234567890","admin","default.png","active","2026-02-11 12:30:05","2026-02-05 14:31:13","2026-02-11 12:30:05");
INSERT INTO `guru` VALUES("2","calson","$2y$10$HcTxdGZrvxgpZdTnGXCtm.M9oC3umQPv.z17uwsElB8FBAjHHRqD.","hsadg","dnaihy@gmail.com","456776","guru","default.png","active","2026-02-07 19:22:57","2026-02-07 19:22:45","2026-02-07 19:22:57");
INSERT INTO `guru` VALUES("3","dasads","$2y$10$KT6Y7czmqaImhNZ.zuWUy.dGwnPZMkdSMp2JVySl4y4BLv83EHHcm","tr5tusdh","ydsfud@gmail.com","65765678","guru","default.png","active",NULL,"2026-02-07 20:38:29","2026-02-07 20:38:29");
INSERT INTO `guru` VALUES("4","dsuat79","$2y$10$0CFUZQbDKdeHkdI6DQd5s.Emcl84qHYyY2CYeio5UCnVTrjA1PDq2","kadjis","7y69o@gmial.com","678965t8y","guru","default.png","active",NULL,"2026-02-10 17:56:09","2026-02-10 17:56:09");

-- Table: kas
DROP TABLE IF EXISTS `kas`;
CREATE TABLE `kas` (
  `id_kas` int(11) NOT NULL AUTO_INCREMENT,
  `id_transaksi` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `saldo_sebelum` decimal(15,2) NOT NULL,
  `debit` decimal(15,2) DEFAULT 0.00,
  `kredit` decimal(15,2) DEFAULT 0.00,
  `saldo_sesudah` decimal(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_kas`),
  KEY `id_transaksi` (`id_transaksi`),
  CONSTRAINT `kas_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Table: kategori_keuangan
DROP TABLE IF EXISTS `kategori_keuangan`;
CREATE TABLE `kategori_keuangan` (
  `id_kategori` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kategori_keuangan` VALUES("2","Donasi","2026-02-07 08:42:43","2026-02-07 08:42:43");
INSERT INTO `kategori_keuangan` VALUES("3","Bantuan Operasional","2026-02-07 08:42:43","2026-02-07 08:42:43");
INSERT INTO `kategori_keuangan` VALUES("4","Lain-lain (Masuk)","2026-02-07 08:42:43","2026-02-07 08:42:43");
INSERT INTO `kategori_keuangan` VALUES("5","Gaji Guru","2026-02-07 08:42:43","2026-02-07 20:45:13");
INSERT INTO `kategori_keuangan` VALUES("6","Listrik & Air","2026-02-07 08:42:43","2026-02-07 20:45:13");
INSERT INTO `kategori_keuangan` VALUES("7","ATK","2026-02-07 08:42:43","2026-02-07 20:45:13");
INSERT INTO `kategori_keuangan` VALUES("9","Lain-lain (Keluar)","2026-02-07 08:42:43","2026-02-07 20:45:13");
INSERT INTO `kategori_keuangan` VALUES("11","Uang Kas","2026-02-07 10:34:13","2026-02-07 10:34:13");

-- Table: kelas
DROP TABLE IF EXISTS `kelas`;
CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_kelas`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kelas` VALUES("1","Kelas A","Kelas A - Pemula","active","2026-02-05 14:31:13");
INSERT INTO `kelas` VALUES("2","Kelas B1","Kelas B1 - Menengah","active","2026-02-05 14:31:13");
INSERT INTO `kelas` VALUES("3","Kelas B2","Kelas B2 - Menengah","active","2026-02-05 14:31:13");
INSERT INTO `kelas` VALUES("4","Kelas C","Kelas C - Lanjutan","active","2026-02-05 14:31:13");
INSERT INTO `kelas` VALUES("5","Kelas D","Kelas D - Tahfidz","active","2026-02-05 14:31:13");
INSERT INTO `kelas` VALUES("6","Kelas F","Kelas F - Tahfidz Lanjutan","active","2026-02-05 14:31:13");
INSERT INTO `kelas` VALUES("7","X RPL 1",NULL,"active","2026-02-10 18:54:26");
INSERT INTO `kelas` VALUES("8","X RPL 2",NULL,"active","2026-02-10 18:54:26");
INSERT INTO `kelas` VALUES("9","X TKJ 1",NULL,"active","2026-02-10 18:54:26");
INSERT INTO `kelas` VALUES("10","X TKJ 2",NULL,"active","2026-02-10 18:54:26");
INSERT INTO `kelas` VALUES("11","XI RPL 1",NULL,"active","2026-02-10 18:54:26");
INSERT INTO `kelas` VALUES("12","XI RPL 2",NULL,"active","2026-02-10 18:54:26");
INSERT INTO `kelas` VALUES("13","XI TKJ 1",NULL,"active","2026-02-10 18:54:26");
INSERT INTO `kelas` VALUES("14","XI TKJ 2",NULL,"active","2026-02-10 18:54:26");
INSERT INTO `kelas` VALUES("15","XII RPL 1",NULL,"active","2026-02-10 18:54:26");
INSERT INTO `kelas` VALUES("16","XII RPL 2",NULL,"active","2026-02-10 18:54:26");
INSERT INTO `kelas` VALUES("17","XII TKJ 1",NULL,"active","2026-02-10 18:54:26");
INSERT INTO `kelas` VALUES("18","XII TKJ 2",NULL,"active","2026-02-10 18:54:26");

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
  KEY `id_guru` (`id_guru`),
  CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `log_aktivitas` VALUES("1","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-05 15:27:08");
INSERT INTO `log_aktivitas` VALUES("2","1","Export laporan pembayaran","laporan"," 2026","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-05 15:34:21");
INSERT INTO `log_aktivitas` VALUES("3","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-07 07:55:50");
INSERT INTO `log_aktivitas` VALUES("4","1","Menambah data pemasukkan","pemasukkan","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-07 08:58:17");
INSERT INTO `log_aktivitas` VALUES("5","1","Menambah data santri baru","santri","56787675","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-07 09:12:45");
INSERT INTO `log_aktivitas` VALUES("6","1","Menambah data santri baru","santri","dsfsr54765","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-07 10:28:47");
INSERT INTO `log_aktivitas` VALUES("7","1","Menambah kategori keuangan","kategori_keuangan","10","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-07 10:29:18");
INSERT INTO `log_aktivitas` VALUES("8","1","Export laporan pembayaran","laporan","Februari 2026","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-07 18:55:01");
INSERT INTO `log_aktivitas` VALUES("9","1","Menyimpan data guru (ditambahkan)","guru","2","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-07 19:22:45");
INSERT INTO `log_aktivitas` VALUES("10","1","Logout dari sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-07 19:22:53");
INSERT INTO `log_aktivitas` VALUES("11","2","Login ke sistem","guru","2","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-07 19:22:57");
INSERT INTO `log_aktivitas` VALUES("12","2","Menghapus kategori keuangan","kategori_keuangan","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-07 19:23:57");
INSERT INTO `log_aktivitas` VALUES("13","2","Menghapus kategori keuangan","kategori_keuangan","8","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-07 19:24:13");
INSERT INTO `log_aktivitas` VALUES("14","2","Menghapus kategori keuangan","kategori_keuangan","10","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-07 19:27:02");
INSERT INTO `log_aktivitas` VALUES("15","2","Logout dari sistem","guru","2","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-07 20:37:04");
INSERT INTO `log_aktivitas` VALUES("16","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-07 20:37:17");
INSERT INTO `log_aktivitas` VALUES("17","1","Menyimpan data guru (ditambahkan)","guru","3","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-07 20:38:29");
INSERT INTO `log_aktivitas` VALUES("18","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-08 19:03:15");
INSERT INTO `log_aktivitas` VALUES("19","1","Menambah data pengeluaran","pengeluaran","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-08 19:03:37");
INSERT INTO `log_aktivitas` VALUES("20","1","Export laporan pembayaran","laporan","Februari 2026","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-09 06:11:28");
INSERT INTO `log_aktivitas` VALUES("21","1","Export laporan keuangan umum","transaksi","Semua Kategori","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-09 07:22:22");
INSERT INTO `log_aktivitas` VALUES("22","1","Menambah data transaksi","transaksi","TRX-20260209-0001","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-09 07:23:55");
INSERT INTO `log_aktivitas` VALUES("23","1","Export laporan keuangan umum","transaksi","Donasi","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-09 07:24:36");
INSERT INTO `log_aktivitas` VALUES("24","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-10 12:58:37");
INSERT INTO `log_aktivitas` VALUES("25","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36","2026-02-10 12:59:07");
INSERT INTO `log_aktivitas` VALUES("26","1","Logout dari sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36","2026-02-10 17:30:07");
INSERT INTO `log_aktivitas` VALUES("27","1","Login ke sistem","guru","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36","2026-02-10 17:31:04");
INSERT INTO `log_aktivitas` VALUES("28","1","Menyimpan data SPP (diubah)","spp","1","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36","2026-02-10 17:33:21");
INSERT INTO `log_aktivitas` VALUES("29","1","Menyimpan data guru (ditambahkan)","guru","4","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36","2026-02-10 17:56:09");
INSERT INTO `log_aktivitas` VALUES("30","1","Menghapus data santri","santri","dsfsr54765","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36","2026-02-10 19:04:15");
INSERT INTO `log_aktivitas` VALUES("31","1","Menghapus data santri","santri","56787675","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36","2026-02-10 19:04:22");
INSERT INTO `log_aktivitas` VALUES("32","1","Export laporan keuangan umum","transaksi","Semua Kategori","::1","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36","2026-02-10 19:20:03");
INSERT INTO `log_aktivitas` VALUES("33","1","Login ke sistem","guru","1","10.64.191.236","curl/7.88.1","2026-02-11 05:58:37");
INSERT INTO `log_aktivitas` VALUES("34","1","Logout dari sistem","guru","1","10.64.191.236","curl/7.88.1","2026-02-11 05:58:38");
INSERT INTO `log_aktivitas` VALUES("35","1","Login ke sistem","guru","1","10.64.191.236","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-11 06:00:54");
INSERT INTO `log_aktivitas` VALUES("36","1","Login ke sistem","guru","1","10.64.100.244","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-11 12:30:05");
INSERT INTO `log_aktivitas` VALUES("37","1","Menambah data santri","santri","098787","10.64.254.154","Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0","2026-02-11 12:48:32");

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
  `nisn` varchar(20) NOT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `nspp` varchar(30) DEFAULT NULL,
  `satuan_pendidikan` varchar(50) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `id_spp` int(11) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `nama_wali` varchar(100) DEFAULT NULL,
  `telp_wali` varchar(15) DEFAULT NULL,
  `status` enum('active','inactive','alumni') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`nisn`),
  KEY `id_kelas` (`id_kelas`),
  CONSTRAINT `santri_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `santri` VALUES("098787","276432643","35214532421","23423","Ahmad Wibowo","Jakarta","2006-02-11","L","5","1","UYRTS","SADASD","2343242","active","2026-02-11 12:48:32","2026-02-11 12:48:32");

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `settings` VALUES("1","nama_sekolah","TPQ Al-Ikhlas Metro Parung","text","general","Nama sekolah/lembaga","2026-02-05 14:31:13","2026-02-05 14:31:13");
INSERT INTO `settings` VALUES("2","alamat_sekolah","PERUM METRO PARUNG, BLOK A5, Rt. 02, Rw. 07","text","general","Alamat","2026-02-05 14:31:13","2026-02-05 14:31:13");
INSERT INTO `settings` VALUES("3","telp_sekolah","021-12345678","text","general","Telepon","2026-02-05 14:31:13","2026-02-05 14:31:13");
INSERT INTO `settings` VALUES("4","email_sekolah","info@tpq-alikhlas.sch.id","email","general","Email","2026-02-05 14:31:13","2026-02-05 14:31:13");
INSERT INTO `settings` VALUES("5","kepala_sekolah","Ustadz Ahmad","text","general","Nama Kepala Sekolah","2026-02-05 14:31:13","2026-02-05 14:31:13");
INSERT INTO `settings` VALUES("6","bendahara","Ustadzah Fatimah","text","general","Nama Bendahara","2026-02-05 14:31:13","2026-02-05 14:31:13");

-- Table: spp
DROP TABLE IF EXISTS `spp`;
CREATE TABLE `spp` (
  `id_spp` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` int(4) NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_spp`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `spp` VALUES("1","2026","100000.00","2026-02-03 12:17:30");
INSERT INTO `spp` VALUES("2","2024","250000.00","2026-02-10 18:54:26");
INSERT INTO `spp` VALUES("3","2025","300000.00","2026-02-10 18:54:26");

-- Table: tarif_spp
DROP TABLE IF EXISTS `tarif_spp`;
CREATE TABLE `tarif_spp` (
  `id_tarif` int(11) NOT NULL AUTO_INCREMENT,
  `tahun_ajaran` varchar(9) NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_tarif`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tarif_spp` VALUES("1","2025/2026","50000.00","Tarif SPP tahun ajaran 2025/2026","active","2026-02-05 14:31:13");
INSERT INTO `tarif_spp` VALUES("2","2026/2027","50000.00","Tarif SPP tahun ajaran 2026/2027","active","2026-02-05 14:31:13");

-- Table: transaksi
DROP TABLE IF EXISTS `transaksi`;
CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(30) NOT NULL,
  `tanggal` date NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `nisn` varchar(20) DEFAULT NULL COMMENT 'NULL jika bukan transaksi per santri',
  `uraian` varchar(255) NOT NULL,
  `debit` decimal(15,2) DEFAULT 0.00 COMMENT 'Pemasukan',
  `kredit` decimal(15,2) DEFAULT 0.00 COMMENT 'Pengeluaran',
  `bulan_spp` varchar(20) DEFAULT NULL COMMENT 'Untuk SPP: bulan yang dibayar',
  `tahun_spp` varchar(4) DEFAULT NULL COMMENT 'Untuk SPP: tahun',
  `metode_bayar` enum('tunai','transfer','online') DEFAULT 'tunai',
  `keterangan` text DEFAULT NULL,
  `id_guru` int(11) NOT NULL COMMENT 'User yang input',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_transaksi`),
  UNIQUE KEY `no_transaksi` (`no_transaksi`),
  KEY `id_kategori` (`id_kategori`),
  KEY `nisn` (`nisn`),
  KEY `id_guru` (`id_guru`),
  KEY `tanggal` (`tanggal`),
  CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_keuangan` (`id_kategori`),
  CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`nisn`) REFERENCES `santri` (`nisn`) ON DELETE SET NULL,
  CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `transaksi` VALUES("2","TRX-20260209-0001","2026-02-09","2",NULL,"saidyfi","100000.00","0.00",NULL,NULL,"tunai",NULL,"1","2026-02-09 07:23:55","2026-02-09 07:23:55");

SET FOREIGN_KEY_CHECKS = 1;
