-- phpMyAdmin SQL Dump
-- version 4.2.9.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jun 11, 2015 at 10:40 AM
-- Server version: 5.5.40
-- PHP Version: 5.4.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `plataformas`
--

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

CREATE TABLE IF NOT EXISTS `agent` (
`agent_id` int(10) unsigned NOT NULL,
  `user` varchar(40) NOT NULL,
  `sfid` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `type` tinyint(3) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=1000002 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`agent_id`, `user`, `sfid`, `password`, `type`) VALUES
(1, 'conexion', '39440103', '39440103', 1),
(2, 'conexion', '39140041', '39140041', 1),
(1561, 'arvato', '18250018', '18250018', 1),
(1562, 'sitel', '59140008', '59140008', 1),
(1563, 'abante', '39994134', '39994134', 1),
(1564, 'madison', '19140038', '19140038', 1),
(1565, 'iccs', '59990015', '59990015', 1),
(1566, 'bosch', '39990025', '39990025', 1),
(1567, 'oest', '29140022', '29140022', 1),
(1568, 'arvato', '18250048', '18250048', 1),
(1569, 'sitel', '19990032', '19990032', 1),
(1570, 'transcom', '19990200', '19990200', 1),
(111111, 'direccion', 'direccion', 'plataformas', 9),
(999999, 'altabox', 'altabox', 'plataformas', 10),
(1000000, 'isgf', '19140109', '19140109', 1),
(1000001, 'transcom', '19990205', '19990205', 1);

-- --------------------------------------------------------

--
-- Table structure for table `alarm`
--

