-- MySQL dump 10.13  Distrib 5.7.21, for osx10.13 (x86_64)
--
-- Host: localhost    Database: 99kf
-- ------------------------------------------------------
-- Server version	5.7.21

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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL COMMENT '登录名',
  `password` varchar(200) NOT NULL COMMENT '密码',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bot_knowledge_relation`
--

DROP TABLE IF EXISTS `bot_knowledge_relation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bot_knowledge_relation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `kf_id` int(11) NOT NULL COMMENT '客服id',
  `bid` varchar(32) NOT NULL COMMENT '商户id',
  `group` int(11) NOT NULL COMMENT '分类id',
  PRIMARY KEY (`id`),
  KEY `bid` (`bid`),
  KEY `kf_id` (`kf_id`),
  KEY `group` (`group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `business`
--

DROP TABLE IF EXISTS `business`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `business` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `bid` varchar(32) NOT NULL DEFAULT '' COMMENT '商家id',
  `kf_id` int(11) unsigned NOT NULL COMMENT '创建者',
  `voice` enum('off','on') NOT NULL DEFAULT 'on' COMMENT '是否开启语音',
  `sound` enum('off','on') NOT NULL DEFAULT 'on' COMMENT '是否开启提示音',
  `upload_file` enum('on','off') DEFAULT 'on' COMMENT '是否允许上传文件',
  `upload_img` enum('on','off') DEFAULT 'on' COMMENT '是否允许上传图片',
  `emoji` enum('on','off') DEFAULT 'on' COMMENT '是否允许发表情',
  `dirty_words` longtext COMMENT '过滤这些脏字，逗号分隔',
  `account_state` enum('normal','forbidden') NOT NULL DEFAULT 'normal' COMMENT 'normal: 正常 ，forbidden：禁用',
  `regist_timestamp` int(11) unsigned NOT NULL COMMENT '注册时间',
  `exp_timestamp` int(11) unsigned NOT NULL COMMENT '过期时间',
  `verified` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否通过验证',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bid` (`bid`),
  KEY `regist_timestamp` (`regist_timestamp`),
  KEY `exp_timestamp` (`exp_timestamp`),
  KEY `account_state` (`account_state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `chatting`
--

DROP TABLE IF EXISTS `chatting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chatting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` varchar(128) NOT NULL COMMENT '用户id',
  `kf_id` int(11) unsigned NOT NULL COMMENT '客服id',
  `bid` varchar(32) NOT NULL COMMENT '商户id',
  `timestamp` int(11) unsigned NOT NULL COMMENT '记录插入时间',
  `last_timestamp` int(11) unsigned NOT NULL COMMENT '最后一条消息时间',
  `last_mid` int(12) NOT NULL DEFAULT '0' COMMENT '对话中最后一条信息mid',
  `user_unread_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户未读消息计数',
  `kefu_unread_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '客服未读消息计数',
  `state` enum('chatting','hidden') NOT NULL DEFAULT 'chatting' COMMENT 'chatting:正在聊，hidden:会话隐藏不显示',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bid-kf_id-uid` (`bid`,`kf_id`,`uid`),
  KEY `timestamp` (`timestamp`),
  KEY `bid-uid` (`bid`,`uid`),
  KEY `bid-kf_id-state` (`bid`,`kf_id`,`state`),
  KEY `last_timestamp` (`last_timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq` (
  `qid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` varchar(32) NOT NULL COMMENT '商户id',
  `question` longtext NOT NULL COMMENT '问题',
  `answer` longtext NOT NULL COMMENT '答案',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '分组id',
  PRIMARY KEY (`qid`),
  KEY `bid-groupid` (`bid`,`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kefu`
--

DROP TABLE IF EXISTS `kefu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kefu` (
  `kf_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '客服id',
  `username` varchar(128) NOT NULL COMMENT '用户名',
  `nickname` varchar(128) NOT NULL COMMENT '昵称',
  `password` varchar(200) NOT NULL COMMENT '密码',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机',
  `groupid` int(11) DEFAULT '0' COMMENT '客服分组id',
  `bid` varchar(32) NOT NULL COMMENT '商户id',
  `avatar` varchar(2048) NOT NULL DEFAULT '/static/kefu-avatar.png' COMMENT '头像',
  `level` enum('super_manager','manager','normal','bot') NOT NULL DEFAULT 'normal' COMMENT 'super_manager: 超级管理员，manager：商家管理员 ，normal：普通客服，bot：机器人',
  `account_state` enum('normal','forbidden','deleted') NOT NULL DEFAULT 'normal' COMMENT 'normal: 正常 ，forbidden：禁用，deleted：删除',
  `state` enum('online','offline') NOT NULL DEFAULT 'offline' COMMENT 'online：在线，offline：离线',
  `status` enum('normal','leave') DEFAULT 'normal' COMMENT '客服状态，normal:正常 leave:离开',
  `regist_timestamp` int(11) NOT NULL COMMENT '注册时间',
  `greeting` mediumtext NOT NULL COMMENT '问候语',
  `exp_timestamp` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '客服过期时间',
  `regist_ip` varchar(32) DEFAULT '' COMMENT '注册ip',
  `last_ip` varchar(32) DEFAULT '' COMMENT '登录ip',
  `cid` varchar(64) NOT NULL DEFAULT '' COMMENT '推送用的client_id',
  PRIMARY KEY (`kf_id`),
  UNIQUE KEY `username` (`username`),
  KEY `bid_groupid_state` (`bid`,`groupid`,`state`),
  KEY `exp_timestamp` (`exp_timestamp`),
  KEY `mobile` (`mobile`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kefu_groups`
--

DROP TABLE IF EXISTS `kefu_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kefu_groups` (
  `groupid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '分组id',
  `groupname` varchar(255) DEFAULT '' COMMENT '分组名称',
  `bid` varchar(32) NOT NULL COMMENT '商户id',
  PRIMARY KEY (`groupid`),
  KEY `bid` (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `knowledge`
--

DROP TABLE IF EXISTS `knowledge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `knowledge` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` varchar(32) NOT NULL COMMENT '商户id',
  `group` int(11) NOT NULL COMMENT '分类id',
  `question` longtext NOT NULL COMMENT '问题',
  `alias` longtext COMMENT '问题别名',
  `answer` longtext NOT NULL COMMENT '答案',
  `timestamp` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `bid` (`bid`),
  KEY `group` (`group`),
  FULLTEXT KEY `question-alias` (`question`,`alias`) /*!50100 WITH PARSER `ngram` */ 
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `knowledge_groups`
--

DROP TABLE IF EXISTS `knowledge_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `knowledge_groups` (
  `group` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` varchar(32) NOT NULL COMMENT '商户id',
  `name` varchar(128) NOT NULL COMMENT '分类名',
  `timestamp` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`group`),
  KEY `bid` (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `mid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '消息id',
  `bid` varchar(32) NOT NULL COMMENT '商户id',
  `kf_id` int(11) unsigned NOT NULL COMMENT '客服id',
  `uid` varchar(32) NOT NULL COMMENT '用户id',
  `author` enum('user','kefu') NOT NULL COMMENT 'user:用户发的消息，kefu:客服发的消息',
  `content` mediumtext COMMENT '具体的消息数据',
  `sub_type` varchar(32) NOT NULL DEFAULT 'message' COMMENT '消息类型,message:普通消息 notice:系统消息 其它模版消息类型',
  `timestamp` int(11) unsigned NOT NULL COMMENT '消息时间戳',
  `readed` enum('unread','readed') NOT NULL DEFAULT 'unread' COMMENT '消息是否已读',
  PRIMARY KEY (`mid`),
  KEY `timestamp` (`timestamp`),
  KEY `bid-kf_id-subtype-author` (`bid`,`kf_id`,`sub_type`,`author`),
  KEY `bid-uid-subtype-author` (`bid`,`uid`,`sub_type`,`author`),
  KEY `bid-kf_id-uid-subtype-author-readed` (`bid`,`kf_id`,`uid`,`sub_type`,`author`,`readed`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reply`
--

DROP TABLE IF EXISTS `reply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` varchar(32) NOT NULL COMMENT '商户id',
  `kf_id` int(11) NOT NULL COMMENT '客服id',
  `group` varchar(128) NOT NULL COMMENT '自动回复分类',
  `content` longtext NOT NULL COMMENT '内容',
  `timestamp` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `bid-kf_id` (`bid`,`kf_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `setting` (
  `name` varchar(32) NOT NULL COMMENT '字段',
  `value` text NOT NULL COMMENT '值'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `trade`
--

DROP TABLE IF EXISTS `trade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trade` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` varchar(32) NOT NULL COMMENT '商家id',
  `kf_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '客服id',
  `trade_no` varchar(256) NOT NULL COMMENT '交易id',
  `sn` varchar(32) NOT NULL DEFAULT '' COMMENT 'sn，用来标记订单',
  `type` varchar(64) NOT NULL COMMENT 'seat:坐席充值',
  `price` float(9,2) NOT NULL COMMENT '单价',
  `count` int(11) unsigned NOT NULL COMMENT '数量',
  `amount` float(9,2) NOT NULL COMMENT '总价',
  `timestamp` int(11) unsigned NOT NULL COMMENT '时间',
  `pay_amount` float(9,2) NOT NULL DEFAULT '0.00' COMMENT '支付总额',
  `pay_timestamp` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `notify_timestamp` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '支付系统异步通知之间',
  `notify_result` mediumtext NOT NULL COMMENT '支付系统异步通知内容',
  PRIMARY KEY (`id`),
  UNIQUE KEY `trade_no` (`trade_no`),
  UNIQUE KEY `sn` (`sn`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` varchar(32) NOT NULL,
  `bid` varchar(32) NOT NULL COMMENT '商户id',
  `nickname` varchar(128) NOT NULL COMMENT '昵称',
  `avatar` varchar(1024) NOT NULL COMMENT '头像',
  `state` enum('offline','online') NOT NULL DEFAULT 'offline' COMMENT 'offline: 离线 ,online : 在线',
  `logout_timestamp` int(11) NOT NULL COMMENT '离线时间',
  `timestamp` int(11) NOT NULL DEFAULT '0' COMMENT '用户数据生成时间',
  `account_state` enum('normal','block') NOT NULL DEFAULT 'normal' COMMENT '账户状态',
  `ip` varchar(256) NOT NULL COMMENT '访客ip',
  `user_agent` varchar(1024) NOT NULL DEFAULT '' COMMENT '浏览器user-agent，用来判断浏览器类型',
  `city` varchar(256) NOT NULL DEFAULT '' COMMENT '城市',
  `refer` varchar(1024) NOT NULL DEFAULT '' COMMENT '来源地址',
  `phone` varchar(32) NOT NULL DEFAULT '' COMMENT '电话',
  `email` varchar(256) NOT NULL DEFAULT '' COMMENT '邮箱',
  `remark` varchar(1024) NOT NULL DEFAULT '' COMMENT '备注',
  `address` varchar(1024) NOT NULL DEFAULT '' COMMENT '地址',
  `qq` varchar(32) NOT NULL DEFAULT '' COMMENT 'qq',
  `realname` varchar(32) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `others` varchar(1024) NOT NULL DEFAULT '' COMMENT '其它扩展信息',
  `history` mediumtext COMMENT '历史浏览信息',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bid-uid` (`bid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `weixin_setting`
--

DROP TABLE IF EXISTS `weixin_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weixin_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` varchar(100) NOT NULL COMMENT '商家id',
  `weixin_id` varchar(255) NOT NULL COMMENT '微信公众号原始ID',
  `appid` varchar(255) NOT NULL COMMENT '微信公众号开发者ID(AppID)',
  `secret` varchar(255) NOT NULL COMMENT '微信公众号开发者密码',
  `token` varchar(255) NOT NULL COMMENT '令牌(Token)',
  `aes_key` varchar(255) NOT NULL DEFAULT '' COMMENT '消息加解密密钥',
  PRIMARY KEY (`id`),
  UNIQUE KEY `weixin_id` (`weixin_id`),
  KEY `bid` (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `kefu` ADD COLUMN `bot` tinyint not null DEFAULT 0 comment '是否开启手动机器人托管';
ALTER TABLE `kefu` ADD COLUMN `offline_use_bot` tinyint not null DEFAULT 0  comment '是否开启离线机器人托管';
ALTER TABLE `chatting` ADD COLUMN `bot` tinyint not null DEFAULT 0 comment '是否开启手动机器人托管';


ALTER TABLE `knowledge` DROP COLUMN `alias`;
ALTER TABLE `knowledge` CHANGE COLUMN `question` `questions` LONGTEXT NOT NULL COMMENT '问题';
ALTER TABLE `knowledge` ADD COLUMN `kf_id` INT NOT NULL DEFAULT 0 comment '客服id';
ALTER TABLE `knowledge` ADD FULLTEXT KEY `questions_answer_fulltext` (`questions`, `answer`) /*!50100 WITH PARSER `ngram` */;
ALTER TABLE `knowledge` DROP KEY `question-alias`;
ALTER TABLE `knowledge_groups` ADD COLUMN  `kf_id` int NOT NULL DEFAULT '0' COMMENT '客服id';


ALTER TABLE `kefu` ADD COLUMN `translate` enum('on','off') DEFAULT 'off' COMMENT '是否开启翻译';
ALTER TABLE `kefu` ADD COLUMN `kefu_lang` varchar(64) DEFAULT 'cn' COMMENT '客服默认语言';
ALTER TABLE `kefu` ADD COLUMN `user_lang` varchar(64) DEFAULT 'cn' COMMENT '访客默认语言';
ALTER TABLE `message` ADD COLUMN `translated_content` mediumtext COMMENT '翻译后的消息数据' after `content`;
ALTER TABLE `user` ADD COLUMN `lang` varchar(64) DEFAULT '' COMMENT '访客语言';
ALTER TABLE `kefu` ADD COLUMN `greeting_translation` mediumtext COMMENT '问候语翻译' after `greeting`;
ALTER TABLE `faq` ADD COLUMN `question_translation` longtext COMMENT '问题翻译' after `question`;
ALTER TABLE `faq` ADD COLUMN `answer_translation` longtext COMMENT '回复翻译' after `answer`;


