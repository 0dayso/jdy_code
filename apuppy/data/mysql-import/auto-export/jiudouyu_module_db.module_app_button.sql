-- MySQL dump 10.13  Distrib 5.6.34, for Linux (x86_64)
--
-- Host: rr-2zeap9jp480wx4j12.mysql.rds.aliyuncs.com    Database: jiudouyu_module_db
-- ------------------------------------------------------
-- Server version	5.6.29

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `module_app_button`
--

LOCK TABLES `module_app_button` WRITE;
/*!40000 ALTER TABLE `module_app_button` DISABLE KEYS */;
INSERT INTO `module_app_button` VALUES (36,'回款计划',9423,1,1,200,'2016-04-01 02:23:50','2016-04-04 16:00:00',100,'',0,'2016-01-07 02:17:17','2016-01-07 02:17:17'),(37,'我的优惠券',9424,1,2,200,'2014-01-17 02:24:13','2015-04-23 16:00:00',100,'',0,'2016-01-07 02:17:42','2016-01-07 02:17:42'),(38,'我的银行卡',9425,1,3,200,'2016-01-18 02:24:33','2016-02-22 02:24:37',100,'',0,'2016-01-07 02:18:02','2016-01-07 02:18:02'),(39,'交易明细',9426,1,4,200,'2016-01-18 02:24:55','2016-02-22 02:24:57',100,'',0,'2016-01-07 02:18:28','2016-01-07 02:18:28'),(40,'债权转让',9427,1,5,200,'2016-01-18 02:25:24','2016-02-22 02:25:26',100,'',0,'2016-01-07 02:18:51','2016-10-12 02:15:03'),(41,'邀请好友',10234,1,6,200,'2016-01-11 02:25:47','2016-11-27 15:59:59',200,'a:7:{s:11:\"share_title\";s:19:\"点击领取收益!\";s:10:\"share_desc\";s:78:\"我在九斗鱼赚了好多钱，邀请你一起来，注册后即可赚钱！\";s:9:\"share_url\";s:47:\"http://wx.9douyu.com/activity/partner1?from=app\";s:10:\"invite_url\";s:1:\"1\";s:4:\"purl\";s:53:\"https://wx.9douyu.com/static/images/partner_share.png\";s:9:\"share_img\";s:53:\"https://wx.9douyu.com/static/images/partner_share.png\";s:10:\"share_type\";i:1;}',0,'2016-01-07 02:19:18','2016-12-01 02:35:55'),(42,'金',9390,2,7,200,'2016-01-25 06:39:57','2016-06-11 16:00:00',100,'',0,'2016-01-25 06:33:12','2016-01-25 06:33:12'),(43,'猴',9391,2,8,200,'2016-01-07 02:26:43','2016-06-11 16:00:00',100,'',0,'2016-01-07 02:20:10','2016-01-07 02:20:10'),(44,'进',9392,2,9,200,'2016-01-07 02:27:17','2016-06-11 16:00:00',100,'',0,'2016-01-07 02:20:44','2016-01-07 02:20:44'),(45,'宝',9393,2,10,200,'2016-01-07 02:27:35','2016-06-11 16:00:00',100,'',0,'2016-01-07 02:21:02','2016-01-07 02:21:02'),(46,'首页未选中',0,2,11,200,'2016-01-18 07:12:42','2016-06-11 16:00:00',100,'',0,'2016-01-19 07:05:40','2016-01-19 07:05:40'),(47,'理财未选中',0,2,12,200,'2016-01-20 03:21:15','2016-06-11 16:00:00',100,'',0,'2016-01-20 03:13:28','2016-01-20 03:13:28'),(48,'资产未选中',0,2,13,200,'2016-01-20 03:21:45','2016-06-11 16:00:00',100,'',0,'2016-01-20 03:14:01','2016-01-20 03:14:01'),(49,'更多未选中',0,2,14,200,'2016-01-20 03:22:05','2016-06-11 16:00:00',100,'',0,'2016-01-20 03:14:20','2016-01-20 03:14:20'),(50,'家庭账户',9968,1,15,200,'2016-05-09 07:51:10','2017-05-10 07:51:12',200,'a:3:{s:11:\"share_title\";s:0:\"\";s:10:\"share_desc\";s:0:\"\";s:9:\"share_url\";s:41:\"http://wx.9douyu.com/family/home?from=app\";}',0,'2016-05-09 07:51:43','2016-11-04 06:13:07'),(51,'新手福利',10232,1,16,100,'2016-06-20 16:00:00','2016-09-30 15:59:59',200,'a:3:{s:11:\"share_title\";s:16:\"新手活动s9.1\";s:10:\"share_desc\";s:16:\"新手活动s9.1\";s:9:\"share_url\";s:54:\"http://wx.9douyu.com/Activity2016/noviceindex?from=app\";}',0,'2016-06-08 14:40:50','2016-09-21 16:07:04');
/*!40000 ALTER TABLE `module_app_button` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-12-02 12:06:38