CREATE TABLE IF NOT EXISTS `alarm` (
  `id_alarm` smallint(5) unsigned NOT NULL,
  `type_alarm` tinyint(3) unsigned NOT NULL,
  `brand_alarm` smallint(5) unsigned NOT NULL,
  `code` varchar(45) DEFAULT NULL,
  `alarm` varchar(100) NOT NULL,
  `picture_url` varchar(200) DEFAULT NULL,
  `description` text,
  `units` smallint(5) unsigned NOT NULL DEFAULT '0',
  `status` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `alarm`
--

INSERT INTO `alarm` (`id_alarm`, `type_alarm`, `brand_alarm`, `code`, `alarm`, `picture_url`, `description`, `units`, `status`) VALUES
(7, 57, 5, 'P0600628', 'SG_P0600628 SHUNT PLUG 4W', '8goq9daufw8wgwggks.png', NULL, 0, 'Alta'),
(8, 33, 5, 'P0600695', 'Peak4W WH IL', 'rvx734anm3kgog4ow.png', NULL, 0, 'Alta'),
(9, 57, 5, 'P0601193', 'PSU 5V 4W with 5 outputs EU', 'hxw1i4jbm1kckcsg48.png', NULL, 0, 'Alta'),
(10, 31, 5, 'P0600163', 'GRIFFE pour TELEPHONE', 'ih65ix862hsgs44kok.png', NULL, 0, 'Alta'),
(11, 31, 5, 'P0600164', 'GRIFFE PR TABLETTES', '12zbxnqoxhhwowc0g4.png', NULL, 0, 'Alta'),
(12, 44, 5, 'P0600166', 'OUTIL POUR OUVRIR LES GRIFFES POUR', '2pthphdq4b6s84sc0w.png', NULL, 0, 'Alta'),
(13, 54, 5, 'P0600972', 'FLY support WH', 'mq1tzjzlvyssws0k40.png', NULL, 0, 'Alta'),
(14, 44, 5, 'P0600152', 'OUTIL OUVRE SENSOR ET PRODUITS QUIKFIX 2', 'tgfkphfjabk0k08wo.png', NULL, 0, 'Alta'),
(15, 34, 5, 'P0600301', 'CABLE 1m 6/6', 'mtv0yw0jrz4kwcgc48.png', NULL, 0, 'Alta'),
(16, 38, 5, 'P0610026', 'Charg. Lead 4W microUSB 8cm BK', '22j22axav31cg8s8s8.png', NULL, 0, 'Alta'),
(17, 37, 5, 'P0610094', 'Charg. Lead 4W microUSB 20cm BK', 'xbm3jb92xu8cw0g04g.png', NULL, 0, 'Alta'),
(18, 41, 5, 'P0600337', 'MASTER 4 INPUTS (MARCHE AVEC ALIM P0600245)', '4af2yajd8zok844oo.png', NULL, 0, 'Alta'),
(19, 43, 5, 'P0600811', 'PSU 5 2V4A GD BLM EU', '1uhfhkkufsw00kc44k.png', NULL, 0, 'Alta'),
(20, 32, 5, 'P0600342', 'iBOUTTON EXTERNE RALLONGE 1m 3 25 00 75 00', '2q05waeqt668so4ss0.png', NULL, 0, 'Alta'),
(21, 47, 5, 'P0600286', 'CLE IBUTTON ON OFF - NOIRE code nº 2', 'pcvw7i7anrkc440ck0.png', NULL, 0, 'Alta'),
(22, 48, 5, 'P0600287', 'CLE IBUTTON PROGRAMMATION ROUGE code nº 2', 'beimqjktm7wckkc088.png', NULL, 0, 'Alta'),
(23, 46, 5, 'P0600288', 'CLE IBUTTON MUTE JAUNE code nº 3', '68q3dwjk3xc08sckc.png', NULL, 0, 'Alta'),
(24, 55, 5, 'P0600343', 'TIGES PR GSM 54-64 mm- POUR P0600163 4 10 00 40', 'ghjys0ornj4ks0w48s.png', NULL, 0, 'Alta'),
(25, 55, 5, 'P0600344', 'TIGES PR GSM 64-74- POUR P0600163 2 10 00 20 00', 'dbc306wnqugoscoocw.png', NULL, 0, 'Alta'),
(26, 55, 5, 'P0600165', 'TIGES PR GSM 44-54mm - POUR P0600163 1 10 00 10 00', 'm0wbevarirk40o00c.png', NULL, 0, 'Alta'),
(27, 55, 5, 'P0600679', 'TIGES PR GSM 74-84- POUR P0600163 1 14 00 14 00', '2b4vq66ihwcg0s4ooo.png', NULL, 0, 'Alta'),
(28, 56, 5, 'P0600581', 'Tiges pour tablette 205-225', '32d5feavm6gwc44k88.png', NULL, 0, 'Alta'),
(29, 56, 5, 'P0600580', 'Tiges pour tablette 225-245', '2c87gxynxspwsgk0s4.png', NULL, 0, 'Alta'),
(30, 56, 5, 'P0600579', 'Tiges pour tablette 245 - 265', '2601o9qu9cbos0go.png', NULL, 0, 'Alta'),
(31, 57, 5, 'P0600939', 'PY4W sensor drawer PA rev B', 'm6bq5wq3ks0sc844s.png', NULL, 0, 'Alta'),
(32, 36, 5, 'P0610047', 'Charg. Lead 4w microusb 8cm for apple', '5xdrvu9jrh4wo40s80.png', NULL, 0, 'Alta'),
(33, 50, 5, 'P0620028', 'Replac. sticker for Master4', 'rfyus3sm3zkosoo88.png', NULL, 0, 'Alta'),
(34, 51, 5, 'P0620047', 'replac. sticker PY4W sensor', 'o0dtry1aov4k4kggs8.png', NULL, 0, 'Alta'),
(35, 57, 2, '5201-1001-00', 'VP-1084B/1175B DEFCON Plate', '1qncfd69czi84k448w.png', NULL, 0, 'Alta'),
(36, 57, 2, 'AD-157-20', 'Adhesive G5 Universal Sensor (20pk)', '4lqvfh5fqo840g8c4g.png', NULL, 0, 'Alta'),
(37, 57, 2, 'AD-158-25', 'Adhesive D-Sensor (25pk)', 'fmjwwkiocvswsgko40.png', NULL, 0, 'Alta'),
(38, 57, 2, 'K-CELLBKT-01', 'DEFCON D-Sensor Boot & Bkt', '36c0hawkzracokg88.png', NULL, 0, 'Alta'),
(39, 57, 2, 'K-DEFCON-02', 'DEFCON D-Sensor Kit  w/Bracket  Black', '7agk4p8e28sggkw4wk.png', NULL, 0, 'Alta'),
(40, 57, 2, 'K-DEFCON-05', 'DEFCON G5 Kit no Bracket Black', 'dhxeng9lrg0sockwo.png', NULL, 0, 'Alta'),
(41, 57, 2, 'K-TABBKT-G5', 'G5 Tablet Bracket', 'yp0ykhs725cgwc08c4.png', NULL, 0, 'Alta'),
(42, 57, 2, 'LV1005', 'LV Monitor Module LVRM', 'jky5vibrhi8kwck0go.png', NULL, 0, 'Alta'),
(43, 57, 2, 'LV1014', 'D-Sensor Black', 'qpoycfq2zeoww0scks.png', NULL, 0, 'Alta'),
(44, 57, 2, 'LV1031', 'D-Sensor Hardwired Black Micro USB', 'd22ck8tl3lcs0cw0sw.png', NULL, 0, 'Alta'),
(45, 57, 2, 'P11008', 'D-Sensor Coupler iPhone Lighting', 'bcsm8sxx6o84ckgso.png', NULL, 0, 'Alta'),
(46, 57, 2, 'P50001', 'G5 Power Coupler Micro USB', '13n7qn0um10gogs8ow.png', NULL, 0, 'Alta'),
(47, 57, 2, 'P50024', 'G5 Coupler Apple I5', '2c2y918wodq8888koo.png', NULL, 0, 'Alta'),
(48, 57, 2, 'P50025', 'G5 Power Coupler Micro USB Booster', 'q8qqpnrc4iswokks0.png', NULL, 0, 'Alta'),
(49, 57, 2, 'SWPH-00612-FMZ', 'Flat Phillips Machine Screw 6-32x3/4in', 'oprq9r879u88kso8w.png', NULL, 0, 'Alta'),
(50, 57, 2, 'SWPH-00620-PMZ', 'Flat Phillips Machine Screw 6-32X1-1/4', '9u0x7sqk4e80cs0s8w.png', NULL, 0, 'Alta'),
(51, 57, 2, 'V-103-D', '9-Volt Duracell Procell Battery', '37n9hm7phnqccgo00c.png', NULL, 0, 'Alta'),
(52, 57, 2, 'V-1206-RC', 'Closed Loop 6-UP Alarm Module PS', 'uavfnfws79c4gok4gk.png', NULL, 0, 'Alta'),
(53, 57, 2, 'V-34E', '24 Volt Power Supply (125 watts) Europe', 'cc3vejo4dlc8ksss4.png', NULL, 0, 'Alta'),
(54, 57, 2, 'V-37E', '24 Volt Power Aupply (45 watts) Europe', 'wm5xmkm796owg0wwgc.png', NULL, 0, 'Alta'),
(55, 57, 2, 'V-393', 'RJ Extender Cable', '153bib94hvr40og84c.png', NULL, 0, 'Alta'),
(56, 57, 2, 'V-500', 'Mini Sensor Coiled Cord  Black', '45zwvq2alukg008s4c.png', NULL, 0, 'Alta'),
(57, 57, 2, 'V-52', 'Adhesive Tape for V-500 Mini Sensor (50pk)', '5ncjddegwqgww8c8c.png', NULL, 0, 'Alta'),
(58, 57, 2, 'V-60V-10', '1" x 2" Velcro Set (10pk)', '16g8svagh47484wgwk.png', NULL, 0, 'Alta'),
(59, 57, 2, 'V-8002', 'G5 Universal Sensor', 'nu4bt5i472848swcs.png', NULL, 0, 'Alta'),
(60, 57, 2, 'V-916D', 'Duracell Procell AA Battery (6pk)', '28cfcdlvtg9wsk0s48.png', NULL, 0, 'Alta'),
(61, 57, 2, 'V-99PX-01', '2P Alarm Module w/RKS', 'b5vx5nzhx20cc0k0sw.png', NULL, 0, 'Alta'),
(62, 57, 2, 'VP-1068', 'G5 Monitor Module', '2es1yslfyvk0cg4w4s.png', NULL, 0, 'Alta'),
(63, 57, 2, 'VP-1084BW', 'G5 Tall Sloped Riser  Screw Mount  Black', '2n7zlyh1qry8k4040k.png', NULL, 0, 'Alta'),
(64, 57, 2, 'VP-1092', 'G5 Removal Tool', '1j4iy1ohg97o808ogo.png', NULL, 0, 'Alta'),
(65, 57, 2, 'VP-1096', 'D-Sensor Removal Tool', 'oodnlykbtis8ogosgg.png', NULL, 0, 'Alta'),
(66, 57, 2, 'VP-1175B', 'Straight Riser  Screw Mount  Black', '35kvk7rbdyw4okco84.png', NULL, 0, 'Alta'),
(67, 57, 2, 'VP-1175BW', 'Straight Riser  Screw Mount White', 'shhscyj6u28ok848k.png', NULL, 0, 'Alta'),
(68, 57, 2, 'VPG BANK BAG', 'VPG Tools Bag', '9ves27cxouwcoog8s.png', NULL, 0, 'Alta'),
(69, 57, 2, 'V-RKS-WB', 'Remote KeySwitch White/Blue', 'xscvsf78hkgscokook.png', NULL, 0, 'Alta'),
(70, 57, 2, 'V-T41', 'Security Screw Driver -', 'kvdn1v4gtu88w0og0k.png', NULL, 0, 'Alta'),
(71, 57, 2, 'V-T900', 'Security Screw Driver - 1/8"', 'iy3bdv5aqhsgowgkg8.png', NULL, 0, 'Alta'),
(72, 57, 2, 'V-T99', 'Small Phillips Screwdriver', 'd20jfbt3zmogsoocks.png', NULL, 0, 'Alta'),
(73, 45, 1, 'AF4400', 'IR Key', '', '', 0, 'Alta'),
(74, 40, 1, 'PK4401', 'IR-2 Programming Station', '', '', 0, 'Alta'),
(75, 43, 1, 'PS5VMU-EU', '5.3V Power Supply - Micro USB - EU', '', '', 0, 'Alta'),
(76, 41, 1, 'MP104-W', '4 PORT POD w/IR EXTENDER - WHITE  Gold LED', '', '', 0, 'Alta'),
(77, 43, 1, 'YLR52', 'POWER SUPPLY', '', '', 0, 'Alta'),
(78, 57, 1, 'YC11', 'YC CABLE', '', '', 0, 'Alta'),
(79, 57, 1, 'HS124', 'HS100 BUGLE B 9 5MM-19MM', '', '', 0, 'Alta'),
(80, 52, 1, '00.LA', 'Rosca de fijación trasera', '', '', 0, 'Alta'),
(81, 33, 1, 'HS110-W', 'HS100 HH PUCK - WHITE', '', '', 0, 'Alta'),
(82, 53, 1, 'HS130-W', 'HS100 HH SENSOR SHORT ARMS - MICRO USB - WHITE', '', '', 0, 'Alta'),
(83, 33, 1, 'HS111-W', 'HS100 TAB PUCK - WHITE', '', '', 0, 'Alta'),
(84, 53, 1, 'HS134-W', 'HS100 TAB SENSOR - MICRO USB - WHITE.', '', '', 0, 'Alta'),
(85, 49, 1, 'ADH2020', 'HS100 HH Puck Adhesive Replacement Kit  20 count', '', '', 0, 'Alta'),
(86, 49, 1, 'ADH2022', 'HS100 HH Sensor Adhesive Replacement Kit  20 count', '', '', 0, 'Alta'),
(87, 49, 1, 'ADH2021', 'HS100 TABLET PUCK ADHESIVE REPLACEMENT KIT  20 CNT', '', '', 0, 'Alta'),
(88, 49, 1, 'ADH2023', 'HS100 TABLET SENSOR ADHESIVE REPLACEMENT KIT 20CNT', '', '', 0, 'Alta'),
(89, 49, 1, 'EC9178', 'REPLACEMENT ADH. - S940 HEADPHONE SENSOR (20 EACH)', '', '', 0, 'Alta'),
(90, 49, 1, 'ADH2015', 'ZIPS POWER HH MEDALLION REPLACEMENT ADHESIVE 20CNT', '', '', 0, 'Alta'),
(91, 42, 1, 'MP103-W', '8 PORT POD w/IR EXTENDER - WHITE  Gold LED', '', '', 0, 'Alta'),
(92, 53, 1, 'HS146-W', 'HH SENSOR SHORT ARMS - MICRO USB - WHITE Angled sensor', '', '', 0, 'Alta'),
(94, 45, 1, 'PK4400', 'IR2 Key', '', '', 0, 'Alta'),
(95, 39, 1, 'PK4404', 'IR-2 Charger (4 port)', '', '', 0, 'Alta'),
(96, 43, 1, 'PS18VB2-EU', '18V Power Supply - 2.5 mm Barrel - EU', '', '', 0, 'Alta'),
(97, 44, 1, 'AF6313', 'Multitools', '', '', 0, 'Alta'),
(98, 33, 1, 'MP021-W', 'HH TAB NO LIFT ANGLED PUCK - WHITE', '', '', 0, 'Alta'),
(99, 53, 1, 'MP623-W', 'HS200 TABLET SENSOR MODIFIED - POWER PORT - Portrait WHITE', '', '', 0, 'Alta'),
(100, 53, 1, 'MP624-W', 'HS200 TABLET SENSOR MODIFIED - POWER PORT - Landscape WHITE', '', '', 0, 'Alta'),
(101, 53, 1, 'MP625-W', 'HS200 HH SENSOR STANDARD ARMS MODIFIED - MICRO USB - WHITE', '', '', 0, 'Alta'),
(102, 53, 1, 'MP626-W', 'HS200 HH SENSOR STANDARD ARMS MODIFIED - Apple 8 Pin - WHITE', '', '', 0, 'Alta'),
(103, 34, 1, 'TC708', 'MICRO USB POWERED LTO2/S940HP CONN', '', '', 0, 'Alta'),
(104, 49, 1, 'ADH2007', 'MED ROUND ADHESIVE WITH HOLE (20 COUNT)', '', '', 0, 'Alta'),
(105, 49, 1, 'ADH2008', 'LARGE ROUND ADHESIVE WITH HOLE (20 COUNT)', '', '', 0, 'Alta');

-- --------------------------------------------------------

--
-- Table structure for table `alarms_almacen`
--

CREATE TABLE IF NOT EXISTS `alarms_almacen` (
`id_alarms_almacen` mediumint(8) unsigned NOT NULL,
  `id_alarm` smallint(5) unsigned NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `barcode` varchar(45) DEFAULT NULL,
  `description` text,
  `status` enum('En stock','Reservado','Enviado','Baja') NOT NULL DEFAULT 'En stock'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `alarms_device_display`
--

CREATE TABLE IF NOT EXISTS `alarms_device_display` (
`id_alarms_device_display` mediumint(8) unsigned NOT NULL,
  `client_type_pds` tinyint(3) unsigned NOT NULL,
  `id_device` smallint(5) unsigned NOT NULL,
  `id_display` mediumint(8) unsigned NOT NULL,
  `id_alarm` smallint(5) unsigned NOT NULL,
  `description` text,
  `status` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `alarms_device_pds`
--

CREATE TABLE IF NOT EXISTS `alarms_device_pds` (
`id_alarms_device_pds` mediumint(8) unsigned NOT NULL,
  `client_type_pds` tinyint(3) unsigned NOT NULL,
  `id_pds` smallint(5) unsigned NOT NULL,
  `id_devices_pds` mediumint(8) unsigned NOT NULL,
  `id_displays_pds` mediumint(8) unsigned NOT NULL,
  `id_alarm` smallint(5) unsigned NOT NULL,
  `description` text,
  `status` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `alarms_display`
--

CREATE TABLE IF NOT EXISTS `alarms_display` (
`id_alarms_display` mediumint(8) unsigned NOT NULL,
  `client_type_pds` tinyint(3) unsigned NOT NULL,
  `id_display` mediumint(8) unsigned NOT NULL,
  `id_alarm` smallint(5) unsigned NOT NULL,
  `description` text,
  `status` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `alarms_display_pds`
--

CREATE TABLE IF NOT EXISTS `alarms_display_pds` (
`id_alarms_display_pds` mediumint(8) unsigned NOT NULL,
  `client_type_pds` tinyint(3) unsigned NOT NULL,
  `id_pds` smallint(5) unsigned NOT NULL,
  `id_displays_pds` mediumint(8) unsigned NOT NULL,
  `id_alarm` smallint(5) unsigned NOT NULL,
  `description` text,
  `status` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `brand_alarm`
--

CREATE TABLE IF NOT EXISTS `brand_alarm` (
`id_brand_alarm` smallint(5) unsigned NOT NULL,
  `brand` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `brand_alarm`
--

INSERT INTO `brand_alarm` (`id_brand_alarm`, `brand`) VALUES
(1, 'GSP'),
(5, 'Inovshop'),
(2, 'VPG');

-- --------------------------------------------------------

--
-- Table structure for table `brand_device`
--

CREATE TABLE IF NOT EXISTS `brand_device` (
`id_brand_device` smallint(5) unsigned NOT NULL,
  `brand` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `brand_device`
--

INSERT INTO `brand_device` (`id_brand_device`, `brand`) VALUES
(9, 'Apple'),
(18, 'BlackBerry'),
(10, 'HTC '),
(11, 'Huawei'),
(12, 'LG'),
(13, 'Motorola'),
(14, 'Nokia'),
(15, 'Orange'),
(16, 'Samsung'),
(17, 'Sony');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
`id_chat` int(10) unsigned NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_incidencia` int(10) unsigned NOT NULL,
  `agent` varchar(100) NOT NULL,
  `texto` text NOT NULL,
  `foto` varchar(200) DEFAULT NULL,
  `status` enum('Nuevo','Old','Privado') NOT NULL DEFAULT 'Nuevo'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id_chat`, `fecha`, `id_incidencia`, `agent`, `texto`, `foto`, `status`) VALUES
(1, '2015-05-21 10:26:36', 1, '100001', 'El instalador ha venido esta mañana.', NULL, 'Nuevo'),
(2, '2015-05-21 10:27:04', 2, '100001', 'El teléfono también presenta desperfectos en la pantalla.', NULL, 'Nuevo'),
(3, '2015-05-21 10:44:44', 2, '100001', 'Seguimos esperando una resolución.', NULL, 'Nuevo'),
(4, '2015-05-21 10:45:19', 2, '100001', 'Foto del terminal dañado.', NULL, 'Nuevo'),
(5, '2015-05-21 11:13:47', 3, '100001', 'EStamos a la espera de la visita del técnico.', NULL, 'Nuevo');

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('77ae31fbcbc8d74d23e072580e630dc4', '212.89.16.245', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.124 Safari/537.36', 1433944048, 'a:4:{s:9:"user_data";s:0:"";s:4:"sfid";s:7:"altabox";s:4:"type";s:2:"10";s:10:"xcrud_sess";a:2:{s:40:"51e960a5c59231961c94bce2d3be052f408913d0";a:130:{s:3:"key";s:40:"75f41c4eddcafe969cac56f7a9fbf4453c2e01f5";s:4:"time";i:1433943777;s:5:"table";s:8:"type_pds";s:10:"table_name";s:4:"Tipo";s:5:"where";a:0:{}s:8:"order_by";a:0:{}s:8:"relation";a:1:{s:24:"type_pds.client_type_pds";a:13:{s:7:"rel_tbl";s:6:"client";s:9:"rel_alias";s:15:"alias1499808795";s:9:"rel_field";s:9:"id_client";s:8:"rel_name";s:6:"client";s:9:"rel_where";a:0:{}s:13:"rel_separator";s:1:" ";s:8:"order_by";b:0;s:5:"multi";b:0;s:5:"table";s:8:"type_pds";s:5:"field";s:15:"client_type_pds";s:4:"tree";b:0;s:12:"depend_field";s:0:"";s:9:"depend_on";s:0:"";}}s:13:"fields_create";a:4:{s:24:"type_pds.client_type_pds";a:3:{s:5:"table";s:8:"type_pds";s:5:"field";s:15:"client_type_pds";s:3:"tab";b:0;}s:12:"type_pds.pds";a:3:{s:5:"table";s:8:"type_pds";s:5:"field";s:3:"pds";s:3:"tab";b:0;}s:20:"type_pds.description";a:3:{s:5:"table";s:8:"type_pds";s:5:"field";s:11:"description";s:3:"tab";b:0;}s:15:"type_pds.status";a:3:{s:5:"table";s:8:"type_pds";s:5:"field";s:6:"status";s:3:"tab";b:0;}}s:11:"fields_edit";a:4:{s:24:"type_pds.client_type_pds";a:3:{s:5:"table";s:8:"type_pds";s:5:"field";s:15:"client_type_pds";s:3:"tab";b:0;}s:12:"type_pds.pds";a:3:{s:5:"table";s:8:"type_pds";s:5:"field";s:3:"pds";s:3:"tab";b:0;}s:20:"type_pds.description";a:3:{s:5:"table";s:8:"type_pds";s:5:"field";s:11:"description";s:3:"tab";b:0;}s:15:"type_pds.status";a:3:{s:5:"table";s:8:"type_pds";s:5:"field";s:6:"status";s:3:"tab";b:0;}}s:11:"fields_view";a:4:{s:24:"type_pds.client_type_pds";a:3:{s:5:"table";s:8:"type_pds";s:5:"field";s:15:"client_type_pds";s:3:"tab";b:0;}s:12:"type_pds.pds";a:3:{s:5:"table";s:8:"type_pds";s:5:"field";s:3:"pds";s:3:"tab";b:0;}s:20:"type_pds.description";a:3:{s:5:"table";s:8:"type_pds";s:5:"field";s:11:"description";s:3:"tab";b:0;}s:15:"type_pds.status";a:3:{s:5:"table";s:8:"type_pds";s:5:"field";s:6:"status";s:3:"tab";b:0;}}s:11:"fields_list";a:3:{s:24:"type_pds.client_type_pds";a:2:{s:5:"table";s:8:"type_pds";s:5:"field";s:15:"client_type_pds";}s:12:"type_pds.pds";a:2:{s:5:"table";s:8:"type_pds";s:5:"field";s:3:"pds";}s:15:"type_pds.status";a:2:{s:5:"table";s:8:"type_pds";s:5:"field";s:6:"status";}}s:6:"labels";a:4:{s:24:"type_pds.client_type_pds";s:7:"Cliente";s:12:"type_pds.pds";s:4:"Tipo";s:20:"type_pds.description";s:11:"Comentarios";s:15:"type_pds.status";s:6:"Estado";}s:13:"columns_names";a:3:{s:24:"type_pds.client_type_pds";s:7:"Cliente";s:12:"type_pds.pds";s:4:"Tipo";s:15:"type_pds.status";s:6:"Estado";}s:9:"is_create";b:1;s:7:"is_edit";b:1;s:9:"is_remove";b:1;s:6:"is_csv";b:1;s:7:"buttons";a:0:{}s:19:"validation_required";a:4:{s:20:"type_pds.id_type_pds";i:1;s:24:"type_pds.client_type_pds";i:1;s:12:"type_pds.pds";i:1;s:15:"type_pds.status";i:1;}s:18:"validation_pattern";a:0:{}s:13:"before_insert";a:0:{}s:13:"before_update";a:0:{}s:13:"before_remove";a:0:{}s:12:"after_insert";a:0:{}s:12:"after_update";a:0:{}s:12:"after_remove";a:0:{}s:10:"field_type";a:5:{s:20:"type_pds.id_type_pds";s:3:"int";s:24:"type_pds.client_type_pds";s:8:"relation";s:12:"type_pds.pds";s:4:"text";s:20:"type_pds.description";s:10:"texteditor";s:15:"type_pds.status";s:6:"select";}s:10:"field_attr";a:5:{s:20:"type_pds.id_type_pds";a:1:{s:9:"maxlength";i:3;}s:24:"type_pds.client_type_pds";a:1:{s:9:"maxlength";i:3;}s:12:"type_pds.pds";a:1:{s:9:"maxlength";i:100;}s:20:"type_pds.description";a:0:{}s:15:"type_pds.status";a:2:{s:9:"maxlength";i:0;s:6:"values";s:13:"''Alta'',''Baja''";}}s:5:"limit";i:10;s:10:"limit_list";a:5:{i:0;i:10;i:1;s:2:"25";i:2;s:2:"50";i:3;s:3:"100";i:4;s:3:"all";}s:10:"column_cut";i:50;s:15:"column_cut_list";a:0:{}s:9:"no_editor";a:0:{}s:21:"show_primary_ai_field";b:0;s:22:"show_primary_ai_column";b:0;s:8:"disabled";a:0:{}s:8:"readonly";a:0:{}s:9:"benchmark";b:0;s:14:"search_pattern";a:2:{i:0;s:1:"%";i:1;s:1:"%";}s:10:"connection";b:0;s:14:"remove_confirm";b:1;s:13:"upload_folder";a:0:{}s:13:"upload_config";a:0:{}s:8:"pass_var";a:0:{}s:14:"reverse_fields";a:4:{s:4:"list";b:0;s:6:"create";b:0;s:4:"edit";b:0;s:4:"view";b:0;}s:9:"no_quotes";a:0:{}s:20:"inner_table_instance";a:0:{}s:11:"inner_where";a:0:{}s:6:"unique";a:1:{s:20:"type_pds.id_type_pds";b:1;}s:5:"theme";s:9:"bootstrap";s:12:"is_duplicate";b:0;s:11:"links_label";a:0:{}s:12:"emails_label";a:0:{}s:3:"sum";a:0:{}s:12:"alert_create";N;s:10:"alert_edit";N;s:9:"is_search";b:1;s:8:"is_print";b:1;s:13:"is_pagination";b:1;s:12:"is_limitlist";b:1;s:11:"is_sortable";b:1;s:7:"is_list";b:1;s:9:"subselect";a:0:{}s:16:"subselect_before";a:0:{}s:9:"highlight";a:0:{}s:13:"highlight_row";a:0:{}s:5:"modal";a:0:{}s:12:"column_class";a:0:{}s:9:"no_select";a:0:{}s:8:"is_inner";b:0;s:4:"join";a:0:{}s:11:"fk_relation";a:0:{}s:8:"is_title";b:1;s:10:"is_numbers";b:1;s:8:"language";s:2:"es";s:12:"field_params";a:0:{}s:17:"mass_alert_create";a:0:{}s:15:"mass_alert_edit";a:0:{}s:15:"column_callback";a:0:{}s:14:"field_callback";a:0:{}s:14:"replace_insert";a:0:{}s:14:"replace_update";a:0:{}s:14:"replace_remove";a:0:{}s:20:"send_external_create";a:0:{}s:18:"send_external_edit";a:0:{}s:14:"column_pattern";a:0:{}s:10:"field_tabs";a:0:{}s:12:"field_marker";a:0:{}s:7:"is_view";b:1;s:13:"field_tooltip";a:0:{}s:13:"table_tooltip";a:0:{}s:14:"column_tooltip";a:0:{}s:14:"search_columns";a:0:{}s:14:"search_default";N;s:12:"column_width";a:0:{}s:6:"before";s:4:"list";s:13:"before_upload";a:0:{}s:12:"after_upload";a:0:{}s:12:"after_resize";a:0:{}s:11:"custom_vars";a:0:{}s:7:"tabdesc";a:0:{}s:11:"column_name";a:0:{}s:14:"upload_to_save";a:0:{}s:16:"upload_to_remove";a:0:{}s:8:"defaults";a:5:{s:20:"type_pds.id_type_pds";N;s:24:"type_pds.client_type_pds";N;s:12:"type_pds.pds";N;s:20:"type_pds.description";N;s:15:"type_pds.status";s:4:"Alta";}s:6:"search";i:0;s:11:"inner_value";b:0;s:9:"bit_field";a:0:{}s:11:"point_field";a:0:{}s:16:"buttons_position";s:5:"right";s:14:"grid_condition";a:0:{}s:9:"condition";a:0:{}s:11:"hide_button";a:0:{}s:8:"set_lang";a:0:{}s:8:"table_ro";b:0;s:17:"grid_restrictions";a:0:{}s:9:"load_view";a:4:{s:4:"list";s:19:"xcrud_list_view.php";s:6:"create";s:21:"xcrud_detail_view.php";s:4:"edit";s:21:"xcrud_detail_view.php";s:4:"view";s:21:"xcrud_detail_view.php";}s:6:"action";a:0:{}s:6:"prefix";s:0:"";s:5:"query";s:0:"";s:11:"default_tab";b:0;s:10:"strip_tags";b:1;s:11:"safe_output";b:0;s:11:"before_list";a:0:{}s:13:"before_create";a:0:{}s:11:"before_edit";a:0:{}s:11:"before_view";a:0:{}s:14:"lists_null_opt";b:1;s:13:"custom_fields";a:0:{}s:11:"date_format";a:2:{s:5:"php_d";s:5:"d.m.Y";s:5:"php_t";s:5:"H:i:s";}}s:40:"2a5709349bcf74d253b1b0307a93222baf1cb904";a:130:{s:3:"key";s:40:"1804f9ebb45e07cc1ee84396f4ff7181e973231e";s:4:"time";i:1433943798;s:5:"table";s:3:"pds";s:10:"table_name";s:6:"Tienda";s:5:"where";a:0:{}s:8:"order_by";a:0:{}s:8:"relation";a:10:{s:14:"pds.client_pds";a:13:{s:7:"rel_tbl";s:6:"client";s:9:"rel_alias";s:15:"alias1836289494";s:9:"rel_field";s:9:"id_client";s:8:"rel_name";s:6:"client";s:9:"rel_where";a:0:{}s:13:"rel_separator";s:1:" ";s:8:"order_by";b:0;s:5:"multi";b:0;s:5:"table";s:3:"pds";s:5:"field";s:10:"client_pds";s:4:"tree";b:0;s:12:"depend_field";s:0:"";s:9:"depend_on";s:0:"";}s:12:"pds.type_pds";a:13:{s:7:"rel_tbl";s:8:"type_pds";s:9:"rel_alias";s:14:"alias999771112";s:9:"rel_field";s:11:"id_type_pds";s:8:"rel_name";s:3:"pds";s:9:"rel_where";a:0:{}s:13:"rel_separator";s:1:" ";s:8:"order_by";b:0;s:5:"multi";b:0;s:5:"table";s:3:"pds";s:5:"field";s:8:"type_pds";s:4:"tree";b:0;s:12:"depend_field";s:0:"";s:9:"depend_on";s:0:"";}s:13:"pds.territory";a:13:{s:7:"rel_tbl";s:9:"territory";s:9:"rel_alias";s:14:"alias117722863";s:9:"rel_field";s:12:"id_territory";s:8:"rel_name";s:9:"territory";s:9:"rel_where";a:0:{}s:13:"rel_separator";s:1:" ";s:8:"order_by";b:0;s:5:"multi";b:0;s:5:"table";s:3:"pds";s:5:"field";s:9:"territory";s:4:"tree";b:0;s:12:"depend_field";s:0:"";s:9:"depend_on";s:0:"";}s:16:"pds.panelado_pds";a:13:{s:7:"rel_tbl";s:8:"panelado";s:9:"rel_alias";s:15:"alias1411792357";s:9:"rel_field";s:11:"id_panelado";s:8:"rel_name";s:12:"panelado_abx";s:9:"rel_where";a:0:{}s:13:"rel_separator";s:1:" ";s:8:"order_by";b:0;s:5:"multi";b:0;s:5:"table";s:3:"pds";s:5:"field";s:12:"panelado_pds";s:4:"tree";b:0;s:12:"depend_field";s:0:"";s:9:"depend_on";s:0:"";}s:12:"pds.type_via";a:13:{s:7:"rel_tbl";s:8:"type_via";s:9:"rel_alias";s:14:"alias485051438";s:9:"rel_field";s:11:"id_type_via";s:8:"rel_name";s:3:"via";s:9:"rel_where";a:0:{}s:13:"rel_separator";s:1:" ";s:8:"order_by";b:0;s:5:"multi";b:0;s:5:"table";s:3:"pds";s:5:"field";s:8:"type_via";s:4:"tree";b:0;s:12:"depend_field";s:0:"";s:9:"depend_on";s:0:"";}s:12:"pds.province";a:13:{s:7:"rel_tbl";s:8:"province";s:9:"rel_alias";s:15:"alias1691026182";s:9:"rel_field";s:11:"id_province";s:8:"rel_name";s:8:"province";s:9:"rel_where";a:0:{}s:13:"rel_separator";s:1:" ";s:8:"order_by";b:0;s:5:"multi";b:0;s:5:"table";s:3:"pds";s:5:"field";s:8:"province";s:4:"tree";b:0;s:12:"depend_field";s:0:"";s:9:"depend_on";s:0:"";}s:10:"pds.county";a:13:{s:7:"rel_tbl";s:6:"county";s:9:"rel_alias";s:14:"alias216451331";s:9:"rel_field";s:9:"id_county";s:8:"rel_name";s:6:"county";s:9:"rel_where";a:0:{}s:13:"rel_separator";s:1:" ";s:8:"order_by";b:0;s:5:"multi";b:0;s:5:"table";s:3:"pds";s:5:"field";s:6:"county";s:4:"tree";b:0;s:12:"depend_field";s:0:"";s:9:"depend_on";s:0:"";}s:26:"pds.contact_contact_person";a:13:{s:7:"rel_tbl";s:7:"contact";s:9:"rel_alias";s:15:"alias1710303481";s:9:"rel_field";s:10:"id_contact";s:8:"rel_name";s:7:"contact";s:9:"rel_where";a:0:{}s:13:"rel_separator";s:1:" ";s:8:"order_by";b:0;s:5:"multi";b:0;s:5:"table";s:3:"pds";s:5:"field";s:22:"contact_contact_person";s:4:"tree";b:0;s:12:"depend_field";s:0:"";s:9:"depend_on";s:0:"";}s:21:"pds.contact_in_charge";a:13:{s:7:"rel_tbl";s:7:"contact";s:9:"rel_alias";s:15:"alias1902544175";s:9:"rel_field";s:10:"id_contact";s:8:"rel_name";s:7:"contact";s:9:"rel_where";a:0:{}s:13:"rel_separator";s:1:" ";s:8:"order_by";b:0;s:5:"multi";b:0;s:5:"table";s:3:"pds";s:5:"field";s:17:"contact_in_charge";s:4:"tree";b:0;s:12:"depend_field";s:0:"";s:9:"depend_on";s:0:"";}s:22:"pds.contact_supervisor";a:13:{s:7:"rel_tbl";s:7:"contact";s:9:"rel_alias";s:15:"alias1203934285";s:9:"rel_field";s:10:"id_contact";s:8:"rel_name";s:7:"contact";s:9:"rel_where";a:0:{}s:13:"rel_separator";s:1:" ";s:8:"order_by";b:0;s:5:"multi";b:0;s:5:"table";s:3:"pds";s:5:"field";s:18:"contact_supervisor";s:4:"tree";b:0;s:12:"depend_field";s:0:"";s:9:"depend_on";s:0:"";}}s:13:"fields_create";a:26:{s:14:"pds.client_pds";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:10:"client_pds";s:3:"tab";b:0;}s:13:"pds.reference";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:9:"reference";s:3:"tab";b:0;}s:12:"pds.type_pds";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:8:"type_pds";s:3:"tab";b:0;}s:16:"pds.panelado_pds";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:12:"panelado_pds";s:3:"tab";b:0;}s:9:"pds.dispo";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:5:"dispo";s:3:"tab";b:0;}s:14:"pds.commercial";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:10:"commercial";s:3:"tab";b:0;}s:7:"pds.cif";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:3:"cif";s:3:"tab";b:0;}s:13:"pds.territory";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:9:"territory";s:3:"tab";b:0;}s:15:"pds.picture_url";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:11:"picture_url";s:3:"tab";b:0;}s:9:"pds.m2_fo";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:5:"m2_fo";s:3:"tab";b:0;}s:9:"pds.m2_bo";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:5:"m2_bo";s:3:"tab";b:0;}s:12:"pds.m2_total";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:8:"m2_total";s:3:"tab";b:0;}s:12:"pds.type_via";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:8:"type_via";s:3:"tab";b:0;}s:11:"pds.address";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:7:"address";s:3:"tab";b:0;}s:7:"pds.zip";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:3:"zip";s:3:"tab";b:0;}s:8:"pds.city";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:4:"city";s:3:"tab";b:0;}s:12:"pds.province";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:8:"province";s:3:"tab";b:0;}s:10:"pds.county";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:6:"county";s:3:"tab";b:0;}s:12:"pds.schedule";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:8:"schedule";s:3:"tab";b:0;}s:9:"pds.phone";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:5:"phone";s:3:"tab";b:0;}s:10:"pds.mobile";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:6:"mobile";s:3:"tab";b:0;}s:9:"pds.email";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:5:"email";s:3:"tab";b:0;}s:26:"pds.contact_contact_person";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:22:"contact_contact_person";s:3:"tab";b:0;}s:21:"pds.contact_in_charge";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:17:"contact_in_charge";s:3:"tab";b:0;}s:22:"pds.contact_supervisor";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:18:"contact_supervisor";s:3:"tab";b:0;}s:10:"pds.status";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:6:"status";s:3:"tab";b:0;}}s:11:"fields_edit";a:26:{s:14:"pds.client_pds";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:10:"client_pds";s:3:"tab";b:0;}s:13:"pds.reference";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:9:"reference";s:3:"tab";b:0;}s:12:"pds.type_pds";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:8:"type_pds";s:3:"tab";b:0;}s:16:"pds.panelado_pds";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:12:"panelado_pds";s:3:"tab";b:0;}s:9:"pds.dispo";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:5:"dispo";s:3:"tab";b:0;}s:14:"pds.commercial";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:10:"commercial";s:3:"tab";b:0;}s:7:"pds.cif";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:3:"cif";s:3:"tab";b:0;}s:13:"pds.territory";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:9:"territory";s:3:"tab";b:0;}s:15:"pds.picture_url";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:11:"picture_url";s:3:"tab";b:0;}s:9:"pds.m2_fo";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:5:"m2_fo";s:3:"tab";b:0;}s:9:"pds.m2_bo";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:5:"m2_bo";s:3:"tab";b:0;}s:12:"pds.m2_total";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:8:"m2_total";s:3:"tab";b:0;}s:12:"pds.type_via";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:8:"type_via";s:3:"tab";b:0;}s:11:"pds.address";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:7:"address";s:3:"tab";b:0;}s:7:"pds.zip";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:3:"zip";s:3:"tab";b:0;}s:8:"pds.city";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:4:"city";s:3:"tab";b:0;}s:12:"pds.province";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:8:"province";s:3:"tab";b:0;}s:10:"pds.county";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:6:"county";s:3:"tab";b:0;}s:12:"pds.schedule";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:8:"schedule";s:3:"tab";b:0;}s:9:"pds.phone";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:5:"phone";s:3:"tab";b:0;}s:10:"pds.mobile";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:6:"mobile";s:3:"tab";b:0;}s:9:"pds.email";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:5:"email";s:3:"tab";b:0;}s:26:"pds.contact_contact_person";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:22:"contact_contact_person";s:3:"tab";b:0;}s:21:"pds.contact_in_charge";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:17:"contact_in_charge";s:3:"tab";b:0;}s:22:"pds.contact_supervisor";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:18:"contact_supervisor";s:3:"tab";b:0;}s:10:"pds.status";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:6:"status";s:3:"tab";b:0;}}s:11:"fields_view";a:26:{s:14:"pds.client_pds";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:10:"client_pds";s:3:"tab";b:0;}s:13:"pds.reference";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:9:"reference";s:3:"tab";b:0;}s:12:"pds.type_pds";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:8:"type_pds";s:3:"tab";b:0;}s:16:"pds.panelado_pds";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:12:"panelado_pds";s:3:"tab";b:0;}s:9:"pds.dispo";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:5:"dispo";s:3:"tab";b:0;}s:14:"pds.commercial";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:10:"commercial";s:3:"tab";b:0;}s:7:"pds.cif";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:3:"cif";s:3:"tab";b:0;}s:13:"pds.territory";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:9:"territory";s:3:"tab";b:0;}s:15:"pds.picture_url";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:11:"picture_url";s:3:"tab";b:0;}s:9:"pds.m2_fo";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:5:"m2_fo";s:3:"tab";b:0;}s:9:"pds.m2_bo";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:5:"m2_bo";s:3:"tab";b:0;}s:12:"pds.m2_total";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:8:"m2_total";s:3:"tab";b:0;}s:12:"pds.type_via";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:8:"type_via";s:3:"tab";b:0;}s:11:"pds.address";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:7:"address";s:3:"tab";b:0;}s:7:"pds.zip";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:3:"zip";s:3:"tab";b:0;}s:8:"pds.city";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:4:"city";s:3:"tab";b:0;}s:12:"pds.province";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:8:"province";s:3:"tab";b:0;}s:10:"pds.county";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:6:"county";s:3:"tab";b:0;}s:12:"pds.schedule";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:8:"schedule";s:3:"tab";b:0;}s:9:"pds.phone";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:5:"phone";s:3:"tab";b:0;}s:10:"pds.mobile";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:6:"mobile";s:3:"tab";b:0;}s:9:"pds.email";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:5:"email";s:3:"tab";b:0;}s:26:"pds.contact_contact_person";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:22:"contact_contact_person";s:3:"tab";b:0;}s:21:"pds.contact_in_charge";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:17:"contact_in_charge";s:3:"tab";b:0;}s:22:"pds.contact_supervisor";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:18:"contact_supervisor";s:3:"tab";b:0;}s:10:"pds.status";a:3:{s:5:"table";s:3:"pds";s:5:"field";s:6:"status";s:3:"tab";b:0;}}s:11:"fields_list";a:7:{s:14:"pds.client_pds";a:2:{s:5:"table";s:3:"pds";s:5:"field";s:10:"client_pds";}s:13:"pds.reference";a:2:{s:5:"table";s:3:"pds";s:5:"field";s:9:"reference";}s:12:"pds.type_pds";a:2:{s:5:"table";s:3:"pds";s:5:"field";s:8:"type_pds";}s:16:"pds.panelado_pds";a:2:{s:5:"table";s:3:"pds";s:5:"field";s:12:"panelado_pds";}s:14:"pds.commercial";a:2:{s:5:"table";s:3:"pds";s:5:"field";s:10:"commercial";}s:13:"pds.territory";a:2:{s:5:"table";s:3:"pds";s:5:"field";s:9:"territory";}s:10:"pds.status";a:2:{s:5:"table";s:3:"pds";s:5:"field";s:6:"status";}}s:6:"labels";a:26:{s:14:"pds.client_pds";s:7:"Cliente";s:13:"pds.reference";s:4:"SFID";s:12:"pds.type_pds";s:4:"Tipo";s:13:"pds.territory";s:4:"Zona";s:16:"pds.panelado_pds";s:8:"Panelado";s:9:"pds.dispo";s:12:"Disposición";s:14:"pds.commercial";s:16:"Nombre comercial";s:7:"pds.cif";s:3:"CIF";s:15:"pds.picture_url";s:4:"Foto";s:9:"pds.m2_fo";s:15:"M2 front-office";s:9:"pds.m2_bo";s:14:"M2 back-office";s:12:"pds.m2_total";s:8:"M2 total";s:12:"pds.type_via";s:9:"Tipo vía";s:11:"pds.address";s:10:"Dirección";s:7:"pds.zip";s:4:"C.P.";s:8:"pds.city";s:6:"Ciudad";s:12:"pds.province";s:9:"Provincia";s:10:"pds.county";s:6:"CC.AA.";s:12:"pds.schedule";s:7:"Horario";s:9:"pds.phone";s:9:"Teléfono";s:10:"pds.mobile";s:6:"Móvil";s:9:"pds.email";s:5:"Email";s:26:"pds.contact_contact_person";s:8:"Contacto";s:21:"pds.contact_in_charge";s:9:"Encargado";s:22:"pds.contact_supervisor";s:10:"Supervisor";s:10:"pds.status";s:6:"Estado";}s:13:"columns_names";a:7:{s:14:"pds.client_pds";s:7:"Cliente";s:13:"pds.reference";s:4:"SFID";s:12:"pds.type_pds";s:4:"Tipo";s:16:"pds.panelado_pds";s:8:"Panelado";s:14:"pds.commercial";s:16:"Nombre comercial";s:13:"pds.territory";s:4:"Zona";s:10:"pds.status";s:6:"Estado";}s:9:"is_create";b:1;s:7:"is_edit";b:1;s:9:"is_remove";b:1;s:6:"is_csv";b:1;s:7:"buttons";a:0:{}s:19:"validation_required";a:6:{s:10:"pds.id_pds";i:1;s:14:"pds.client_pds";i:1;s:13:"pds.reference";i:1;s:9:"pds.dispo";i:1;s:14:"pds.commercial";i:1;s:10:"pds.status";i:1;}s:18:"validation_pattern";a:0:{}s:13:"before_insert";a:0:{}s:13:"before_update";a:0:{}s:13:"before_remove";a:0:{}s:12:"after_insert";a:0:{}s:12:"after_update";a:0:{}s:12:"after_remove";a:0:{}s:10:"field_type";a:27:{s:15:"pds.picture_url";s:5:"image";s:10:"pds.id_pds";s:3:"int";s:14:"pds.client_pds";s:8:"relation";s:13:"pds.reference";s:4:"text";s:12:"pds.type_pds";s:8:"relation";s:13:"pds.territory";s:8:"relation";s:16:"pds.panelado_pds";s:8:"relation";s:9:"pds.dispo";s:6:"select";s:14:"pds.commercial";s:4:"text";s:7:"pds.cif";s:4:"text";s:9:"pds.m2_fo";s:3:"int";s:9:"pds.m2_bo";s:3:"int";s:12:"pds.m2_total";s:3:"int";s:12:"pds.type_via";s:8:"relation";s:11:"pds.address";s:10:"texteditor";s:7:"pds.zip";s:4:"text";s:8:"pds.city";s:4:"text";s:12:"pds.province";s:8:"relation";s:10:"pds.county";s:8:"relation";s:12:"pds.schedule";s:10:"texteditor";s:9:"pds.phone";s:4:"text";s:10:"pds.mobile";s:4:"text";s:9:"pds.email";s:4:"text";s:26:"pds.contact_contact_person";s:8:"relation";s:21:"pds.contact_in_charge";s:8:"relation";s:22:"pds.contact_supervisor";s:8:"relation";s:10:"pds.status";s:6:"select";}s:10:"field_attr";a:27:{s:10:"pds.id_pds";a:1:{s:9:"maxlength";i:5;}s:14:"pds.client_pds";a:1:{s:9:"maxlength";i:3;}s:13:"pds.reference";a:1:{s:9:"maxlength";i:50;}s:12:"pds.type_pds";a:1:{s:9:"maxlength";i:3;}s:13:"pds.territory";a:1:{s:9:"maxlength";i:3;}s:16:"pds.panelado_pds";a:1:{s:9:"maxlength";i:3;}s:9:"pds.dispo";a:2:{s:9:"maxlength";i:0;s:6:"values";s:26:"''Normal'',''Inversa'',''Mixta''";}s:14:"pds.commercial";a:1:{s:9:"maxlength";i:200;}s:7:"pds.cif";a:1:{s:9:"maxlength";i:12;}s:15:"pds.picture_url";a:1:{s:9:"maxlength";i:200;}s:9:"pds.m2_fo";a:1:{s:9:"maxlength";i:10;}s:9:"pds.m2_bo";a:1:{s:9:"maxlength";i:10;}s:12:"pds.m2_total";a:1:{s:9:"maxlength";i:10;}s:12:"pds.type_via";a:1:{s:9:"maxlength";i:3;}s:11:"pds.address";a:0:{}s:7:"pds.zip";a:1:{s:9:"maxlength";i:10;}s:8:"pds.city";a:1:{s:9:"maxlength";i:200;}s:12:"pds.province";a:1:{s:9:"maxlength";i:6;}s:10:"pds.county";a:1:{s:9:"maxlength";i:4;}s:12:"pds.schedule";a:0:{}s:9:"pds.phone";a:1:{s:9:"maxlength";i:20;}s:10:"pds.mobile";a:1:{s:9:"maxlength";i:20;}s:9:"pds.email";a:1:{s:9:"maxlength";i:100;}s:26:"pds.contact_contact_person";a:1:{s:9:"maxlength";i:5;}s:21:"pds.contact_in_charge";a:1:{s:9:"maxlength";i:5;}s:22:"pds.contact_supervisor";a:1:{s:9:"maxlength";i:5;}s:10:"pds.status";a:2:{s:9:"maxlength";i:0;s:6:"values";s:13:"''Alta'',''Baja''";}}s:5:"limit";s:2:"10";s:10:"limit_list";a:5:{i:0;i:10;i:1;s:2:"25";i:2;s:2:"50";i:3;s:3:"100";i:4;s:3:"all";}s:10:"column_cut";i:50;s:15:"column_cut_list";a:0:{}s:9:"no_editor";a:0:{}s:21:"show_primary_ai_field";b:0;s:22:"show_primary_ai_column";b:0;s:8:"disabled";a:1:{s:13:"pds.reference";a:1:{s:4:"edit";i:1;}}s:8:"readonly";a:0:{}s:9:"benchmark";b:0;s:14:"search_pattern";a:2:{i:0;s:1:"%";i:1;s:1:"%";}s:10:"connection";b:0;s:14:"remove_confirm";b:1;s:13:"upload_folder";a:0:{}s:13:"upload_config";a:1:{s:15:"pds.picture_url";a:0:{}}s:8:"pass_var";a:0:{}s:14:"reverse_fields";a:4:{s:4:"list";b:0;s:6:"create";b:0;s:4:"edit";b:0;s:4:"view";b:0;}s:9:"no_quotes";a:0:{}s:20:"inner_table_instance";a:0:{}s:11:"inner_where";a:0:{}s:6:"unique";a:2:{s:10:"pds.id_pds";b:1;s:13:"pds.reference";b:1;}s:5:"theme";s:9:"bootstrap";s:12:"is_duplicate";b:0;s:11:"links_label";a:0:{}s:12:"emails_label";a:0:{}s:3:"sum";a:1:{s:12:"pds.m2_total";a:4:{s:5:"table";s:3:"pds";s:6:"column";s:8:"m2_total";s:5:"class";s:5:"m2_fo";s:6:"custom";s:5:"m2_bo";}}s:12:"alert_create";N;s:10:"alert_edit";N;s:9:"is_search";b:1;s:8:"is_print";b:1;s:13:"is_pagination";b:1;s:12:"is_limitlist";b:1;s:11:"is_sortable";b:1;s:7:"is_list";b:1;s:9:"subselect";a:0:{}s:16:"subselect_before";a:0:{}s:9:"highlight";a:0:{}s:13:"highlight_row";a:0:{}s:5:"modal";a:1:{s:15:"pds.picture_url";b:0;}s:12:"column_class";a:0:{}s:9:"no_select";a:0:{}s:8:"is_inner";b:0;s:4:"join";a:0:{}s:11:"fk_relation";a:0:{}s:8:"is_title";b:1;s:10:"is_numbers";b:1;s:8:"language";s:2:"es";s:12:"field_params";a:0:{}s:17:"mass_alert_create";a:0:{}s:15:"mass_alert_edit";a:0:{}s:15:"column_callback";a:0:{}s:14:"field_callback";a:0:{}s:14:"replace_insert";a:0:{}s:14:"replace_update";a:0:{}s:14:"replace_remove";a:0:{}s:20:"send_external_create";a:0:{}s:18:"send_external_edit";a:0:{}s:14:"column_pattern";a:0:{}s:10:"field_tabs";a:0:{}s:12:"field_marker";a:0:{}s:7:"is_view";b:1;s:13:"field_tooltip";a:0:{}s:13:"table_tooltip";a:0:{}s:14:"column_tooltip";a:0:{}s:14:"search_columns";a:0:{}s:14:"search_default";N;s:12:"column_width";a:0:{}s:6:"before";s:4:"list";s:13:"before_upload";a:0:{}s:12:"after_upload";a:0:{}s:12:"after_resize";a:0:{}s:11:"custom_vars";a:0:{}s:7:"tabdesc";a:0:{}s:11:"column_name";a:0:{}s:14:"upload_to_save";a:0:{}s:16:"upload_to_remove";a:0:{}s:8:"defaults";a:27:{s:15:"pds.picture_url";b:0;s:10:"pds.id_pds";N;s:14:"pds.client_pds";N;s:13:"pds.reference";N;s:12:"pds.type_pds";N;s:13:"pds.territory";N;s:16:"pds.panelado_pds";N;s:9:"pds.dispo";s:6:"Normal";s:14:"pds.commercial";N;s:7:"pds.cif";N;s:9:"pds.m2_fo";N;s:9:"pds.m2_bo";N;s:12:"pds.m2_total";N;s:12:"pds.type_via";N;s:11:"pds.address";N;s:7:"pds.zip";N;s:8:"pds.city";N;s:12:"pds.province";N;s:10:"pds.county";N;s:12:"pds.schedule";N;s:9:"pds.phone";N;s:10:"pds.mobile";N;s:9:"pds.email";N;s:26:"pds.contact_contact_person";N;s:21:"pds.contact_in_charge";N;s:22:"pds.contact_supervisor";N;s:10:"pds.status";s:4:"Alta";}s:6:"search";i:0;s:11:"inner_value";b:0;s:9:"bit_field";a:0:{}s:11:"point_field";a:0:{}s:16:"buttons_position";s:5:"right";s:14:"grid_condition";a:0:{}s:9:"condition";a:0:{}s:11:"hide_button";a:0:{}s:8:"set_lang";a:0:{}s:8:"table_ro";b:0;s:17:"grid_restrictions";a:0:{}s:9:"load_view";a:4:{s:4:"list";s:19:"xcrud_list_view.php";s:6:"create";s:21:"xcrud_detail_view.php";s:4:"edit";s:21:"xcrud_detail_view.php";s:4:"view";s:21:"xcrud_detail_view.php";}s:6:"action";a:0:{}s:6:"prefix";s:0:"";s:5:"query";s:0:"";s:11:"default_tab";b:0;s:10:"strip_tags";b:1;s:11:"safe_output";b:0;s:11:"before_list";a:0:{}s:13:"before_create";a:0:{}s:11:"before_edit";a:0:{}s:11:"before_view";a:0:{}s:14:"lists_null_opt";b:1;s:13:"custom_fields";a:0:{}s:11:"date_format";a:2:{s:5:"php_d";s:5:"d.m.Y";s:5:"php_t";s:5:"H:i:s";}}}}'),
('d0b08efd850d315f3dfe0e589259d65e', '194.140.177.204', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.81 Safari/537.36', 1433946584, 'a:5:{s:9:"user_data";s:0:"";s:4:"sfid";s:8:"19990205";s:6:"id_pds";s:2:"12";s:4:"type";s:1:"1";s:9:"logged_in";b:1;}');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
`id_client` tinyint(3) unsigned NOT NULL,
  `type_profile_client` tinyint(3) unsigned NOT NULL,
  `client` varchar(200) NOT NULL,
  `picture_url` varchar(200) DEFAULT NULL,
  `description` text,
  `status` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id_client`, `type_profile_client`, `client`, `picture_url`, `description`, `status`) VALUES
(1, 4, 'Orange', '18bn18463wdcog8.jpg', NULL, 'Alta'),
(2, 5, 'Focus on Emotions', NULL, NULL, 'Alta');

-- --------------------------------------------------------

--
-- Table structure for table `color_device`
--

CREATE TABLE IF NOT EXISTS `color_device` (
`id_color_device` tinyint(3) unsigned NOT NULL,
  `color_device` varchar(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `color_device`
--

INSERT INTO `color_device` (`id_color_device`, `color_device`) VALUES
(1, 'Black'),
(6, 'Blue'),
(3, 'Gray'),
(5, 'Green'),
(7, 'Purple'),
(8, 'Silver'),
(11, 'White'),
(10, 'Yellow');

-- --------------------------------------------------------

--
-- Table structure for table `complement_device`
--

CREATE TABLE IF NOT EXISTS `complement_device` (
`id_complement_device` tinyint(3) unsigned NOT NULL,
  `complement_device` varchar(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `complement_device`
--

INSERT INTO `complement_device` (`id_complement_device`, `complement_device`) VALUES
(1, 'Desconocido'),
(2, 'No'),
(3, 'Sí'),
(4, 'Solo cargador y cable de datos blanco'),
(5, 'Solo móvil'),
(6, 'Solo tablet');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
`id_contact` smallint(5) unsigned NOT NULL,
  `client_contact` tinyint(3) unsigned NOT NULL,
  `type_profile_contact` tinyint(3) unsigned NOT NULL,
  `contact` varchar(200) NOT NULL,
  `type_via` tinyint(3) unsigned DEFAULT NULL,
  `address` text,
  `zip` varchar(10) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `province` smallint(6) unsigned DEFAULT NULL,
  `county` tinyint(4) unsigned DEFAULT NULL,
  `schedule` text,
  `phone` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id_contact`, `client_contact`, `type_profile_contact`, `contact`, `type_via`, `address`, `zip`, `city`, `province`, `county`, `schedule`, `phone`, `mobile`, `email`, `status`) VALUES
