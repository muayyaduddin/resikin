# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: guyubapp.com (MySQL 5.5.5-10.5.15-MariaDB-cll-lve)
# Database: u3706888_resikin
# Generation Time: 2022-07-14 22:54:09 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table barang_pembelian
# ------------------------------------------------------------

DROP TABLE IF EXISTS `barang_pembelian`;

CREATE TABLE `barang_pembelian` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idpembelian` int(11) DEFAULT NULL,
  `idproduk` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `barang_pembelian` WRITE;
/*!40000 ALTER TABLE `barang_pembelian` DISABLE KEYS */;

INSERT INTO `barang_pembelian` (`id`, `idpembelian`, `idproduk`, `jumlah`)
VALUES
	(3,1,3,3),
	(4,2,3,2),
	(5,3,3,1),
	(6,4,3,1);

/*!40000 ALTER TABLE `barang_pembelian` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table barang_penjualan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `barang_penjualan`;

CREATE TABLE `barang_penjualan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idpenjualan` int(11) DEFAULT NULL,
  `idjenissampah` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `barang_penjualan` WRITE;
/*!40000 ALTER TABLE `barang_penjualan` DISABLE KEYS */;

INSERT INTO `barang_penjualan` (`id`, `idpenjualan`, `idjenissampah`, `jumlah`)
VALUES
	(9,1,6,1),
	(12,5,4,3),
	(13,6,4,3),
	(14,7,4,3),
	(15,7,6,5),
	(16,8,5,15),
	(17,8,6,15),
	(18,9,4,30),
	(19,9,6,103),
	(20,10,7,6),
	(21,10,3,4);

/*!40000 ALTER TABLE `barang_penjualan` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table daerah
# ------------------------------------------------------------

DROP TABLE IF EXISTS `daerah`;

CREATE TABLE `daerah` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `status` int(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `daerah` WRITE;
/*!40000 ALTER TABLE `daerah` DISABLE KEYS */;

INSERT INTO `daerah` (`id`, `nama`, `status`)
VALUES
	(1,'Ampelgading',1),
	(2,'Bantur',1),
	(3,'Bululawang',1),
	(4,'Dampit',1),
	(5,'Dau',1),
	(6,'Donomulyo',1),
	(7,'KlojenUpdate',-1);

/*!40000 ALTER TABLE `daerah` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table info_mitra
# ------------------------------------------------------------

DROP TABLE IF EXISTS `info_mitra`;

CREATE TABLE `info_mitra` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iduser` int(11) DEFAULT NULL,
  `iddaerah` int(11) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `foto` text DEFAULT NULL,
  `gpsl` varchar(50) DEFAULT NULL,
  `gpsb` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `info_mitra` WRITE;
/*!40000 ALTER TABLE `info_mitra` DISABLE KEYS */;

INSERT INTO `info_mitra` (`id`, `iduser`, `iddaerah`, `nama`, `foto`, `gpsl`, `gpsb`, `alamat`)
VALUES
	(2,7,4,'Mightrees','Mightrees7.png','-7.946673987712951','112.55919434130192','jalansissbianauss'),
	(3,5,4,'bola2','bola2.jpg','-10.877129519466739','55.919434112130192','jl. asdqwezxc'),
	(4,19,4,'Udon Kamikaze','Udon Kamikaze19.png','-7.9731556366799445','112.62325990945101','jalan simpang balapan');

/*!40000 ALTER TABLE `info_mitra` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table jadwal_ambil_sampah
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jadwal_ambil_sampah`;

CREATE TABLE `jadwal_ambil_sampah` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iddaerah` int(11) DEFAULT NULL,
  `jam` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `jadwal_ambil_sampah` WRITE;
/*!40000 ALTER TABLE `jadwal_ambil_sampah` DISABLE KEYS */;

INSERT INTO `jadwal_ambil_sampah` (`id`, `iddaerah`, `jam`)
VALUES
	(1,1,'09:00:00'),
	(2,4,'09:00:00');

