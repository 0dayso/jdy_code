-- MySQL dump 10.13  Distrib 5.6.34, for Linux (x86_64)
--
-- Host: rr-2zeap9jp480wx4j12.mysql.rds.aliyuncs.com    Database: jiudouyu_core_db
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
-- Dumping data for table `core_system_config`
--

LOCK TABLES `core_system_config` WRITE;
/*!40000 ALTER TABLE `core_system_config` DISABLE KEYS */;
INSERT INTO `core_system_config` VALUES (1,'Service Access Token','ACCESS_TOKEN_SERVER','s:47:\"Bearer cEKTJRUdzRqxSU5DOv7eP8zMmOTzC9jkiOEOJ4av\";',1,1,'s:47:\"Bearer cEKTJRUdzRqxSU5DOv7eP8zMmOTzC9jkiOEOJ4av\";','2016-07-05 20:25:55','2016-12-02 12:00:02'),(2,'系统报警接收管理员','SYSTEM_WARNING_RECEIVE_ADMIN','a:2:{s:4:\"TYPE\";s:1:\"1\";s:7:\"RECEIVE\";s:120:\"gao.yinglu@9douyu.com,高英璐|zhang.shuang@9douyu.com,张爽|liu.qiuhui@9douyu.com,刘秋慧|he.xing@9douyu.com,贺兴\";}',1,1,'a:2:{s:4:\"TYPE\";s:21:\"1:邮件；2：短信\";s:7:\"RECEIVE\";s:12:\"邮箱列表\";}','2016-09-20 08:29:33','2016-10-27 07:11:26'),(3,'每日提现邮件接收用户','WITHDRAW_RECEIVES','a:2:{s:7:\"RECEIVE\";s:261:\"gao.yinglu@9douyu.com,高英璐|zhang.shuang@9douyu.com,张爽|qi.wenli@sunfund.com,齐文利|wang.yunxiu@sunfund.com,王云秀|kong.lingzhen@sunfund.com,孔令珍|zhu.xuewei@sunfund.com,褚雪微|yang.lan@sunfund.com,杨澜|zhang.xiaolong@sunfund.com,张小龙\";s:4:\"TYPE\";s:1:\"1\";}',1,1,'a:2:{s:7:\"RECEIVE\";s:12:\"邮箱列表\";s:4:\"TYPE\";s:8:\"1.邮件\";}','2016-09-21 05:03:51','2016-09-30 01:46:29');
/*!40000 ALTER TABLE `core_system_config` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-12-02 12:06:36