(5, 2, 1, 'Instalador por defecto', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'no-reply@altabox.net', 'Alta'),
(9, 1, 5, 'Ana Lopez', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(10, 1, 5, 'Patricia Escudero', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(11, 1, 5, 'Mar Hernández', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(12, 1, 5, 'Sandra Longhi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(13, 1, 5, 'Monica Gonzalez', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(14, 1, 5, 'Antonio Ramos', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(15, 1, 5, 'Javier  Sanz', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(16, 1, 5, 'Ana Fe Sordo Galguera', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(17, 1, 5, 'Soraya Pizarro Arcos', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(18, 1, 5, 'Patricia Asyn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(19, 1, 5, 'Adolfo Alonso', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(20, 1, 5, 'Mónica Gutierrez', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(21, 1, 5, 'Belen Ramos', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '628717308', 'anabelen.ramos@abantebpo.com', 'Alta');

-- --------------------------------------------------------

--
-- Table structure for table `county`
--

CREATE TABLE IF NOT EXISTS `county` (
`id_county` tinyint(4) unsigned NOT NULL,
  `county` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `county`
--

INSERT INTO `county` (`id_county`, `county`) VALUES
(1, 'Andalucía'),
(2, 'Aragón'),
(3, 'Asturias, Principado de'),
(4, 'Balears, Illes'),
(5, 'Canarias'),
(6, 'Cantabria'),
(8, 'Castilla - La Mancha'),
(7, 'Castilla y León'),
(9, 'Catalunya'),
(18, 'Ceuta'),
(10, 'Comunitat Valenciana'),
(11, 'Extremadura'),
(12, 'Galicia'),
(13, 'Madrid, Comunidad de'),
(19, 'Melilla'),
(14, 'Murcia, Región de'),
(15, 'Navarra, Comunidad Foral de'),
(16, 'País Vasco'),
(17, 'Rioja, La');

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE IF NOT EXISTS `device` (
`id_device` smallint(5) unsigned NOT NULL,
  `type_device` tinyint(3) unsigned NOT NULL,
  `brand_device` smallint(5) unsigned NOT NULL,
  `device` varchar(100) NOT NULL,
  `brand_name` varchar(100) DEFAULT NULL,
  `picture_url` varchar(200) DEFAULT NULL,
  `description` text,
  `status` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `device`
--

INSERT INTO `device` (`id_device`, `type_device`, `brand_device`, `device`, `brand_name`, `picture_url`, `description`, `status`) VALUES
(31, 1, 11, 'Huawei Ascend G7 4G', 'HUAWEI G7-L01', '3lyfqz7f0e4gsco0g.png', NULL, 'Alta'),
(36, 1, 12, 'LG G3 4G', NULL, 'r0ag2ooiw68kog4kc.png', NULL, 'Alta'),
(39, 1, 13, 'Motorola Moto G', NULL, '14vzvc3gr96sg4gocg.png', NULL, 'Alta'),
(40, 1, 13, 'Motorola Moto X (2)', 'XT1092 (2)', '8s1enzvq988w08c84.png', NULL, 'Alta'),
(41, 1, 14, 'Nokia Lumia 1320', 'NOKIA 1320', 'ciynrwivme8g08ckcg.png', NULL, 'Alta'),
(42, 1, 14, 'Nokia 530', NULL, 'mb7cn2a8xsg8wcgwcg.png', NULL, 'Alta'),
(43, 1, 14, 'Nokia Lumia 635', 'RM-974/NOKIA635', 'ikaad5m6eb4swk4wkc.png', NULL, 'Alta'),
(44, 1, 14, 'Nokia Lumia 735', 'RM-1038', '25y0f521pitcss0cg4.png', NULL, 'Alta'),
(51, 1, 15, 'Orange Roya 4G', NULL, '1odzeb4re8sg0s8s8k.png', NULL, 'Alta'),
(52, 1, 16, 'Samsung Core', NULL, 'iggi7q26jmgcck480g.png', NULL, 'Alta'),
(53, 1, 16, 'Samsung Galaxy A3 4G', 'SM-A300XU', 'xed7q31k8sg0ogk04.png', NULL, 'Alta'),
(54, 1, 16, 'Samsung Galaxy Ace 4', 'SM-G357FZ', NULL, NULL, 'Alta'),
(61, 1, 16, 'Samsung Note 3', NULL, 'hlw3rt202g0kc8wkcg.png', NULL, 'Alta'),
(62, 1, 16, 'Samsung Note 4', 'SM-N910X', '401opriar6skkwg4s.png', NULL, 'Alta'),
(63, 1, 17, 'Sony Xperia E1', NULL, '1xrsq1kysb34gg48so.png', NULL, 'Alta'),
(64, 1, 17, 'Sony Xperia E3 4G', 'D2203', '2ly2xmicp6gwkskkoo.png', NULL, 'Alta'),
(65, 1, 17, 'Sony Xperia M2 4G', 'D2303', 'wtt1n7gxbe8swsgs0k.png', NULL, 'Alta'),
(66, 1, 17, 'Sony Xperia T3', 'D5103', '6x6rueg6d3c408wow.png', NULL, 'Alta'),
(67, 1, 17, 'Sony Xperia Z', 'C6603', '5fa023oh5ig4c8kkoc.png', NULL, 'Alta'),
(68, 1, 17, 'Sony Xperia Z2', 'D6503', '2oux9rmvw9q8wg800g.png', NULL, 'Alta'),
(69, 1, 17, 'Sony Xperia Z3 4G', 'D6603', 'cy8dwbk6yxkc80csg0.png', NULL, 'Alta'),
(70, 1, 17, 'Sony Xperia Z3 Compact', 'D5803', 'x2i0n8buekgwgsos0c.png', NULL, 'Alta'),
(73, 2, 12, 'LG G PAD 8.0 4G', 'LG-V480', '29ai6w1uas00w0g0cc.png', NULL, 'Alta'),
(74, 2, 16, 'Samsung Galaxy Tab 4', 'SM-T535', 'm6jec5185gggwccoo0.png', NULL, 'Alta'),
(75, 2, 16, 'Samsung Galaxy Tab Lite', NULL, 'zek01v9zsw00gskso.png', NULL, 'Alta'),
(76, 2, 16, 'Samsung Galaxy Tab S', NULL, 'm65a9sbx2w0kssw0gs.png', NULL, 'Alta'),
(77, 2, 16, 'Samsung Galaxy Tab 3 Lite 7" WiFi', NULL, 'ljrjblxcfnk4ckc88k.png', NULL, 'Alta'),
(78, 2, 17, 'Sony Xperia Tablet Z', NULL, '75h3kbl3w8cok0o04.png', NULL, 'Alta'),
(79, 2, 17, 'Sony Xperia Tablet Z2 4G', NULL, 'p8g1e32ya348g88wsk.png', NULL, 'Alta'),
(83, 1, 15, 'Orange Daytona', 'G510-0200', NULL, NULL, 'Alta'),
(84, 1, 15, 'Orange Gova', 'G535-L11', NULL, NULL, 'Alta'),
(85, 1, 13, 'Motorola XT1092', 'MOE14', NULL, NULL, 'Alta'),
(86, 1, 15, 'Orange Yumo', 'G740-L00', NULL, NULL, 'Alta'),
(87, 1, 17, 'Sony Xperia Z1', 'C6903', NULL, NULL, 'Alta'),
(88, 1, 17, 'Sony Xperia Z1 Compact', 'D5503', NULL, NULL, 'Alta'),
(89, 1, 16, 'Samsung Galaxy A5 4G', 'SM-A500XZ', NULL, NULL, 'Alta'),
(90, 1, 16, 'Samsung Galaxy S5 + Maq Fit', 'Samsung Galaxy S5 + Maq Fit', 'asyf54wz0ogsg8wo4o.png', NULL, 'Alta'),
(91, 1, 12, 'LG Spirit 4G', 'H440n', '5g97aoi92nc48gwo0.jpg', NULL, 'Alta'),
(93, 1, 16, 'Samsung  Galaxy S6', NULL, NULL, NULL, 'Alta'),
(94, 1, 16, 'Samsung  Galaxy S6 Edge', NULL, NULL, NULL, 'Alta'),
(96, 1, 17, 'Sony Xperia SP', NULL, NULL, NULL, 'Alta'),
(100, 1, 12, 'LG H955', NULL, NULL, NULL, 'Alta'),
(102, 2, 11, 'Huawei Mediapad T1 8.0 4G', NULL, NULL, NULL, 'Alta'),
(106, 1, 10, 'HTC Desire 820 4G', NULL, NULL, NULL, 'Alta'),
(107, 1, 15, 'Orange Stockholm', NULL, NULL, NULL, 'Alta'),
(108, 1, 14, 'Nokia Lumia 710', NULL, NULL, NULL, 'Alta'),
(109, 1, 14, 'Nokia Lumia 610', NULL, NULL, NULL, 'Alta'),
(110, 1, 14, 'Nokia Lumia 925', NULL, NULL, NULL, 'Alta'),
(111, 1, 14, 'Nokia C5', NULL, NULL, NULL, 'Alta'),
(112, 1, 14, 'Nokia Lumia 1321', NULL, NULL, NULL, 'Alta'),
(113, 1, 16, 'Samsung Galaxy Pro', NULL, NULL, NULL, 'Alta'),
(114, 1, 16, 'Samsung Galaxy Core', NULL, NULL, NULL, 'Alta'),
(115, 2, 16, 'Samsung Galaxy Tab 2 7"', NULL, NULL, NULL, 'Alta'),
(116, 2, 16, 'Samsung Galaxy Tab 2 10"', NULL, NULL, NULL, 'Alta'),
(117, 2, 16, 'Samsung Galaxy Tab 3 10"', NULL, NULL, NULL, 'Alta'),
(118, 2, 15, 'Samsung Galaxy Tab 10"', NULL, NULL, NULL, 'Alta'),
(119, 1, 15, 'Orange Rise 30 3G', NULL, '16iaemil1syokc0gkg.jpg', NULL, 'Alta');

-- --------------------------------------------------------

--
-- Table structure for table `devices_almacen`
--

CREATE TABLE IF NOT EXISTS `devices_almacen` (
`id_devices_almacen` mediumint(8) unsigned NOT NULL,
  `id_device` smallint(5) unsigned NOT NULL,
  `alta` date DEFAULT NULL,
  `IMEI` varchar(50) DEFAULT NULL,
  `mac` varchar(50) DEFAULT NULL,
  `serial` varchar(50) DEFAULT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `id_color_device` tinyint(3) unsigned DEFAULT NULL,
  `id_complement_device` tinyint(3) unsigned DEFAULT NULL,
  `id_status_device` tinyint(3) unsigned DEFAULT NULL,
  `id_status_packaging_device` tinyint(3) unsigned DEFAULT NULL,
  `picture_url_1` varchar(200) DEFAULT NULL,
  `picture_url_2` varchar(200) DEFAULT NULL,
  `picture_url_3` varchar(200) DEFAULT NULL,
  `description` text,
  `owner` varchar(5) DEFAULT NULL,
  `status` enum('En stock','Reservado','Enviado','Baja') NOT NULL DEFAULT 'En stock'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `devices_display`
--

CREATE TABLE IF NOT EXISTS `devices_display` (
`id_devices_display` smallint(5) unsigned NOT NULL,
  `client_panelado` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `id_display` tinyint(3) unsigned NOT NULL,
  `id_device` tinyint(3) unsigned NOT NULL,
  `position` tinyint(3) unsigned NOT NULL,
  `description` text,
  `status` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `devices_display`
--

INSERT INTO `devices_display` (`id_devices_display`, `client_panelado`, `id_display`, `id_device`, `position`, `description`, `status`) VALUES
(1, 1, 1, 53, 1, NULL, 'Alta'),
(2, 1, 1, 64, 2, NULL, 'Alta'),
(3, 1, 1, 36, 3, NULL, 'Alta'),
(4, 1, 1, 31, 4, NULL, 'Alta'),
(5, 1, 1, 89, 5, NULL, 'Alta'),
(6, 1, 1, 65, 6, NULL, 'Alta'),
(7, 1, 1, 91, 7, NULL, 'Alta'),
(8, 1, 1, 119, 8, NULL, 'Alta'),
(9, 1, 1, 93, 9, NULL, 'Alta'),
(10, 1, 1, 69, 10, NULL, 'Alta'),
(11, 1, 1, 106, 11, NULL, 'Alta'),
(12, 1, 1, 51, 12, NULL, 'Alta'),
(13, 1, 1, 77, 15, NULL, 'Alta'),
(14, 1, 1, 102, 14, NULL, 'Alta'),
(15, 1, 1, 79, 13, NULL, 'Alta'),
(16, 1, 1, 73, 16, NULL, 'Alta');

-- --------------------------------------------------------

--
-- Table structure for table `devices_pds`
--

CREATE TABLE IF NOT EXISTS `devices_pds` (
`id_devices_pds` mediumint(8) unsigned NOT NULL,
  `client_type_pds` tinyint(3) unsigned NOT NULL,
  `id_pds` smallint(5) unsigned NOT NULL,
  `id_displays_pds` mediumint(8) unsigned NOT NULL,
  `id_display` tinyint(3) unsigned NOT NULL,
  `alta` date DEFAULT NULL,
  `position` tinyint(3) unsigned NOT NULL,
  `id_device` smallint(5) unsigned NOT NULL,
  `IMEI` varchar(50) DEFAULT NULL,
  `mac` varchar(50) DEFAULT NULL,
  `serial` varchar(50) DEFAULT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `id_color_device` tinyint(3) unsigned DEFAULT NULL,
  `id_complement_device` tinyint(3) unsigned DEFAULT NULL,
  `id_status_device` tinyint(3) unsigned DEFAULT NULL,
  `id_status_packaging_device` tinyint(3) unsigned DEFAULT NULL,
  `picture_url_1` varchar(200) DEFAULT NULL,
  `picture_url_2` varchar(200) DEFAULT NULL,
  `picture_url_3` varchar(200) DEFAULT NULL,
  `description` text,
  `owner` varchar(5) DEFAULT NULL,
  `status` enum('Alta','Incidencia','SAT','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB AUTO_INCREMENT=373 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `devices_pds`
--

INSERT INTO `devices_pds` (`id_devices_pds`, `client_type_pds`, `id_pds`, `id_displays_pds`, `id_display`, `alta`, `position`, `id_device`, `IMEI`, `mac`, `serial`, `barcode`, `id_color_device`, `id_complement_device`, `id_status_device`, `id_status_packaging_device`, `picture_url_1`, `picture_url_2`, `picture_url_3`, `description`, `owner`, `status`) VALUES
(1, 1, 1, 1, 1, NULL, 1, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(2, 1, 1, 1, 1, NULL, 2, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(3, 1, 1, 1, 1, NULL, 3, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(4, 1, 1, 1, 1, NULL, 4, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(5, 1, 1, 1, 1, NULL, 5, 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(6, 1, 1, 1, 1, NULL, 6, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(7, 1, 1, 1, 1, NULL, 7, 91, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(8, 1, 1, 1, 1, NULL, 8, 119, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(9, 1, 1, 1, 1, NULL, 9, 93, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(10, 1, 1, 1, 1, NULL, 10, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(11, 1, 1, 1, 1, NULL, 11, 106, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(12, 1, 1, 1, 1, NULL, 12, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(13, 1, 1, 1, 1, NULL, 15, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(14, 1, 1, 1, 1, NULL, 14, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(15, 1, 1, 1, 1, NULL, 13, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(16, 1, 1, 1, 1, NULL, 16, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(32, 1, 2, 2, 1, NULL, 1, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(33, 1, 2, 2, 1, NULL, 2, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(34, 1, 2, 2, 1, NULL, 3, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(35, 1, 2, 2, 1, NULL, 4, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(36, 1, 2, 2, 1, NULL, 5, 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(37, 1, 2, 2, 1, NULL, 6, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(38, 1, 2, 2, 1, NULL, 7, 91, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(39, 1, 2, 2, 1, NULL, 8, 119, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(40, 1, 2, 2, 1, NULL, 9, 93, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(41, 1, 2, 2, 1, NULL, 10, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(42, 1, 2, 2, 1, NULL, 11, 106, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(43, 1, 2, 2, 1, NULL, 12, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(44, 1, 2, 2, 1, NULL, 15, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(45, 1, 2, 2, 1, NULL, 14, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(46, 1, 2, 2, 1, NULL, 13, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(47, 1, 2, 2, 1, NULL, 16, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(63, 1, 3, 3, 1, NULL, 1, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(64, 1, 3, 3, 1, NULL, 2, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(65, 1, 3, 3, 1, NULL, 3, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(66, 1, 3, 3, 1, NULL, 4, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(67, 1, 3, 3, 1, NULL, 5, 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(68, 1, 3, 3, 1, NULL, 6, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(69, 1, 3, 3, 1, NULL, 7, 91, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(70, 1, 3, 3, 1, NULL, 8, 119, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(71, 1, 3, 3, 1, NULL, 9, 93, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(72, 1, 3, 3, 1, NULL, 10, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(73, 1, 3, 3, 1, NULL, 11, 106, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(74, 1, 3, 3, 1, NULL, 12, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(75, 1, 3, 3, 1, NULL, 15, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(76, 1, 3, 3, 1, NULL, 14, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(77, 1, 3, 3, 1, NULL, 13, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(78, 1, 3, 3, 1, NULL, 16, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(94, 1, 4, 4, 1, NULL, 1, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(95, 1, 4, 4, 1, NULL, 2, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(96, 1, 4, 4, 1, NULL, 3, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(97, 1, 4, 4, 1, NULL, 4, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(98, 1, 4, 4, 1, NULL, 5, 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(99, 1, 4, 4, 1, NULL, 6, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(100, 1, 4, 4, 1, NULL, 7, 91, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(101, 1, 4, 4, 1, NULL, 8, 119, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(102, 1, 4, 4, 1, NULL, 9, 93, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(103, 1, 4, 4, 1, NULL, 10, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(104, 1, 4, 4, 1, NULL, 11, 106, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(105, 1, 4, 4, 1, NULL, 12, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(106, 1, 4, 4, 1, NULL, 15, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(107, 1, 4, 4, 1, NULL, 14, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(108, 1, 4, 4, 1, NULL, 13, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(109, 1, 4, 4, 1, NULL, 16, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(125, 1, 5, 5, 1, NULL, 1, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(126, 1, 5, 5, 1, NULL, 2, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(127, 1, 5, 5, 1, NULL, 3, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(128, 1, 5, 5, 1, NULL, 4, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(129, 1, 5, 5, 1, NULL, 5, 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(130, 1, 5, 5, 1, NULL, 6, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(131, 1, 5, 5, 1, NULL, 7, 91, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(132, 1, 5, 5, 1, NULL, 8, 119, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(133, 1, 5, 5, 1, NULL, 9, 93, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(134, 1, 5, 5, 1, NULL, 10, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(135, 1, 5, 5, 1, NULL, 11, 106, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(136, 1, 5, 5, 1, NULL, 12, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(137, 1, 5, 5, 1, NULL, 15, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(138, 1, 5, 5, 1, NULL, 14, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(139, 1, 5, 5, 1, NULL, 13, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(140, 1, 5, 5, 1, NULL, 16, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(156, 1, 6, 6, 1, NULL, 1, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(157, 1, 6, 6, 1, NULL, 2, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(158, 1, 6, 6, 1, NULL, 3, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(159, 1, 6, 6, 1, NULL, 4, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(160, 1, 6, 6, 1, NULL, 5, 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(161, 1, 6, 6, 1, NULL, 6, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(162, 1, 6, 6, 1, NULL, 7, 91, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(163, 1, 6, 6, 1, NULL, 8, 119, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(164, 1, 6, 6, 1, NULL, 9, 93, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(165, 1, 6, 6, 1, NULL, 10, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(166, 1, 6, 6, 1, NULL, 11, 106, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(167, 1, 6, 6, 1, NULL, 12, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(168, 1, 6, 6, 1, NULL, 15, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(169, 1, 6, 6, 1, NULL, 14, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(170, 1, 6, 6, 1, NULL, 13, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(171, 1, 6, 6, 1, NULL, 16, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(187, 1, 7, 7, 1, NULL, 1, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(188, 1, 7, 7, 1, NULL, 2, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(189, 1, 7, 7, 1, NULL, 3, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(190, 1, 7, 7, 1, NULL, 4, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(191, 1, 7, 7, 1, NULL, 5, 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(192, 1, 7, 7, 1, NULL, 6, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(193, 1, 7, 7, 1, NULL, 7, 91, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(194, 1, 7, 7, 1, NULL, 8, 119, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(195, 1, 7, 7, 1, NULL, 9, 93, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(196, 1, 7, 7, 1, NULL, 10, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(197, 1, 7, 7, 1, NULL, 11, 106, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(198, 1, 7, 7, 1, NULL, 12, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(199, 1, 7, 7, 1, NULL, 15, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(200, 1, 7, 7, 1, NULL, 14, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(201, 1, 7, 7, 1, NULL, 13, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(202, 1, 7, 7, 1, NULL, 16, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(218, 1, 8, 8, 1, NULL, 1, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(219, 1, 8, 8, 1, NULL, 2, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(220, 1, 8, 8, 1, NULL, 3, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(221, 1, 8, 8, 1, NULL, 4, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(222, 1, 8, 8, 1, NULL, 5, 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(223, 1, 8, 8, 1, NULL, 6, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(224, 1, 8, 8, 1, NULL, 7, 91, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(225, 1, 8, 8, 1, NULL, 8, 119, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(226, 1, 8, 8, 1, NULL, 9, 93, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(227, 1, 8, 8, 1, NULL, 10, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(228, 1, 8, 8, 1, NULL, 11, 106, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(229, 1, 8, 8, 1, NULL, 12, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(230, 1, 8, 8, 1, NULL, 15, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(231, 1, 8, 8, 1, NULL, 14, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(232, 1, 8, 8, 1, NULL, 13, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(233, 1, 8, 8, 1, NULL, 16, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(249, 1, 9, 9, 1, NULL, 1, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(250, 1, 9, 9, 1, NULL, 2, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(251, 1, 9, 9, 1, NULL, 3, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(252, 1, 9, 9, 1, NULL, 4, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(253, 1, 9, 9, 1, NULL, 5, 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(254, 1, 9, 9, 1, NULL, 6, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(255, 1, 9, 9, 1, NULL, 7, 91, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(256, 1, 9, 9, 1, NULL, 8, 119, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(257, 1, 9, 9, 1, NULL, 9, 93, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(258, 1, 9, 9, 1, NULL, 10, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(259, 1, 9, 9, 1, NULL, 11, 106, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(260, 1, 9, 9, 1, NULL, 12, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(261, 1, 9, 9, 1, NULL, 15, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(262, 1, 9, 9, 1, NULL, 14, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(263, 1, 9, 9, 1, NULL, 13, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(264, 1, 9, 9, 1, NULL, 16, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(280, 1, 10, 10, 1, NULL, 1, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(281, 1, 10, 10, 1, NULL, 2, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(282, 1, 10, 10, 1, NULL, 3, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(283, 1, 10, 10, 1, NULL, 4, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(284, 1, 10, 10, 1, NULL, 5, 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(285, 1, 10, 10, 1, NULL, 6, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(286, 1, 10, 10, 1, NULL, 7, 91, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(287, 1, 10, 10, 1, NULL, 8, 119, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(288, 1, 10, 10, 1, NULL, 9, 93, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(289, 1, 10, 10, 1, NULL, 10, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(290, 1, 10, 10, 1, NULL, 11, 106, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(291, 1, 10, 10, 1, NULL, 12, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(292, 1, 10, 10, 1, NULL, 15, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(293, 1, 10, 10, 1, NULL, 14, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(294, 1, 10, 10, 1, NULL, 13, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(295, 1, 10, 10, 1, NULL, 16, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(311, 1, 11, 11, 1, NULL, 1, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(312, 1, 11, 11, 1, NULL, 2, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(313, 1, 11, 11, 1, NULL, 3, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(314, 1, 11, 11, 1, NULL, 4, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(315, 1, 11, 11, 1, NULL, 5, 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(316, 1, 11, 11, 1, NULL, 6, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(317, 1, 11, 11, 1, NULL, 7, 91, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(318, 1, 11, 11, 1, NULL, 8, 119, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(319, 1, 11, 11, 1, NULL, 9, 93, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(320, 1, 11, 11, 1, NULL, 10, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(321, 1, 11, 11, 1, NULL, 11, 106, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(322, 1, 11, 11, 1, NULL, 12, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(323, 1, 11, 11, 1, NULL, 15, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(324, 1, 11, 11, 1, NULL, 14, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(325, 1, 11, 11, 1, NULL, 13, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(326, 1, 11, 11, 1, NULL, 16, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(342, 1, 12, 12, 1, NULL, 1, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(343, 1, 12, 12, 1, NULL, 2, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(344, 1, 12, 12, 1, NULL, 3, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(345, 1, 12, 12, 1, NULL, 4, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(346, 1, 12, 12, 1, NULL, 5, 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(347, 1, 12, 12, 1, NULL, 6, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(348, 1, 12, 12, 1, NULL, 7, 91, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(349, 1, 12, 12, 1, NULL, 8, 119, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(350, 1, 12, 12, 1, NULL, 9, 93, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(351, 1, 12, 12, 1, NULL, 10, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(352, 1, 12, 12, 1, NULL, 11, 106, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(353, 1, 12, 12, 1, NULL, 12, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(354, 1, 12, 12, 1, NULL, 15, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(355, 1, 12, 12, 1, NULL, 14, 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(356, 1, 12, 12, 1, NULL, 13, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta'),
(357, 1, 12, 12, 1, NULL, 16, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alta');

-- --------------------------------------------------------

--
-- Table structure for table `display`
--

CREATE TABLE IF NOT EXISTS `display` (
`id_display` tinyint(3) unsigned NOT NULL,
  `client_display` tinyint(3) unsigned NOT NULL,
  `display` varchar(100) NOT NULL,
  `picture_url` varchar(200) DEFAULT NULL,
  `canvas_url` varchar(200) DEFAULT NULL,
  `description` text,
  `positions` tinyint(3) unsigned NOT NULL,
  `status` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `display`
--

INSERT INTO `display` (`id_display`, `client_display`, `display`, `picture_url`, `canvas_url`, `description`, `positions`, `status`) VALUES
(1, 1, 'Paneles Televenta y Operaciones', 'rgh0qusqdaoc4oos0w.png', NULL, NULL, 16, 'Alta');

-- --------------------------------------------------------

--
-- Table structure for table `displays_panelado`
--

CREATE TABLE IF NOT EXISTS `displays_panelado` (
`id_displays_panelado` smallint(5) unsigned NOT NULL,
  `client_panelado` tinyint(3) unsigned NOT NULL,
  `id_panelado` tinyint(3) unsigned NOT NULL,
  `id_display` tinyint(3) unsigned NOT NULL,
  `position` tinyint(3) unsigned NOT NULL,
  `description` text,
  `status` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `displays_panelado`
--

INSERT INTO `displays_panelado` (`id_displays_panelado`, `client_panelado`, `id_panelado`, `id_display`, `position`, `description`, `status`) VALUES
(1, 1, 2, 1, 1, NULL, 'Alta'),
(2, 1, 3, 1, 1, NULL, 'Alta'),
(3, 1, 1, 1, 1, NULL, 'Alta'),
(4, 1, 4, 1, 1, NULL, 'Alta'),
(5, 1, 5, 1, 1, NULL, 'Alta');

-- --------------------------------------------------------

--
-- Table structure for table `displays_pds`
--

CREATE TABLE IF NOT EXISTS `displays_pds` (
`id_displays_pds` smallint(5) unsigned NOT NULL,
  `client_type_pds` tinyint(3) unsigned NOT NULL,
  `id_type_pds` tinyint(3) unsigned NOT NULL,
  `id_pds` smallint(5) unsigned NOT NULL,
  `id_panelado` tinyint(3) unsigned NOT NULL,
  `id_display` tinyint(3) unsigned NOT NULL,
  `position` tinyint(3) unsigned NOT NULL,
  `description` text,
  `status` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `displays_pds`
--

INSERT INTO `displays_pds` (`id_displays_pds`, `client_type_pds`, `id_type_pds`, `id_pds`, `id_panelado`, `id_display`, `position`, `description`, `status`) VALUES
(1, 1, 5, 1, 5, 1, 1, NULL, 'Alta'),
(2, 1, 5, 2, 5, 1, 1, NULL, 'Alta'),
(3, 1, 5, 3, 5, 1, 1, '', 'Alta'),
(4, 1, 5, 4, 5, 1, 1, '', 'Alta'),
(5, 1, 3, 5, 3, 1, 1, '', 'Alta'),
(6, 1, 1, 6, 1, 1, 1, '', 'Alta'),
(7, 1, 2, 7, 2, 1, 1, '', 'Alta'),
(8, 1, 4, 8, 4, 1, 1, '', 'Alta'),
(9, 1, 4, 9, 4, 1, 1, '', 'Alta'),
(10, 1, 4, 10, 4, 1, 1, '', 'Alta'),
(11, 1, 4, 11, 4, 1, 1, '', 'Alta'),
(12, 1, 4, 12, 4, 1, 1, '', 'Alta');

-- --------------------------------------------------------

--
-- Table structure for table `facturacion`
--

CREATE TABLE IF NOT EXISTS `facturacion` (
`id_facturacion` int(10) unsigned NOT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_pds` smallint(5) unsigned NOT NULL,
  `id_intervencion` int(10) unsigned NOT NULL,
  `id_incidencia` int(10) unsigned NOT NULL,
  `id_displays_pds` smallint(5) unsigned NOT NULL,
  `units_device` smallint(5) unsigned NOT NULL DEFAULT '0',
  `units_alarma` smallint(5) unsigned NOT NULL DEFAULT '0',
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `historico`
--

CREATE TABLE IF NOT EXISTS `historico` (
`id_historico` int(10) unsigned NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_incidencia` int(10) unsigned NOT NULL,
  `id_pds` smallint(5) unsigned NOT NULL,
  `description` text,
  `agent` varchar(100) NOT NULL,
  `status_pds` enum('Alta realizada','En proceso','En visita','Finalizada','Cancelada') NOT NULL DEFAULT 'Alta realizada',
  `status` enum('Nueva','Revisada','Material asignado','Instalador asignado','Comunicada','Resuelta','Pendiente recogida','Cerrada','Cancelada') NOT NULL DEFAULT 'Nueva'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `historico_sfid`
--

CREATE TABLE IF NOT EXISTS `historico_sfid` (
  `id_pds` smallint(5) unsigned NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sfid_old` varchar(50) NOT NULL,
  `sfid_new` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `incidencias`
--

CREATE TABLE IF NOT EXISTS `incidencias` (
`id_incidencia` int(10) unsigned NOT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_pds` smallint(5) unsigned NOT NULL,
  `id_displays_pds` smallint(5) unsigned NOT NULL,
  `id_devices_pds` mediumint(8) unsigned DEFAULT NULL,
  `tipo_averia` varchar(45) NOT NULL,
  `fail_device` tinyint(1) unsigned DEFAULT NULL,
  `alarm_display` tinyint(1) unsigned DEFAULT NULL,
  `alarm_device` tinyint(1) unsigned DEFAULT NULL,
  `alarm_garra` tinyint(1) unsigned DEFAULT NULL,
  `alarm_adverts` tinyint(1) unsigned DEFAULT NULL,
  `description_1` text,
  `description_2` text,
  `description_3` text,
  `parte_pdf` varchar(100) DEFAULT NULL,
  `denuncia` varchar(100) DEFAULT NULL,
  `foto_url` varchar(100) DEFAULT NULL,
  `foto_url_2` varchar(100) DEFAULT NULL,
  `foto_url_3` varchar(100) DEFAULT NULL,
  `contacto` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `id_operador` smallint(5) unsigned DEFAULT NULL,
  `intervencion` int(10) unsigned DEFAULT NULL,
  `status_pds` enum('Alta realizada','En proceso','En visita','Finalizada','Cancelada') NOT NULL DEFAULT 'Alta realizada',
  `status` enum('Nueva','Revisada','Material asignado','Instalador asignado','Comunicada','Resuelta','Pendiente recogida','Cerrada','Cancelada') NOT NULL DEFAULT 'Nueva'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `incidencias`
--

INSERT INTO `incidencias` (`id_incidencia`, `fecha`, `id_pds`, `id_displays_pds`, `id_devices_pds`, `tipo_averia`, `fail_device`, `alarm_display`, `alarm_device`, `alarm_garra`, `alarm_adverts`, `description_1`, `description_2`, `description_3`, `parte_pdf`, `denuncia`, `foto_url`, `foto_url_2`, `foto_url_3`, `contacto`, `phone`, `email`, `id_operador`, `intervencion`, `status_pds`, `status`) VALUES
(6, '2015-06-09 10:45:36', 12, 12, NULL, 'Avería', 0, 1, 0, 0, 0, 'Necesitamos un duplicado del mando de activación y desastivación de las alarmas del armario. Saltan las alarmas y necesitamos que la persona de recepción pueda apagarlo, ya que el mando está en plataforma y hay veces que tardán en venir a apagarlo. En plataforma también lo necesitan ya que a partir de las 6 esta está  cerrada.\nSi necesitan cualquier aclaración no duden en ponerse en contacto conmigo.\nSaludos cordiales.\n\nMónica Gutiérrez', '', '', '', '', '', '', '', 'Mónica Gutiérrez Cristiano', '675797802', NULL, NULL, NULL, 'Alta realizada', 'Nueva');

-- --------------------------------------------------------

--
-- Table structure for table `intervenciones`
--

CREATE TABLE IF NOT EXISTS `intervenciones` (
`id_intervencion` int(10) unsigned NOT NULL,
  `id_pds` smallint(5) unsigned NOT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_operador` smallint(5) unsigned DEFAULT NULL,
  `description` text,
  `status` enum('Nueva','Comunicada','Cerrada','Cancelada') NOT NULL DEFAULT 'Nueva'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `intervenciones_incidencias`
--

CREATE TABLE IF NOT EXISTS `intervenciones_incidencias` (
  `id_intervencion` int(10) unsigned NOT NULL,
  `id_incidencia` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
`id` int(11) unsigned NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `material_incidencias`
--

CREATE TABLE IF NOT EXISTS `material_incidencias` (
`id_material_incidencias` int(10) unsigned NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_incidencia` int(10) unsigned NOT NULL,
  `id_pds` smallint(5) NOT NULL,
  `id_alarm` smallint(5) unsigned DEFAULT NULL,
  `id_devices_almacen` mediumint(8) unsigned DEFAULT NULL,
  `cantidad` smallint(5) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `panelado`
--

CREATE TABLE IF NOT EXISTS `panelado` (
`id_panelado` tinyint(3) unsigned NOT NULL,
  `client_panelado` tinyint(3) unsigned NOT NULL,
  `type_pds` tinyint(3) unsigned NOT NULL,
  `panelado` varchar(200) NOT NULL,
  `panelado_abx` varchar(200) NOT NULL,
  `picture_url` varchar(200) DEFAULT NULL,
  `description` text,
  `status` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `panelado`
--

INSERT INTO `panelado` (`id_panelado`, `client_panelado`, `type_pds`, `panelado`, `panelado_abx`, `picture_url`, `description`, `status`) VALUES
(1, 1, 1, 'INbound Televenta y Operaciones', 'INbound Televenta y Operaciones', NULL, NULL, 'Alta'),
(2, 1, 2, 'INbound + Operaciones Televenta y Operaciones', 'INbound + Operaciones Televenta y Operaciones', NULL, NULL, 'Alta'),
(3, 1, 3, 'INbound + OUTbound Televenta y Operaciones', 'INbound + OUTbound Televenta y Operaciones', NULL, NULL, 'Alta'),
(4, 1, 4, 'Operaciones Televenta y Operaciones', 'Operaciones Televenta y Operaciones', NULL, NULL, 'Alta'),
(5, 1, 5, 'OUTbound Televenta y Operaciones', 'OUTbound Televenta y Operaciones', NULL, NULL, 'Alta');

-- --------------------------------------------------------

--
-- Table structure for table `pds`
--

CREATE TABLE IF NOT EXISTS `pds` (
`id_pds` smallint(5) unsigned NOT NULL,
  `client_pds` tinyint(3) NOT NULL,
  `reference` varchar(50) NOT NULL,
  `type_pds` tinyint(3) unsigned DEFAULT NULL,
  `territory` tinyint(3) unsigned DEFAULT NULL,
  `panelado_pds` tinyint(3) unsigned DEFAULT NULL,
  `dispo` enum('Normal','Inversa','Mixta') NOT NULL DEFAULT 'Normal',
  `commercial` varchar(200) NOT NULL,
  `cif` varchar(12) DEFAULT NULL,
  `picture_url` varchar(200) DEFAULT NULL,
  `m2_fo` int(10) unsigned DEFAULT NULL,
  `m2_bo` int(10) unsigned DEFAULT NULL,
  `m2_total` int(10) unsigned DEFAULT NULL,
  `type_via` tinyint(3) unsigned DEFAULT NULL,
  `address` text,
  `zip` varchar(10) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `province` smallint(6) unsigned DEFAULT NULL,
  `county` tinyint(4) unsigned DEFAULT NULL,
  `schedule` text,
  `phone` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact_contact_person` smallint(5) unsigned DEFAULT NULL,
  `contact_in_charge` smallint(5) unsigned DEFAULT NULL,
  `contact_supervisor` smallint(5) unsigned DEFAULT NULL,
  `status` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pds`
--

INSERT INTO `pds` (`id_pds`, `client_pds`, `reference`, `type_pds`, `territory`, `panelado_pds`, `dispo`, `commercial`, `cif`, `picture_url`, `m2_fo`, `m2_bo`, `m2_total`, `type_via`, `address`, `zip`, `city`, `province`, `county`, `schedule`, `phone`, `mobile`, `email`, `contact_contact_person`, `contact_in_charge`, `contact_supervisor`, `status`) VALUES
(1, 1, '39994134', 5, 6, 5, 'Normal', 'Abante Lugo', NULL, NULL, NULL, NULL, NULL, 6, 'Avda Infanta Elena, 300\nPoligono O´Ceao', '27003', 'Lugo', 28, 12, NULL, NULL, '628717308', 'anabelen.ramos@abantebpo.com', 21, NULL, NULL, 'Alta'),
(2, 1, '39440103', 5, 6, 5, 'Normal', 'Conexión Santander Nueva Montaña', NULL, NULL, NULL, NULL, NULL, 6, 'Avda. Nueva Montaña, 2G Parcela 28', '39011', 'Santander', 39, 6, NULL, NULL, '658835031', 'patricia.escudero@tiendasconexion.com', 10, NULL, NULL, 'Alta'),
(3, 1, '19140038', 5, 1, 5, 'Normal', 'Madison', NULL, NULL, NULL, NULL, NULL, 8, 'c/ Enrique Cubero, 32', '47014', 'Valladolid', 47, 7, NULL, NULL, '638919114', 'mar.hernandez@madisonmk.com', 11, NULL, NULL, 'Alta'),
(4, 1, '59990015', 5, 3, 5, 'Normal', 'Iccs Malaga', NULL, NULL, NULL, NULL, NULL, 8, 'C/ Don Cristian 2 2ª Planta', '29007', 'Málaga', 29, 1, NULL, NULL, '615641070', 'sandra.longhi@iccs.es', 17, NULL, NULL, 'Alta'),
(5, 1, '39140041', 3, 6, 3, 'Normal', 'Conexión Santander Ardazo', NULL, NULL, NULL, NULL, NULL, NULL, 'Alfredo Perez Guillen, 5', '39011', 'Santander', 39, 6, NULL, NULL, '658835031', 'monica.gonzalez@tiendasconexion.com', 13, NULL, NULL, 'Alta'),
(6, 1, '59140008', 1, 3, 1, 'Normal', 'Sitel Sevilla', NULL, NULL, NULL, NULL, NULL, 8, 'C/ República Argentina, 25 Planta 4ª', '41011', 'Sevilla', 41, 1, NULL, NULL, '629385565', 'Antonio.ramos@sitel.com', 14, NULL, NULL, 'Alta'),
(7, 1, '18250018', 2, 1, 2, 'Normal', 'Arvato Salamanca', NULL, NULL, NULL, NULL, NULL, NULL, 'Polígono el Montalvo I\nC/ Doctor Fleming 51', '37188', '(Carbajosa de la Sagrada) Salamanca', 37, 8, NULL, NULL, '615038089', 'Javier.sanz@arvato.es', 15, NULL, NULL, 'Alta'),
(8, 1, '29140022', 4, 6, 4, 'Normal', 'Oviedo OEST', NULL, NULL, NULL, NULL, NULL, NULL, 'Polígono Industrial Espíritu Santo\nCalle Dinamarca nº 8', '33010', 'Oviedo', 33, 3, NULL, NULL, '615087035', 'anafesordo.oest@orange.com', 16, NULL, NULL, 'Alta'),
(9, 1, '19990200', 4, 1, 4, 'Normal', 'Transcom San Fernando', NULL, NULL, NULL, NULL, NULL, NULL, 'Avda. Castilla, 2\nParque Empresarial de San Fernando\nEdificio Hungría', '28830', 'San Fernando de Henares, Madrid', 28, 13, NULL, NULL, '625919590', 'soraya.pizarro@transcom.com', 17, NULL, NULL, 'Alta'),
(10, 1, '19140109', 4, 3, 4, 'Normal', 'ISGF Murcia', NULL, NULL, NULL, NULL, NULL, NULL, 'C/ Faro S/N\nPoligono Industrial Cabezo Cortado', '30100', 'El Puntual', 30, 14, NULL, NULL, '625302376', 'patricia.asyn.tejeira@isgf.es', 18, NULL, NULL, 'Alta'),
(11, 1, '39990025', 4, 6, 4, 'Normal', 'Bosch Vigo', NULL, NULL, NULL, NULL, NULL, NULL, 'Avenida de Madrid, 183 4ª Pta', '36214', 'Vigo', 36, 12, NULL, '986917513', NULL, 'Adolfo.AlonsoArgibay@es.bosch.com', 19, NULL, NULL, 'Alta'),
(12, 1, '19990205', 4, 6, 4, 'Normal', 'Transcom León', NULL, NULL, NULL, NULL, NULL, NULL, 'Transcom Worldwide\nAv. Reyes Leoneses, 14, 2º\n(Edificio Europa)', '24008', 'León', 24, 7, NULL, NULL, '675797802', 'monica.gutierrez@transcom.com', 20, NULL, NULL, 'Alta');

--
-- Triggers `pds`
--
DELIMITER //
CREATE TRIGGER `m2_totales_create` BEFORE INSERT ON `pds`
 FOR EACH ROW SET NEW.m2_total = NEW.m2_fo + NEW.m2_bo
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `m2_totales_update` BEFORE UPDATE ON `pds`
 FOR EACH ROW SET NEW.m2_total = NEW.m2_fo + NEW.m2_bo
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `province`
--

CREATE TABLE IF NOT EXISTS `province` (
  `id_province` smallint(6) unsigned NOT NULL,
  `province` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `province`
--

INSERT INTO `province` (`id_province`, `province`) VALUES
(2, 'Albacete'),
(3, 'Alicante/Alacant'),
(4, 'Almería'),
(1, 'Araba/Álava'),
(33, 'Asturias'),
(5, 'Ávila'),
(6, 'Badajoz'),
(7, 'Balears, Illes'),
(8, 'Barcelona'),
(48, 'Bizkaia'),
(9, 'Burgos'),
(10, 'Cáceres'),
(11, 'Cádiz'),
(39, 'Cantabria'),
(12, 'Castellón/Castelló'),
(51, 'Ceuta'),
(13, 'Ciudad Real'),
(14, 'Córdoba'),
(15, 'Coruña, A'),
(16, 'Cuenca'),
(20, 'Gipuzkoa'),
(17, 'Girona'),
(18, 'Granada'),
(19, 'Guadalajara'),
(21, 'Huelva'),
(22, 'Huesca'),
(23, 'Jaén'),
(24, 'León'),
(25, 'Lleida'),
(27, 'Lugo'),
(28, 'Madrid'),
(29, 'Málaga'),
(52, 'Melilla'),
(30, 'Murcia'),
(31, 'Navarra'),
(32, 'Ourense'),
(34, 'Palencia'),
(35, 'Palmas, Las'),
(36, 'Pontevedra'),
(26, 'Rioja, La'),
(37, 'Salamanca'),
(38, 'Santa Cruz de Tenerife'),
(40, 'Segovia'),
(41, 'Sevilla'),
(42, 'Soria'),
(43, 'Tarragona'),
(44, 'Teruel'),
(45, 'Toledo'),
(46, 'Valencia/València'),
(47, 'Valladolid'),
(49, 'Zamora'),
(50, 'Zaragoza');

-- --------------------------------------------------------

--
-- Table structure for table `status_device`
--

CREATE TABLE IF NOT EXISTS `status_device` (
`id_status_device` tinyint(3) unsigned NOT NULL,
  `status_device` varchar(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `status_device`
--

INSERT INTO `status_device` (`id_status_device`, `status_device`) VALUES
(4, 'Muy usado'),
(1, 'Nuevo'),
(5, 'Seminuevo');

-- --------------------------------------------------------

--
-- Table structure for table `status_packaging_device`
--

CREATE TABLE IF NOT EXISTS `status_packaging_device` (
`id_status_packaging_device` tinyint(3) unsigned NOT NULL,
  `status_packaging_device` varchar(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `status_packaging_device`
--

INSERT INTO `status_packaging_device` (`id_status_packaging_device`, `status_packaging_device`) VALUES
(4, 'Abierto'),
(5, 'Demo'),
(12, 'Desprecintado'),
(9, 'Original'),
(11, 'Precintado');

-- --------------------------------------------------------

--
-- Table structure for table `territory`
--

CREATE TABLE IF NOT EXISTS `territory` (
`id_territory` tinyint(3) unsigned NOT NULL,
  `territory` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `territory`
--

INSERT INTO `territory` (`id_territory`, `territory`) VALUES
(1, 'CENTRO'),
(4, 'ESTE'),
(5, 'LEVANTE'),
(6, 'NORTE'),
(7, 'PAIS VASCO'),
(3, 'SUR');

-- --------------------------------------------------------

--
-- Table structure for table `type_alarm`
--

CREATE TABLE IF NOT EXISTS `type_alarm` (
`id_type_alarm` tinyint(3) unsigned NOT NULL,
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `type_alarm`
--

INSERT INTO `type_alarm` (`id_type_alarm`, `type`) VALUES
(31, 'abrazadera'),
(32, 'botón desconexión'),
(33, 'brazo'),
(34, 'cable'),
(36, 'cable iphone'),
(37, 'cable micro usb 20cm'),
(38, 'cable micro usb 8cm'),
(39, 'cargador llave'),
(40, 'central programación'),
(41, 'centralita 4 conexiones'),
(42, 'centralita 8 conexiones'),
(43, 'fuente alimentación'),
(44, 'herramienta'),
(45, 'llave'),
(46, 'llave mute'),
(47, 'llave on/off'),
(48, 'llave programación'),
(49, 'pegatina'),
(50, 'pegatina centralita'),
(51, 'pegatina sensor'),
(52, 'rosca'),
(53, 'sensor'),
(54, 'soporte tablet'),
(55, 'varilla smartphone'),
(56, 'varilla tablet'),
(57, 'varios');

-- --------------------------------------------------------

--
-- Table structure for table `type_device`
--

CREATE TABLE IF NOT EXISTS `type_device` (
`id_type_device` tinyint(3) unsigned NOT NULL,
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `type_device`
--

INSERT INTO `type_device` (`id_type_device`, `type`) VALUES
(3, 'Accesorio'),
(1, 'Smartphone'),
(4, 'Smartwatch'),
(2, 'Tablet'),
(5, 'Wearable');

-- --------------------------------------------------------

--
-- Table structure for table `type_pds`
--

CREATE TABLE IF NOT EXISTS `type_pds` (
`id_type_pds` tinyint(3) unsigned NOT NULL,
  `client_type_pds` tinyint(3) unsigned NOT NULL,
  `pds` varchar(100) NOT NULL,
  `description` text,
  `status` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `type_pds`
--

INSERT INTO `type_pds` (`id_type_pds`, `client_type_pds`, `pds`, `description`, `status`) VALUES
(1, 1, 'INbound', NULL, 'Alta'),
(2, 1, 'INbound + Operaciones', NULL, 'Alta'),
(3, 1, 'INbound + OUTbound', NULL, 'Alta'),
(4, 1, 'Operaciones', NULL, 'Alta'),
(5, 1, 'OUTbound', NULL, 'Alta');

-- --------------------------------------------------------

--
-- Table structure for table `type_profile`
--

CREATE TABLE IF NOT EXISTS `type_profile` (
`id_type_profile` tinyint(3) unsigned NOT NULL,
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `type_profile`
--

INSERT INTO `type_profile` (`id_type_profile`, `type`) VALUES
(5, 'Agente'),
(2, 'Fabricante'),
(1, 'Instalador'),
(4, 'Operador'),
(3, 'Proveedor');

-- --------------------------------------------------------

--
-- Table structure for table `type_via`
--

CREATE TABLE IF NOT EXISTS `type_via` (
`id_type_via` tinyint(3) unsigned NOT NULL,
  `via` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `type_via`
--

INSERT INTO `type_via` (`id_type_via`, `via`) VALUES
(1, 'Acceso'),
(2, 'Acera'),
(3, 'Alameda'),
(4, 'Autopista'),
(5, 'Autovía'),
(6, 'Avenida'),
(7, 'C. Comercial'),
(8, 'Calle'),
(9, 'Callejón'),
(10, 'Camino'),
(11, 'Cañada'),
(12, 'Carrer'),
(13, 'Carrera'),
(14, 'Carretera'),
(15, 'Cuesta'),
(27, 'Explanada'),
(16, 'Glorieta'),
(17, 'Pasadizo'),
(18, 'Pasaje'),
(19, 'Paseo'),
(20, 'Plaza'),
(28, 'Plazoleta'),
(29, 'Polígono'),
(21, 'Rambla'),
(22, 'Ronda'),
(23, 'Sendero'),
(24, 'Travesía'),
(25, 'Urbanización'),
(26, 'Vía');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agent`
--
ALTER TABLE `agent`
 ADD PRIMARY KEY (`agent_id`);

--
-- Indexes for table `alarm`
--
ALTER TABLE `alarm`
 ADD PRIMARY KEY (`id_alarm`);

--
-- Indexes for table `alarms_almacen`
--
ALTER TABLE `alarms_almacen`
 ADD PRIMARY KEY (`id_alarms_almacen`);

--
-- Indexes for table `alarms_device_display`
--
ALTER TABLE `alarms_device_display`
 ADD PRIMARY KEY (`id_alarms_device_display`);

--
-- Indexes for table `alarms_device_pds`
--
ALTER TABLE `alarms_device_pds`
 ADD PRIMARY KEY (`id_alarms_device_pds`);

--
-- Indexes for table `alarms_display`
--
ALTER TABLE `alarms_display`
 ADD PRIMARY KEY (`id_alarms_display`);

--
-- Indexes for table `alarms_display_pds`
--
ALTER TABLE `alarms_display_pds`
 ADD PRIMARY KEY (`id_alarms_display_pds`);

--
-- Indexes for table `brand_alarm`
--
ALTER TABLE `brand_alarm`
 ADD PRIMARY KEY (`id_brand_alarm`), ADD UNIQUE KEY `brand_UNIQUE` (`brand`);

--
-- Indexes for table `brand_device`
--
ALTER TABLE `brand_device`
 ADD PRIMARY KEY (`id_brand_device`), ADD UNIQUE KEY `brand_UNIQUE` (`brand`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
 ADD PRIMARY KEY (`id_chat`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
 ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
 ADD PRIMARY KEY (`id_client`), ADD UNIQUE KEY `client` (`client`);

--
-- Indexes for table `color_device`
--
ALTER TABLE `color_device`
 ADD PRIMARY KEY (`id_color_device`), ADD UNIQUE KEY `color_device` (`color_device`);

--
-- Indexes for table `complement_device`
--
ALTER TABLE `complement_device`
 ADD PRIMARY KEY (`id_complement_device`), ADD UNIQUE KEY `complement_device` (`complement_device`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
 ADD PRIMARY KEY (`id_contact`);

--
-- Indexes for table `county`
--
ALTER TABLE `county`
 ADD PRIMARY KEY (`id_county`), ADD UNIQUE KEY `county_UNIQUE` (`county`);

--
-- Indexes for table `device`
--
ALTER TABLE `device`
 ADD PRIMARY KEY (`id_device`);

--
-- Indexes for table `devices_almacen`
--
ALTER TABLE `devices_almacen`
 ADD PRIMARY KEY (`id_devices_almacen`);

--
-- Indexes for table `devices_display`
--
ALTER TABLE `devices_display`
 ADD PRIMARY KEY (`id_devices_display`);

--
-- Indexes for table `devices_pds`
--
ALTER TABLE `devices_pds`
 ADD PRIMARY KEY (`id_devices_pds`);

--
-- Indexes for table `display`
--
ALTER TABLE `display`
 ADD PRIMARY KEY (`id_display`);

--
-- Indexes for table `displays_panelado`
--
ALTER TABLE `displays_panelado`
 ADD PRIMARY KEY (`id_displays_panelado`);

--
-- Indexes for table `displays_pds`
--
ALTER TABLE `displays_pds`
 ADD PRIMARY KEY (`id_displays_pds`);

--
-- Indexes for table `facturacion`
--
ALTER TABLE `facturacion`
 ADD PRIMARY KEY (`id_facturacion`);

--
-- Indexes for table `historico`
--
ALTER TABLE `historico`
 ADD PRIMARY KEY (`id_historico`);

--
-- Indexes for table `historico_sfid`
--
ALTER TABLE `historico_sfid`
 ADD PRIMARY KEY (`id_pds`);

--
-- Indexes for table `incidencias`
--
ALTER TABLE `incidencias`
 ADD PRIMARY KEY (`id_incidencia`);

--
-- Indexes for table `intervenciones`
--
ALTER TABLE `intervenciones`
 ADD PRIMARY KEY (`id_intervencion`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_incidencias`
--
ALTER TABLE `material_incidencias`
 ADD PRIMARY KEY (`id_material_incidencias`);

--
-- Indexes for table `panelado`
--
ALTER TABLE `panelado`
 ADD PRIMARY KEY (`id_panelado`);

--
-- Indexes for table `pds`
--
ALTER TABLE `pds`
 ADD PRIMARY KEY (`id_pds`,`reference`);

--
-- Indexes for table `province`
--
ALTER TABLE `province`
 ADD PRIMARY KEY (`id_province`), ADD UNIQUE KEY `province_UNIQUE` (`province`), ADD UNIQUE KEY `id_province_UNIQUE` (`id_province`);

--
-- Indexes for table `status_device`
--
ALTER TABLE `status_device`
 ADD PRIMARY KEY (`id_status_device`), ADD UNIQUE KEY `status_device` (`status_device`);

--
-- Indexes for table `status_packaging_device`
--
ALTER TABLE `status_packaging_device`
 ADD PRIMARY KEY (`id_status_packaging_device`), ADD UNIQUE KEY `status_packaging_device` (`status_packaging_device`);

--
-- Indexes for table `territory`
--
ALTER TABLE `territory`
 ADD PRIMARY KEY (`id_territory`), ADD UNIQUE KEY `territory` (`territory`);

--
-- Indexes for table `type_alarm`
--
ALTER TABLE `type_alarm`
 ADD PRIMARY KEY (`id_type_alarm`), ADD UNIQUE KEY `type_UNIQUE` (`type`);

--
-- Indexes for table `type_device`
--
ALTER TABLE `type_device`
 ADD PRIMARY KEY (`id_type_device`), ADD UNIQUE KEY `type_UNIQUE` (`type`);

--
-- Indexes for table `type_pds`
--
ALTER TABLE `type_pds`
 ADD PRIMARY KEY (`id_type_pds`);

--
-- Indexes for table `type_profile`
--
ALTER TABLE `type_profile`
 ADD PRIMARY KEY (`id_type_profile`), ADD UNIQUE KEY `type_UNIQUE` (`type`);

--
-- Indexes for table `type_via`
--
ALTER TABLE `type_via`
 ADD PRIMARY KEY (`id_type_via`), ADD UNIQUE KEY `via_UNIQUE` (`via`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
MODIFY `agent_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1000002;
--
-- AUTO_INCREMENT for table `alarms_almacen`
--
ALTER TABLE `alarms_almacen`
MODIFY `id_alarms_almacen` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `alarms_device_display`
--
ALTER TABLE `alarms_device_display`
MODIFY `id_alarms_device_display` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `alarms_device_pds`
--
ALTER TABLE `alarms_device_pds`
MODIFY `id_alarms_device_pds` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `alarms_display`
--
ALTER TABLE `alarms_display`
MODIFY `id_alarms_display` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `alarms_display_pds`
--
ALTER TABLE `alarms_display_pds`
MODIFY `id_alarms_display_pds` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `brand_alarm`
--
ALTER TABLE `brand_alarm`
MODIFY `id_brand_alarm` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `brand_device`
--
ALTER TABLE `brand_device`
MODIFY `id_brand_device` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
MODIFY `id_chat` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
MODIFY `id_client` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `color_device`
--
ALTER TABLE `color_device`
MODIFY `id_color_device` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `complement_device`
--
ALTER TABLE `complement_device`
MODIFY `id_complement_device` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
MODIFY `id_contact` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `county`
--
ALTER TABLE `county`
MODIFY `id_county` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `device`
--
ALTER TABLE `device`
MODIFY `id_device` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=120;
--
-- AUTO_INCREMENT for table `devices_almacen`
--
ALTER TABLE `devices_almacen`
MODIFY `id_devices_almacen` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `devices_display`
--
ALTER TABLE `devices_display`
MODIFY `id_devices_display` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `devices_pds`
--
ALTER TABLE `devices_pds`
MODIFY `id_devices_pds` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=373;
--
-- AUTO_INCREMENT for table `display`
--
ALTER TABLE `display`
MODIFY `id_display` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `displays_panelado`
--
ALTER TABLE `displays_panelado`
MODIFY `id_displays_panelado` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `displays_pds`
--
ALTER TABLE `displays_pds`
MODIFY `id_displays_pds` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `facturacion`
--
ALTER TABLE `facturacion`
MODIFY `id_facturacion` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `historico`
--
ALTER TABLE `historico`
MODIFY `id_historico` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `incidencias`
--
ALTER TABLE `incidencias`
MODIFY `id_incidencia` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `intervenciones`
--
ALTER TABLE `intervenciones`
MODIFY `id_intervencion` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `material_incidencias`
--
ALTER TABLE `material_incidencias`
MODIFY `id_material_incidencias` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `panelado`
--
ALTER TABLE `panelado`
MODIFY `id_panelado` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `pds`
--
ALTER TABLE `pds`
MODIFY `id_pds` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `status_device`
--
ALTER TABLE `status_device`
MODIFY `id_status_device` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `status_packaging_device`
--
ALTER TABLE `status_packaging_device`
MODIFY `id_status_packaging_device` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `territory`
--
ALTER TABLE `territory`
MODIFY `id_territory` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `type_alarm`
--
ALTER TABLE `type_alarm`
MODIFY `id_type_alarm` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=58;
--
-- AUTO_INCREMENT for table `type_device`
--
ALTER TABLE `type_device`
MODIFY `id_type_device` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `type_pds`
--
ALTER TABLE `type_pds`
MODIFY `id_type_pds` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `type_profile`
--
ALTER TABLE `type_profile`
MODIFY `id_type_profile` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `type_via`
--
ALTER TABLE `type_via`
MODIFY `id_type_via` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