/*!40000 ALTER TABLE `jadwal_ambil_sampah` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table jenis_produk
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jenis_produk`;

CREATE TABLE `jenis_produk` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iddaerah` int(11) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `status` int(1) DEFAULT 1 COMMENT '1 aktif -1 nonaktif',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `jenis_produk` WRITE;
/*!40000 ALTER TABLE `jenis_produk` DISABLE KEYS */;

INSERT INTO `jenis_produk` (`id`, `iddaerah`, `nama`, `status`)
VALUES
	(1,4,'Air Mineral 2',-1),
	(2,4,'Air Mineral',1),
	(4,4,'Minyak',1),
	(5,4,'Minyak',1),
	(6,4,'Mie Instan',1),
	(7,4,'Minuman',1);

/*!40000 ALTER TABLE `jenis_produk` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table jenis_sampah
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jenis_sampah`;

CREATE TABLE `jenis_sampah` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iddaerah` int(11) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `satuan` varchar(10) DEFAULT NULL,
  `status` int(1) DEFAULT 1 COMMENT '1 aktif -1 non aktif',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `jenis_sampah` WRITE;
/*!40000 ALTER TABLE `jenis_sampah` DISABLE KEYS */;

INSERT INTO `jenis_sampah` (`id`, `iddaerah`, `nama`, `harga`, `satuan`, `status`)
VALUES
	(1,1,'Botol Plastik 1 lt',5000,'pcs',-1),
	(2,1,'Botol plastik',3000,'pcs',-1),
	(3,1,'Botol Plastik Kecil',1500,'botol',1),
	(4,4,'Botol Plastik',500,'pcs',1),
	(5,4,'Botol Kaca',1500,'pcs',1),
	(6,4,'Kerdus',2500,'kg',1),
	(7,1,'Botol plastik besar',2000,'botol',1);

/*!40000 ALTER TABLE `jenis_sampah` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table otp
# ------------------------------------------------------------

DROP TABLE IF EXISTS `otp`;

CREATE TABLE `otp` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iduser` int(11) DEFAULT NULL,
  `otp` varchar(4) DEFAULT '',
  `validuntil` datetime DEFAULT NULL,
  `expired` int(1) DEFAULT 0 COMMENT '0 belum, 1 expired',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `otp` WRITE;
/*!40000 ALTER TABLE `otp` DISABLE KEYS */;

