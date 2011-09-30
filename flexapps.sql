/*
SQLyog Ultimate v8.62 
MySQL - 5.1.41 : Database - flexapps
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `flx_application` */

CREATE TABLE `flx_application` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `flx_application` */

insert  into `flx_application`(`id`,`name`,`title`,`description`) values (1,'cineplex','21 Cineplex','Jadwal semua bioskop 21 atau XXI di seluruh Indonesia secara realtime');
insert  into `flx_application`(`id`,`name`,`title`,`description`) values (2,'megaplex','Blitz Megaplex','Jadwal semua bioskop Blitz Megaplex di seluruh indonesia secara realtime');
insert  into `flx_application`(`id`,`name`,`title`,`description`) values (3,'kompas','Kompas','Koran Kompas');
insert  into `flx_application`(`id`,`name`,`title`,`description`) values (4,'vivanews','VivaNews','Koran Viva News');
insert  into `flx_application`(`id`,`name`,`title`,`description`) values (5,'detik','Detik','Situs berita detik');
insert  into `flx_application`(`id`,`name`,`title`,`description`) values (6,'lionair','Lion Air','Jadwal dan harga tiket penerbangan Lionair secara realtime');
insert  into `flx_application`(`id`,`name`,`title`,`description`) values (7,'sekar','Sekar','Serikat Pekerja Telkom');
insert  into `flx_application`(`id`,`name`,`title`,`description`) values (8,'okezone','Okezone','Situs berita okezone');
insert  into `flx_application`(`id`,`name`,`title`,`description`) values (9,'detikbola','Detiksport - Sepakbola','Situs berita detiksport');
insert  into `flx_application`(`id`,`name`,`title`,`description`) values (10,'goal','Goal.com','Situs berita bola international');
insert  into `flx_application`(`id`,`name`,`title`,`description`) values (12,'sekolah','Sekolah','Melihat nilai, absensi dan spp sekolah secara realtime');

/*Table structure for table `flx_user` */

CREATE TABLE `flx_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `city` varchar(200) DEFAULT NULL,
  `status` enum('I','O') NOT NULL DEFAULT 'O',
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;

/*Data for the table `flx_user` */

insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (62,'081122334455','siswa','bandung','I','2011-03-25 08:47:02',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (45,'123','1','test','I','2011-03-23 10:30:09',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (44,'085238320567','44444','Kuta','I','2011-03-16 17:29:58',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (42,'081319399030','123','bandung','I','2011-04-02 06:21:04',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (37,'081321494163','dinata','Bandung','I','2011-04-06 11:51:06',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (43,' 6281365011122','qwerty11','Riau','I','2011-03-16 15:18:42',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (40,'08111888801','12345678','Jakarta','I','2011-03-27 08:55:57',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (48,'08999909619','donttakealong','Jakarta','I','2011-03-20 22:44:30',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (61,'0811223344','guru','bandung','I','2011-03-25 07:51:16',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (51,' 62811836785','691003','Jakarta','I','2011-03-23 05:12:54',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (52,'081322420181','1234','bandung','I','2011-04-04 07:25:13',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (53,'082112682223','ridwan','jakarta','I','2011-03-23 05:46:42',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (54,'081315633637','ridwan','jakarta','I','2011-03-24 15:25:31',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (55,'081257780985','@#herudin','Heryyyy','I','2011-03-23 15:43:07',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (56,'085716432704','ridwan','Jakarta','I','2011-03-25 04:43:35',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (57,'081220667911','12345','bandung','I','2011-03-24 15:54:48',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (58,'08122120174','tes','Jakarta','I','2011-03-28 08:43:23',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (59,'0817826933','firdi','Jakarta','I','2011-03-25 08:46:33',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (60,'087822661152','junior','Bandung','I','2011-03-25 04:51:39',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (64,'081357974200','coba','sidoarjo','I','2011-03-29 00:06:10',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (65,'081572283332','123456','Sumedang','I','2011-04-04 11:52:09',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (66,'081323767601','khanahaya','Bandung','I','2011-03-29 12:25:59',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (67,'08562820110','aaaa','klate','I','2011-03-31 07:49:38',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (68,'081320653009','arka','KLATEN','I','2011-04-01 18:31:30',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (69,'085647061033','arkananta','Klaten','I','2011-04-01 18:29:11',NULL);
insert  into `flx_user`(`id`,`username`,`password`,`city`,`status`,`login_time`,`logout_time`) values (70,'0818353610','444444','Denpasar','I','2011-04-01 19:43:11',NULL);

/*Table structure for table `flx_user_many_application` */

CREATE TABLE `flx_user_many_application` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_application` int(11) NOT NULL,
  `default` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