INSERT INTO `otp` (`id`, `iduser`, `otp`, `validuntil`, `expired`)
VALUES
	(1,1,'2205','2022-06-25 23:06:38',0),
	(2,1,'9690','2022-06-25 23:19:07',0),
	(3,2,'1742','2022-06-25 23:26:54',0),
	(4,2,'3708','2022-06-25 23:27:06',0),
	(5,2,'4204','2022-06-25 23:27:18',0),
	(6,2,'5692','2022-06-25 23:29:12',0),
	(7,1,'9321','2022-06-25 23:31:13',0),
	(8,2,'6888','2022-06-25 23:34:51',0),
	(9,3,'5533','2022-06-26 00:04:51',0),
	(10,5,'9317','2022-06-26 08:53:47',0),
	(11,11,'3136','2022-06-27 11:41:42',0),
	(12,10,'7744','2022-06-27 11:41:42',0),
	(13,12,'9110','2022-06-28 02:44:46',0),
	(14,13,'7489','2022-07-10 10:52:58',0),
	(15,14,'1187','2022-07-10 11:45:55',0),
	(16,15,'7100','2022-07-10 11:56:36',0),
	(17,15,'1786','2022-07-10 12:00:45',0),
	(18,15,'8850','2022-07-10 12:01:02',0),
	(19,15,'9696','2022-07-10 12:01:19',0),
	(20,15,'4563','2022-07-10 12:01:24',0),
	(21,15,'6643','2022-07-10 12:01:37',0),
	(22,20,'2101','2022-07-11 12:30:34',0),
	(23,20,'3504','2022-07-11 12:31:05',0),
	(24,20,'7113','2022-07-11 12:31:10',0),
	(25,20,'6884','2022-07-11 12:31:16',0),
	(26,21,'6034','2022-07-11 15:36:37',0),
	(27,21,'8780','2022-07-11 15:36:52',0),
	(28,21,'3983','2022-07-11 15:37:08',0),
	(29,22,'7935','2022-07-11 16:23:04',0),
	(30,22,'3865','2022-07-11 16:23:23',0),
	(31,22,'4970','2022-07-11 16:23:29',0),
	(32,22,'3186','2022-07-11 16:23:36',0),
	(33,22,'2257','2022-07-11 16:23:36',0),
	(34,22,'2440','2022-07-11 16:23:41',0),
	(35,22,'9221','2022-07-11 16:23:42',0),
	(36,22,'9160','2022-07-11 16:23:47',0),
	(37,22,'9327','2022-07-11 16:23:48',0),
	(38,22,'9360','2022-07-11 16:23:54',0),
	(39,22,'8169','2022-07-11 16:23:54',0),
	(40,22,'1479','2022-07-11 16:24:00',0),
	(41,22,'7472','2022-07-11 16:24:00',0),
	(42,22,'5036','2022-07-11 16:24:06',0),
	(43,22,'5165','2022-07-11 16:24:06',0),
	(44,22,'7735','2022-07-11 16:24:12',0),
	(45,22,'3794','2022-07-11 16:24:12',0),
	(46,22,'1973','2022-07-11 16:24:17',0),
	(47,22,'4479','2022-07-11 16:24:17',0),
	(48,22,'6912','2022-07-11 16:24:19',0),
	(49,22,'9870','2022-07-11 16:24:19',0),
	(50,22,'7347','2022-07-11 16:24:21',0),
	(51,22,'9013','2022-07-11 16:24:22',0),
	(52,22,'8392','2022-07-11 16:24:23',0),
	(53,22,'7196','2022-07-11 16:24:24',0),
	(54,22,'5829','2022-07-11 16:24:32',0),
	(55,22,'6842','2022-07-11 16:24:39',0),
	(56,20,'6375','2022-07-12 01:14:42',0),
	(57,21,'2169','2022-07-12 03:21:16',0),
	(58,10,'7560','2022-07-12 03:21:32',0),
	(59,23,'3961','2022-07-13 19:08:14',0),
	(60,23,'4311','2022-07-13 19:08:33',0),
	(61,23,'4198','2022-07-13 19:08:38',0),
	(62,23,'2560','2022-07-13 19:08:44',0),
	(63,23,'3559','2022-07-13 19:08:49',0),
	(64,23,'5007','2022-07-13 19:08:54',0),
	(65,23,'1155','2022-07-13 19:08:55',0),
	(66,23,'7847','2022-07-13 19:08:56',0),
	(67,23,'2412','2022-07-13 19:08:57',0),
	(68,23,'8492','2022-07-13 19:08:58',0),
	(69,23,'6195','2022-07-13 19:09:12',0),
	(70,23,'2687','2022-07-13 19:09:17',0),
	(71,23,'3193','2022-07-13 19:09:22',0),
	(72,24,'7840','2022-07-14 14:04:51',0),
	(73,24,'2321','2022-07-14 14:05:19',0),
	(74,24,'2185','2022-07-14 14:05:24',0),
	(75,24,'1445','2022-07-14 14:05:31',0),
	(76,24,'5992','2022-07-14 14:05:31',0),
	(77,24,'1552','2022-07-14 14:05:37',0),
	(78,24,'8946','2022-07-14 14:05:37',0),
	(79,24,'5288','2022-07-14 14:05:42',0),
	(80,24,'8118','2022-07-14 14:05:42',0),
	(81,24,'3909','2022-07-14 14:05:47',0),
	(82,24,'5290','2022-07-14 14:05:48',0),
	(83,24,'6814','2022-07-14 14:05:54',0),
	(84,24,'4083','2022-07-14 14:05:55',0),
	(85,24,'8181','2022-07-14 14:05:56',0),
	(86,24,'6349','2022-07-14 14:06:00',0),
	(87,24,'1775','2022-07-14 14:08:11',0),
	(88,24,'4072','2022-07-14 14:08:18',0),
	(89,24,'9468','2022-07-14 14:08:18',0),
	(90,24,'4808','2022-07-14 14:08:24',0),
	(91,24,'1852','2022-07-14 14:08:24',0),
	(92,24,'7638','2022-07-14 14:08:30',0),
	(93,24,'4390','2022-07-14 14:08:30',0),
	(94,24,'8061','2022-07-14 14:08:35',0),
	(95,24,'7114','2022-07-14 14:08:35',0),
	(96,24,'9757','2022-07-14 14:08:41',0),
	(97,24,'8518','2022-07-14 14:08:41',0),
	(98,24,'8851','2022-07-14 14:08:46',0),
	(99,24,'8804','2022-07-14 14:08:46',0),
	(100,24,'8719','2022-07-14 14:08:53',0),
	(101,24,'7270','2022-07-14 14:08:53',0),
	(102,24,'4716','2022-07-14 14:20:21',0),
	(103,24,'4125','2022-07-14 14:20:27',0),
	(104,25,'4815','2022-07-14 14:52:03',0);

/*!40000 ALTER TABLE `otp` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pembelian
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pembelian`;

CREATE TABLE `pembelian` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idalamat` int(11) DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `totalpembelian` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `idkurir` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT 1,
  `statusprogress` int(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `pembelian` WRITE;
/*!40000 ALTER TABLE `pembelian` DISABLE KEYS */;

INSERT INTO `pembelian` (`id`, `idalamat`, `iduser`, `totalpembelian`, `tanggal`, `idkurir`, `status`, `statusprogress`)
VALUES
	(1,2,5,75000,'2022-07-07 23:52:53',6,1,3),
	(2,2,5,50000,'2022-07-10 04:29:17',6,1,3),
	(3,9,15,25000,'2022-07-10 12:20:45',6,1,3),
	(4,2,5,25000,'2022-07-10 13:14:06',6,1,3);

/*!40000 ALTER TABLE `pembelian` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table penjualan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penjualan`;

CREATE TABLE `penjualan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idalamat` int(11) DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `foto` text DEFAULT NULL,
  `totalpenjualan` int(11) DEFAULT NULL,
  `idjadwal` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `idkurir` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT 1 COMMENT '1 aktif / menunggu kurir',
  `statusprogress` int(1) DEFAULT 0 COMMENT '0 = menunggu kurir, 1 = kurir otw, 2 = barang diambil',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `penjualan` WRITE;
/*!40000 ALTER TABLE `penjualan` DISABLE KEYS */;

INSERT INTO `penjualan` (`id`, `idalamat`, `iduser`, `foto`, `totalpenjualan`, `idjadwal`, `tanggal`, `idkurir`, `status`, `statusprogress`)
VALUES
	(5,2,5,NULL,1500,2,'2022-07-08 16:20:02',6,1,2),
	(6,2,5,NULL,1500,2,'2022-07-10 02:35:57',6,1,2),
	(7,2,5,'penjualan_rezza_yuniansyah_202207100948.jpg',14000,2,'2022-07-10 02:48:28',6,1,2),
	(8,9,15,'penjualan_rezza_ilmi_2_202207101915.jpg',60000,2,'2022-07-10 12:15:33',6,1,1),
	(9,2,5,'penjualan_rezza_yuniansyah_202207102012.jpg',272500,2,'2022-07-10 13:12:56',6,1,2),
	(10,3,2,'penjualan_Ayat_202207142207.jpg',18000,1,'2022-07-14 15:08:34',17,1,0);

/*!40000 ALTER TABLE `penjualan` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table produk
# ------------------------------------------------------------

DROP TABLE IF EXISTS `produk`;

CREATE TABLE `produk` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idjenis` int(11) DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `foto` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `produk` WRITE;
/*!40000 ALTER TABLE `produk` DISABLE KEYS */;