/*Data for the table `flx_user_many_application` */

insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (36,53,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (35,52,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (32,37,2,'N');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (51,37,4,'N');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (30,48,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (34,51,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (16,37,12,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (27,45,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (26,44,2,'N');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (25,44,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (21,40,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (24,43,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (23,42,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (37,54,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (49,62,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (39,55,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (40,56,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (41,37,3,'N');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (42,57,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (43,57,2,'N');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (44,58,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (45,59,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (46,60,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (48,61,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (52,58,5,'N');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (54,64,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (55,42,9,'N');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (56,65,12,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (57,65,9,'N');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (58,65,10,'N');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (59,65,12,'N');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (60,65,3,'N');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (61,66,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (63,67,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (64,68,1,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (65,69,12,'Y');
insert  into `flx_user_many_application`(`id`,`id_user`,`id_application`,`default`) values (66,70,12,'Y');

/*Table structure for table `sch_absensi` */

CREATE TABLE `sch_absensi` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_sch_profile` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('H','T') DEFAULT 'H',
  `id_profile_guru` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Data for the table `sch_absensi` */

insert  into `sch_absensi`(`id`,`id_sch_profile`,`tanggal`,`status`,`id_profile_guru`) values (6,38,'2011-04-01','H',49);
insert  into `sch_absensi`(`id`,`id_sch_profile`,`tanggal`,`status`,`id_profile_guru`) values (7,39,'2011-04-01','T',49);
insert  into `sch_absensi`(`id`,`id_sch_profile`,`tanggal`,`status`,`id_profile_guru`) values (8,40,'2011-04-01','H',49);
insert  into `sch_absensi`(`id`,`id_sch_profile`,`tanggal`,`status`,`id_profile_guru`) values (9,34,'2011-04-01','H',33);
insert  into `sch_absensi`(`id`,`id_sch_profile`,`tanggal`,`status`,`id_profile_guru`) values (10,35,'2011-04-01','T',33);
insert  into `sch_absensi`(`id`,`id_sch_profile`,`tanggal`,`status`,`id_profile_guru`) values (11,36,'2011-04-01','H',33);
insert  into `sch_absensi`(`id`,`id_sch_profile`,`tanggal`,`status`,`id_profile_guru`) values (12,38,'2011-04-05','H',49);
insert  into `sch_absensi`(`id`,`id_sch_profile`,`tanggal`,`status`,`id_profile_guru`) values (13,54,'2011-04-05','T',49);
insert  into `sch_absensi`(`id`,`id_sch_profile`,`tanggal`,`status`,`id_profile_guru`) values (14,39,'2011-04-05','H',49);

/*Table structure for table `sch_iuran_spp` */

CREATE TABLE `sch_iuran_spp` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_sch_profile` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` enum('L','T') DEFAULT 'L',
  `id_profile_guru` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `sch_iuran_spp` */

insert  into `sch_iuran_spp`(`id`,`id_sch_profile`,`tanggal`,`status`,`id_profile_guru`) values (3,38,'2011-04-01','T',49);
insert  into `sch_iuran_spp`(`id`,`id_sch_profile`,`tanggal`,`status`,`id_profile_guru`) values (4,39,'2011-04-01','L',49);
insert  into `sch_iuran_spp`(`id`,`id_sch_profile`,`tanggal`,`status`,`id_profile_guru`) values (5,40,'2011-04-01','T',49);
insert  into `sch_iuran_spp`(`id`,`id_sch_profile`,`tanggal`,`status`,`id_profile_guru`) values (6,39,'2011-04-05','T',49);
insert  into `sch_iuran_spp`(`id`,`id_sch_profile`,`tanggal`,`status`,`id_profile_guru`) values (7,54,'2011-04-05','L',49);

/*Table structure for table `sch_pelajaran` */

CREATE TABLE `sch_pelajaran` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_sch_sekolah` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

/*Data for the table `sch_pelajaran` */

insert  into `sch_pelajaran`(`id`,`id_sch_sekolah`,`nama`) values (15,9,'kimia');
insert  into `sch_pelajaran`(`id`,`id_sch_sekolah`,`nama`) values (16,7,'fisika');
insert  into `sch_pelajaran`(`id`,`id_sch_sekolah`,`nama`) values (17,10,'matematika');
insert  into `sch_pelajaran`(`id`,`id_sch_sekolah`,`nama`) values (18,10,'biologi');
insert  into `sch_pelajaran`(`id`,`id_sch_sekolah`,`nama`) values (19,11,'matematika');
insert  into `sch_pelajaran`(`id`,`id_sch_sekolah`,`nama`) values (20,11,'biologi');
insert  into `sch_pelajaran`(`id`,`id_sch_sekolah`,`nama`) values (21,8,'sejarah');
insert  into `sch_pelajaran`(`id`,`id_sch_sekolah`,`nama`) values (22,8,'matematika');
insert  into `sch_pelajaran`(`id`,`id_sch_sekolah`,`nama`) values (23,8,'fisika');
insert  into `sch_pelajaran`(`id`,`id_sch_sekolah`,`nama`) values (24,16,'matematika');
insert  into `sch_pelajaran`(`id`,`id_sch_sekolah`,`nama`) values (25,16,'biologi');
insert  into `sch_pelajaran`(`id`,`id_sch_sekolah`,`nama`) values (26,13,'matematika');

/*Table structure for table `sch_profile` */

CREATE TABLE `sch_profile` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_flx_user` int(11) NOT NULL DEFAULT '0',
  `no_induk` varchar(50) NOT NULL DEFAULT '0',
  `nama` varchar(50) DEFAULT NULL,
  `level` enum('G','S','A') NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

/*Data for the table `sch_profile` */

insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (27,54,'0',NULL,'A');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (47,0,'1116',NULL,'S');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (29,56,'12345',NULL,'G');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (30,59,'23456','Ada deh','S');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (31,0,'34568',NULL,'S');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (32,0,'55555',NULL,'S');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (33,42,'336699','SUHU','G');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (34,0,'221',NULL,'S');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (35,0,'222',NULL,'S');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (36,0,'223',NULL,'S');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (46,65,'120852508',NULL,'G');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (38,0,'1111','budi','S');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (39,0,'2222','Imam','S');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (40,0,'3333',NULL,'S');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (41,0,'99999',NULL,'S');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (42,0,'99998',NULL,'S');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (43,52,'0',NULL,'A');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (44,61,'123456',NULL,'G');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (49,0,'1234',NULL,'G');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (50,0,'1234',NULL,'G');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (51,0,'1',NULL,'S');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (53,0,'1234',NULL,'G');
insert  into `sch_profile`(`id`,`id_flx_user`,`no_induk`,`nama`,`level`) values (54,37,'1212','rafa','S');

/*Table structure for table `sch_profile_many_pelajaran` */

CREATE TABLE `sch_profile_many_pelajaran` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_sch_profile` int(11) NOT NULL,
  `id_sch_pelajaran` int(11) NOT NULL,
  `nilai` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=latin1;

/*Data for the table `sch_profile_many_pelajaran` */

insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (29,29,15,9);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (30,30,15,7);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (31,31,15,8);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (32,32,15,6);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (33,33,16,0);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (34,34,16,7);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (35,35,16,9);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (36,36,16,8);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (37,37,17,0);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (38,37,18,0);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (39,38,17,75);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (40,39,17,75);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (42,41,15,5);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (43,42,15,8);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (44,44,19,0);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (45,44,20,0);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (46,46,21,0);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (47,46,22,0);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (48,46,23,0);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (49,38,21,65);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (50,47,21,85);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (51,49,24,0);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (52,49,25,0);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (53,38,24,75);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (54,39,24,80);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (55,40,24,82);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (56,38,25,90);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (57,39,25,78);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (58,40,25,85);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (59,50,26,0);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (60,51,26,78);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (61,52,24,0);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (62,52,25,0);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (63,53,24,0);
insert  into `sch_profile_many_pelajaran`(`id`,`id_sch_profile`,`id_sch_pelajaran`,`nilai`) values (64,53,25,0);

/*Table structure for table `sch_sekolah` */

CREATE TABLE `sch_sekolah` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(250) DEFAULT NULL,
  `pin` varchar(6) NOT NULL,
  `kode` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

/*Data for the table `sch_sekolah` */

insert  into `sch_sekolah`(`id`,`nama`,`alamat`,`pin`,`kode`) values (7,'Smun 2 bandung','Jl sulawesi no 2','721221','SMUN2BDG');
insert  into `sch_sekolah`(`id`,`nama`,`alamat`,`pin`,`kode`) values (8,'Smun 10 surabaya','Jl tambang sari 2','356476','SMUN10SBY');
insert  into `sch_sekolah`(`id`,`nama`,`alamat`,`pin`,`kode`) values (9,'Avicena','Jakarta','3d9439','AVICENA');
insert  into `sch_sekolah`(`id`,`nama`,`alamat`,`pin`,`kode`) values (10,'Smun 5 bandung','Jl sumatera no 26','377809','SMUN5BDG');
insert  into `sch_sekolah`(`id`,`nama`,`alamat`,`pin`,`kode`) values (11,'SMA YKM Bandung','Tanjungsari','da3437','SMAYKMBDG');
insert  into `sch_sekolah`(`id`,`nama`,`alamat`,`pin`,`kode`) values (12,'Smun tanjungsari','Jl tanjungsari 25','8c955c','SMUNTJS');
insert  into `sch_sekolah`(`id`,`nama`,`alamat`,`pin`,`kode`) values (13,'Smunsa klaten','Jl. Merbabu no 13','a5c882','140184');
insert  into `sch_sekolah`(`id`,`nama`,`alamat`,`pin`,`kode`) values (14,'Smun 1 cimalaka','Jl licin','e10dd1','11112');
insert  into `sch_sekolah`(`id`,`nama`,`alamat`,`pin`,`kode`) values (15,'smaykm','tanjungsari','d672e5','SMAYKMBANDUNG');
insert  into `sch_sekolah`(`id`,`nama`,`alamat`,`pin`,`kode`) values (16,'SMU Negeri 14 Bandung','Di Bandung','6a9c78','SMUN14BDG');
insert  into `sch_sekolah`(`id`,`nama`,`alamat`,`pin`,`kode`) values (17,'smu 2 klaten','Jl klaten','080c2a','SMUDAKLA');
insert  into `sch_sekolah`(`id`,`nama`,`alamat`,`pin`,`kode`) values (18,'Smu 1 cimalaka','Jl licin cimalaka','4ffcef','E10DDI');

/*Table structure for table `sch_sekolah_many_profile` */

CREATE TABLE `sch_sekolah_many_profile` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_sch_sekolah` int(11) NOT NULL,
  `id_sch_profile` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;

/*Data for the table `sch_sekolah_many_profile` */

insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (25,7,28);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (26,8,28);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (27,9,27);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (28,9,29);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (29,9,30);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (30,9,31);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (31,9,32);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (32,7,33);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (33,7,34);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (34,7,35);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (35,7,36);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (36,10,28);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (37,10,37);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (38,16,38);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (39,16,39);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (40,16,40);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (41,9,41);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (42,9,42);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (43,11,43);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (44,11,44);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (45,8,46);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (46,8,47);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (47,12,46);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (48,13,45);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (49,14,46);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (50,15,43);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (51,16,48);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (52,16,49);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (53,13,50);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (54,17,45);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (55,13,51);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (56,18,46);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (57,16,52);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (58,16,53);
insert  into `sch_sekolah_many_profile`(`id`,`id_sch_sekolah`,`id_sch_profile`) values (59,16,54);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