INSERT INTO `produk` (`id`, `idjenis`, `iduser`, `nama`, `foto`, `deskripsi`, `harga`, `satuan`, `status`)
VALUES
	(1,2,5,'coba','fotocoba','deskripsi',100,'kg',-1),
	(2,2,5,'update','fotocoba','coba update',100,'kg',1),
	(3,2,7,'Fuji','Fuji7.png','kue brownies panggang',25000,'pcs',1),
	(4,2,5,'aqua','aqua.jpg','deskripsi',350,'kg',1),
	(5,2,5,'cleo','cleo.jpg','deskripsi',7500,'kg',1),
	(6,2,5,'danone','air.png','deskripsi',1200,'kg',1),
	(7,2,5,'nestle','nestle.jpeg','deskripsi',10000,'kg',1),
	(8,2,5,'mizone','mizone.png','deskripsi',15000,'kg',1),
	(9,2,5,'as shifa\'','nh.jpg','deskripsi',4000,'kg',1),
	(10,4,7,'olive oil','olive.png','desc',12000,'lt',1),
	(11,4,5,'bbm','bbm.jpg','desc',12500,'lt',1);

/*!40000 ALTER TABLE `produk` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table progress_pembelian
# ------------------------------------------------------------

DROP TABLE IF EXISTS `progress_pembelian`;

CREATE TABLE `progress_pembelian` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idpembelian` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `foto` text DEFAULT NULL,
  `status` int(11) DEFAULT 0 COMMENT '1 kurir dalam perjalanan, 2 selesai',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table progress_penjualan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `progress_penjualan`;

CREATE TABLE `progress_penjualan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idpenjualan` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `foto` text DEFAULT NULL,
  `status` int(1) DEFAULT 1 COMMENT '1 kurir dalam perjalanan, 2 selesai',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table sedekah
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sedekah`;

CREATE TABLE `sedekah` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iddaerah` int(11) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` int(1) DEFAULT 1 COMMENT '1 aktif, -1 non aktif',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `sedekah` WRITE;
/*!40000 ALTER TABLE `sedekah` DISABLE KEYS */;

INSERT INTO `sedekah` (`id`, `iddaerah`, `nama`, `deskripsi`, `status`)
VALUES
	(1,4,'Pembangunan Sekolah','Bantuan untuk membangun sekolah di daerah',1);

/*!40000 ALTER TABLE `sedekah` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sedekahuser
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sedekahuser`;

CREATE TABLE `sedekahuser` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iduser` int(11) DEFAULT NULL,
  `idsedekah` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `sedekahuser` WRITE;
/*!40000 ALTER TABLE `sedekahuser` DISABLE KEYS */;

INSERT INTO `sedekahuser` (`id`, `iduser`, `idsedekah`, `total`, `tanggal`)
VALUES
	(1,5,1,5000,'2022-07-10 11:34:09'),
	(2,15,1,5000,'2022-07-10 12:23:21');

/*!40000 ALTER TABLE `sedekahuser` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tabungan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tabungan`;

CREATE TABLE `tabungan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iddaerah` int(11) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` int(1) DEFAULT 1 COMMENT '1 aktif, -1 nonaktif',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `tabungan` WRITE;
/*!40000 ALTER TABLE `tabungan` DISABLE KEYS */;

INSERT INTO `tabungan` (`id`, `iddaerah`, `nama`, `deskripsi`, `status`)
VALUES
	(1,1,'Tabungan Hari Tua','Tabung sekarang demi mas depan yang aman',1);

/*!40000 ALTER TABLE `tabungan` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tabunganuser
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tabunganuser`;

CREATE TABLE `tabunganuser` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iduser` int(11) DEFAULT NULL,
  `idtabungan` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `tabunganuser` WRITE;
/*!40000 ALTER TABLE `tabunganuser` DISABLE KEYS */;

INSERT INTO `tabunganuser` (`id`, `iduser`, `idtabungan`, `total`, `tanggal`)
VALUES
	(1,5,1,25000,'2022-07-10 10:24:13'),
	(2,15,1,10000,'2022-07-10 12:22:22');

/*!40000 ALTER TABLE `tabunganuser` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idlevel` int(11) DEFAULT 2,
  `iddaerah` int(11) DEFAULT -1 COMMENT 'khusus untuk admin daerah',
  `nama` varchar(100) DEFAULT '',
  `email` varchar(100) DEFAULT '',
  `nohp` varchar(20) DEFAULT '',
  `password` varchar(50) DEFAULT '',
  `fcmtoken` text DEFAULT NULL,
  `deviceid` varchar(20) DEFAULT NULL,
  `status_verifikasi` int(1) DEFAULT 0 COMMENT '0 belum verifikasi, 1 verified',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `idlevel`, `iddaerah`, `nama`, `email`, `nohp`, `password`, `fcmtoken`, `deviceid`, `status_verifikasi`)
VALUES
	(2,2,-1,'Ayat','ayatmyd@gmail.com','085955166669','12345678','e8cNELp0S5CjKqqltOkHur:APA91bFQuzs0JD0ht8SbUPzT12O0-qexBqrFLWmmzOV2mPSuFjqHlcWTFPSQBMsztLVbvyR-bZWKyXPLCvKX7IVThayoQnyucKtZpxQtfCLDvUow31wKkDAx7Lb8ipj71zfpvaQFFuy1','54E2FB514AF64D15',1),
	(3,2,-1,'Rika','dakiapps@gmail.com','08123456789','password','drI59eIzTtGC-I8CcNATwu:APA91bEtfWXswRhS_52n9te9iW5QSAjU3YcQMKWqxVDfKw_cyDvwN2WjiE6ZGt7D6HlvDH8A65Nab2G-iz-5_PbXbOl24VSAyoSrPT6mFpSw1mOZgKRMhyCbblbgnYejUmT4PqQk2odv','019710DC3F85DBD1',1),
	(5,2,-1,'rezza yuniansyah','rezza@gmail.com','081944953214','bolabola2','cQySI5U-SDOpcmiW1EXM8a:APA91bGXMk3LvbzOV_ce6xKhrT0olrc7-SgyqDX48iyVXxSOJ27kkT3GGm63UmEa73Xf2PUDfOEcdIF3e4pfQxNE_zXVFLaAc9SzE86V1Ta7RpMn2sNVuvl36HTkvYItlnhscjRdGSOV','84ABD22195559D83',1),
	(6,3,-1,'jajang kurir','kurir@resikin.com','081948138','111111','cQySI5U-SDOpcmiW1EXM8a:APA91bGXMk3LvbzOV_ce6xKhrT0olrc7-SgyqDX48iyVXxSOJ27kkT3GGm63UmEa73Xf2PUDfOEcdIF3e4pfQxNE_zXVFLaAc9SzE86V1Ta7RpMn2sNVuvl36HTkvYItlnhscjRdGSOV','84ABD22195559D83',1),
	(7,4,-1,'rezza mitra','mitra@resikin.com','0785794215','111111','cQySI5U-SDOpcmiW1EXM8a:APA91bGXMk3LvbzOV_ce6xKhrT0olrc7-SgyqDX48iyVXxSOJ27kkT3GGm63UmEa73Xf2PUDfOEcdIF3e4pfQxNE_zXVFLaAc9SzE86V1Ta7RpMn2sNVuvl36HTkvYItlnhscjRdGSOV','84ABD22195559D83',1),
	(8,5,4,'rezza admin daerah','admindaerah@resikin.com','0484854485448','111111','cQySI5U-SDOpcmiW1EXM8a:APA91bGXMk3LvbzOV_ce6xKhrT0olrc7-SgyqDX48iyVXxSOJ27kkT3GGm63UmEa73Xf2PUDfOEcdIF3e4pfQxNE_zXVFLaAc9SzE86V1Ta7RpMn2sNVuvl36HTkvYItlnhscjRdGSOV','84ABD22195559D83',1),
	(9,1,-1,'rezza admin pusat','adminpusat@resikin.com','091238102938120','111111','cQySI5U-SDOpcmiW1EXM8a:APA91bGXMk3LvbzOV_ce6xKhrT0olrc7-SgyqDX48iyVXxSOJ27kkT3GGm63UmEa73Xf2PUDfOEcdIF3e4pfQxNE_zXVFLaAc9SzE86V1Ta7RpMn2sNVuvl36HTkvYItlnhscjRdGSOV','84ABD22195559D83',1),
	(10,2,NULL,'Sasri Ninda','sasrininda@gmail.com','087554561595','123456',NULL,NULL,0),
	(11,2,NULL,'Sasri Ninda','sasrininda@gmail.com','087554561595','123456',NULL,NULL,0),
	(12,2,-1,'Urip Prayitno','uriptobat@gmail.com','081230099927','uripaja890..',NULL,NULL,0),
	(13,2,-1,'Yasmeen','uriplava870@gmail.com','08813561535','uripaja890..',NULL,NULL,0),
	(14,2,-1,'rezza ilmi','rezza2@gmail.com','081944953214','bolabola2','cQySI5U-SDOpcmiW1EXM8a:APA91bGXMk3LvbzOV_ce6xKhrT0olrc7-SgyqDX48iyVXxSOJ27kkT3GGm63UmEa73Xf2PUDfOEcdIF3e4pfQxNE_zXVFLaAc9SzE86V1Ta7RpMn2sNVuvl36HTkvYItlnhscjRdGSOV','84ABD22195559D83',1),
	(15,2,-1,'rezza ilmi 2','rezza@gmail.com','081944953214','bolabola2','fTLxZfzORxKY94TMfaN6GT:APA91bHVZfEYpxRaJHo9XNku4kS-ssYv67Xaui-yluc0bp_N3NSLJ2stMD2PvVbegi94Q7XETWut5jj_uyqL_cbrs6HlOwKIW3yvr4S_v9Ie7gK8XvVJQz91nDU5ZkKR1c1-sMHoledc','1A8747D3B65E67A6',1),
	(16,5,4,'Riki Aji','rezza2@gmail.com','091231231','111111','cQySI5U-SDOpcmiW1EXM8a:APA91bGXMk3LvbzOV_ce6xKhrT0olrc7-SgyqDX48iyVXxSOJ27kkT3GGm63UmEa73Xf2PUDfOEcdIF3e4pfQxNE_zXVFLaAc9SzE86V1Ta7RpMn2sNVuvl36HTkvYItlnhscjRdGSOV','84ABD22195559D83',1),
	(17,3,-1,'rezza kurir','rezza.kurir@gmail.com','081944953214','111111','cQySI5U-SDOpcmiW1EXM8a:APA91bGXMk3LvbzOV_ce6xKhrT0olrc7-SgyqDX48iyVXxSOJ27kkT3GGm63UmEa73Xf2PUDfOEcdIF3e4pfQxNE_zXVFLaAc9SzE86V1Ta7RpMn2sNVuvl36HTkvYItlnhscjRdGSOV','84ABD22195559D83',1),
	(19,4,4,'rezza kurir','rezza.ilmi@gmail.com','0451815164','1111111','cQySI5U-SDOpcmiW1EXM8a:APA91bGXMk3LvbzOV_ce6xKhrT0olrc7-SgyqDX48iyVXxSOJ27kkT3GGm63UmEa73Xf2PUDfOEcdIF3e4pfQxNE_zXVFLaAc9SzE86V1Ta7RpMn2sNVuvl36HTkvYItlnhscjRdGSOV','84ABD22195559D83',1),
	(20,2,-1,'Test','muhtadiimam05@gmail.com','08123456789','password','fRUfprjLRouBE7Z6X3CEgK:APA91bGx9-FRDxwFM8L6W0OtAj0-TBcZk1inkY9VgSn0UxjxhJ6SU4cXk9O3MP30T3y6mqX-iu6yyHJ-A2Y-MQGnsXcosSx4dkp4N1Hv1lmRGuNxvuSa_XRW555tfbQzIVnC69TTIPfX','019710DC3F85DBD1',0),
	(21,2,-1,'sasrininda','nindasasri@gmail.com','09686464','123456',NULL,NULL,0),
	(22,2,-1,'Farhan','farhanalifzamorano@gmail.com','085236455015','farhanalif','dJPF3_K0SW-GoDs8ETBlxU:APA91bEHf-Kl7gRfygTVCgYEZ7MvzPr3sF4RtU1PsgFN2-woKalzHmMT6204loPkq8aJGa1U5NAHLkLCRiBDCyK_U7otbEjXty2uFluCkVRfJFYCYBxT7F1QqZDTySXGH39Op-fAm3_q','B26B181623A5511E',0),
	(23,2,-1,'Buhairj','khairipasker116@gmail.com','085934610569','Buhairi678','dBCFSuYDRAKuuw4XpquhyN:APA91bFeklXoxExxnNlMIkGPtD22tFWRAbAEBb8zwMGt1bOz80vFy-1jAbMw_MBEdMTazGlTJthnMqWSpnbh1tK6Mk2M-V5FBeMf_SgfIg9YZdQTehSaWTmbTOO4HIPWMTPyIPY6WVcM','2F65B781D4301DC7',0),
	(24,2,-1,'Ach Dafid','david.blakucak@gmail.com','087850257000','David199','crmY3p-eQA2EPVxWKHzxQs:APA91bEVUKab8ZA9axBUrN8J8t0EDTgK63yaRcOfQ0Gyz7NgSja04DjNlcmdEkgZ_q-M7fX8wK6sqTdmtf0r1VYnP9QcsUQ9pfynnjBVnM_vBsibJWpL-83d9C3z5u1VPIBy9TgT0-AL','54E2FB514AF64D15',0),
	(25,2,-1,'Muayyaduddin','muayyaduddin98@gmail.com','085336540101','Santuy123',NULL,NULL,0);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_alamat
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_alamat`;

CREATE TABLE `user_alamat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iduser` int(11) DEFAULT NULL,
  `iddaerah` int(11) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `gpsl` varchar(50) DEFAULT NULL,
  `gpsb` varchar(50) DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `isdefault` int(1) DEFAULT 0 COMMENT '0 bukan default, 1 default',
  `status` int(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `user_alamat` WRITE;
/*!40000 ALTER TABLE `user_alamat` DISABLE KEYS */;

INSERT INTO `user_alamat` (`id`, `iduser`, `iddaerah`, `nama`, `gpsl`, `gpsb`, `detail`, `isdefault`, `status`)
VALUES
	(2,5,4,'rumah utama 2','-7.967853666360565','112.62851972132923','jalan mega mendung',1,1),
	(3,2,1,'kantor','1.22331293','1.97654321','Jl. brigjend s parman',1,1),
	(4,2,1,'cobadelete','0.01','0.01','coba delete',0,-1),
	(5,1,1,'cobastatus','0.0333','0.0222','coba status',1,1),
	(6,5,1,'Kantor','-7.967853666360565','112.62851972132923','jalan durian',0,-1),
	(7,5,2,'kantor 2','-7.95898147827748','112.62709278613329','jalan bunga cempaka',0,1),
	(8,14,1,'Rumah Sendiri','-7.9716010455372315','112.62491315603256','jalan Raya Tidar',1,1),
	(9,15,4,'rumah','-7.961830095838482','112.63069935142994','bajabsjss',1,1);

/*!40000 ALTER TABLE `user_alamat` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_level
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_level`;

CREATE TABLE `user_level` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(3) DEFAULT NULL,
  `nama` varchar(20) DEFAULT NULL,
  `pendaftarankhusus` int(1) DEFAULT 0 COMMENT '0 untuk umum, 1 khusus',
  `requestotp` int(1) DEFAULT 0 COMMENT '0 tidak request, 1 requestotp',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `user_level` WRITE;
/*!40000 ALTER TABLE `user_level` DISABLE KEYS */;

INSERT INTO `user_level` (`id`, `kode`, `nama`, `pendaftarankhusus`, `requestotp`)
VALUES
	(1,'ADP','Admin Pusat',1,0),
	(2,'PUM','Pengguna Umum',0,1),
	(3,'KUR','Kurir',0,0),
	(4,'MIT','Mitra',0,0),
	(5,'ADD','Admin Daerah',0,0);

/*!40000 ALTER TABLE `user_level` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
