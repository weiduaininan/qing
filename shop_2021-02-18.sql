# ************************************************************
# Sequel Pro SQL dump
# Version 5446
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 8.0.22)
# Database: shop
# Generation Time: 2021-02-18 11:12:24 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table qing_address
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_address`;

CREATE TABLE `qing_address` (
  `id` int NOT NULL AUTO_INCREMENT,
  `shou_name` varchar(10) DEFAULT NULL,
  `shou_address` varchar(50) DEFAULT NULL,
  `shou_mobile` varchar(11) DEFAULT NULL,
  `isdefault` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int NOT NULL,
  `province` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `district` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收货地址表';

LOCK TABLES `qing_address` WRITE;
/*!40000 ALTER TABLE `qing_address` DISABLE KEYS */;

INSERT INTO `qing_address` (`id`, `shou_name`, `shou_address`, `shou_mobile`, `isdefault`, `user_id`, `province`, `city`, `district`)
VALUES
	(1,'ferfe','drd','fdfd',0,1,'河北省','石家庄市','长安区'),
	(2,'fdfd','fdfd','fdfd',0,1,'内蒙古自治区','呼和浩特市','新城区'),
	(3,'鬼鬼','详细地址','18810118687',0,1,'北京市','北京市市辖区','朝阳区'),
	(4,'鬼鬼鬼','详细地址','18810118687',1,1,'北京市','北京市市辖区','东城区');

/*!40000 ALTER TABLE `qing_address` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_admin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_admin`;

CREATE TABLE `qing_admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_login_time` int NOT NULL,
  `group_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1启用0禁用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='后台管理员';

LOCK TABLES `qing_admin` WRITE;
/*!40000 ALTER TABLE `qing_admin` DISABLE KEYS */;

INSERT INTO `qing_admin` (`id`, `user_name`, `password`, `last_login_time`, `group_id`, `status`)
VALUES
	(1,'root_qing','7ac60358d4f56501575fa9def6cc3bc3',1613645346,1,1),
	(2,'goods_admin','0d734ea736e18b582050e3b990636001',1612244887,2,1);

/*!40000 ALTER TABLE `qing_admin` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_adverts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_adverts`;

CREATE TABLE `qing_adverts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type_id` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `listorder` int unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `qing_adverts` WRITE;
/*!40000 ALTER TABLE `qing_adverts` DISABLE KEYS */;

INSERT INTO `qing_adverts` (`id`, `type_id`, `title`, `thumb`, `url`, `description`, `listorder`, `status`, `content`)
VALUES
	(4,1,'小米','/public/upload/20210216/11fd1c19eaf4310fed5d63596b415c59.jpg','','',0,1,NULL),
	(5,1,'华为','/public/upload/20210216/cd16c625d723fe4ea2d6ccbf475ca144.jpg','','',0,1,NULL);

/*!40000 ALTER TABLE `qing_adverts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_adverts_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_adverts_type`;

CREATE TABLE `qing_adverts_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `qing_adverts_type` WRITE;
/*!40000 ALTER TABLE `qing_adverts_type` DISABLE KEYS */;

INSERT INTO `qing_adverts_type` (`id`, `name`)
VALUES
	(1,'首页轮播图'),
	(2,'首页广告位'),
	(3,'广告分组3');

/*!40000 ALTER TABLE `qing_adverts_type` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_auth_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_auth_group`;

CREATE TABLE `qing_auth_group` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(400) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `qing_auth_group` WRITE;
/*!40000 ALTER TABLE `qing_auth_group` DISABLE KEYS */;

INSERT INTO `qing_auth_group` (`id`, `title`, `status`, `rules`)
VALUES
	(1,'超级管理员',1,'8,68,11,10,9,100,101,102,103,88,89,90,91,92,93,94,95,96,97,98,99,104,105,106,107,108,109,7,4,5,36,1,3,2,18,19,20,33,34,39,6,13,12,69,15,16,37,21,22,27,28,29,23,26,32,38,25,30,24,40,44,41,42,43,45,46,48,49,50,60,61,62,51,53,54,55,56,57,58,59,52,63,64,65,66,70,71,74,67,73,75,76,78,79,80,77,81,82,83,84,85,86,87'),
	(2,'商品管理员',1,'8,9,10,11,15,16'),
	(5,'订单管理员',1,'6,13,12,14,15,16,37');

/*!40000 ALTER TABLE `qing_auth_group` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_auth_rule
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_auth_rule`;

CREATE TABLE `qing_auth_rule` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0不可用 1正常',
  `parent_id` mediumint NOT NULL DEFAULT '0' COMMENT '上级规则id 0表示顶级规则',
  `listorder` int NOT NULL DEFAULT '0' COMMENT '排序',
  `is_left` int DEFAULT '0' COMMENT '是否出现在左侧选择栏中 0为不出现 1为出现',
  `icon` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '图片',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `qing_auth_rule` WRITE;
/*!40000 ALTER TABLE `qing_auth_rule` DISABLE KEYS */;

INSERT INTO `qing_auth_rule` (`id`, `name`, `title`, `status`, `parent_id`, `listorder`, `is_left`, `icon`)
VALUES
	(13,'Order/edit','订单修改',1,6,0,0,''),
	(12,'Order/add（未使用）','订单添加',1,6,0,0,''),
	(11,'Goods/del','商品删除',1,68,0,0,''),
	(10,'Goods/edit','商品修改',1,68,0,0,''),
	(9,'Goods/add','商品添加',1,68,0,0,''),
	(8,'Goods/indexs','商品管理',1,0,0,1,'&#xe620;'),
	(7,'Config/indexs','系统管理',1,0,7,1,'&#xe62e;'),
	(6,'Order/indexs','订单管理',1,0,0,1,'&#xe681;'),
	(5,'Search/add','搜索关键字添加',1,4,0,0,''),
	(4,'Search/index','搜索关键字列表',1,7,0,1,''),
	(3,'Flink/edit','友情链接修改',1,1,0,0,''),
	(2,'Flink/add','友情链接添加',1,1,0,0,''),
	(1,'Flink/index','友情链接列表',1,7,0,1,''),
	(14,'Order/del','订单删除(未使用)',1,6,0,0,''),
	(15,'Index/index','后台首页',1,0,0,0,'&#xe616;'),
	(16,'Index/welcome','欢迎页面',1,15,0,1,''),
	(18,'Config/notice','通知消息',1,7,0,1,''),
	(19,'Fapiao/index','发票管理',1,7,0,1,''),
	(20,'Config/del_cache','清除缓存',1,7,0,1,''),
	(21,'Admin/indexs','管理员管理',1,0,6,1,'&#xe62d;'),
	(22,'AuthGroup/index','用户组管理',1,21,0,1,''),
	(23,'Authrule/index','权限管理',1,21,0,1,''),
	(24,'Number/index','数据统计',1,0,0,1,'&#xe61e;'),
	(25,'Admin/add','管理员添加',1,38,0,0,''),
	(26,'Authrule/add','添加权限节点',1,23,0,0,''),
	(27,'AuthGroup/add','用户组添加',1,22,0,0,''),
	(28,'AuthGroup/power','用户组分配权限',1,22,0,0,''),
	(29,'AuthGroup/edit','用户组修改',1,22,0,0,''),
	(30,'Admin/edit','管理员编辑',1,38,0,0,''),
	(31,'Admin/del','管理员删除',1,38,0,0,''),
	(32,'Authrule/edit','编辑权限节点',1,23,0,0,''),
	(33,'Base/status','基础状态修改',1,7,0,0,''),
	(34,'Base/del','基础删除设置',1,7,0,0,''),
	(35,'Authrule/del','删除权限节点',1,23,0,0,''),
	(36,'Search/edit','搜索关键字修改',1,4,0,0,''),
	(37,'Index/logout','退出页面',1,15,0,0,''),
	(38,'Admin/index','管理员列表',1,21,0,1,''),
	(39,'Config/index','系统配置',1,7,0,1,''),
	(40,'Number/order','订单统计',1,24,0,1,''),
	(41,'Market/index','营销管理',1,0,0,1,'&#xe6b6;'),
	(42,'Fenyong/index','分佣管理',1,41,0,1,''),
	(43,'Coupons/index','优惠券管理',1,41,0,1,''),
	(44,'Number/getOrder','显示订单统计图',1,40,0,0,''),
	(45,'Coupons/add','优惠券添加',1,43,0,0,''),
	(46,'Coupons/edit','优惠券编辑',1,43,0,0,''),
	(47,'Coupons/del','优惠券删除',1,43,0,0,''),
	(48,'Coupons/update','优惠券状态修改',1,43,0,0,''),
	(49,'Message/indexs','内容管理',1,0,0,1,'&#xe616;'),
	(50,'Page/index','单页面管理',1,49,0,1,''),
	(51,'News/index','新闻管理',1,49,0,1,''),
	(52,'Message/index','意见反馈',1,49,0,1,''),
	(53,'News/add','新闻添加',1,51,0,0,''),
	(54,'News/edit','新闻编辑',1,51,0,0,''),
	(55,'News/del','新闻删除',1,51,0,0,''),
	(56,'News/layui','Layui上传页面',1,51,0,0,''),
	(57,'Uploader/local_upload','Layui上传新闻',1,51,0,0,''),
	(58,'Uploader/remove_img','Layui上传删除',1,51,0,0,''),
	(59,'News/update','Layui上传编辑',1,51,0,0,''),
	(60,'Page/add','单页面添加',1,50,0,0,''),
	(61,'Page/edit','单页面编辑',1,50,0,0,''),
	(62,'Page/del','单页面删除',1,50,0,0,''),
	(63,'Message/info','意见反馈详情',1,52,0,0,''),
	(64,'Message/del','意见反馈删除',1,52,0,0,''),
	(65,'Score/index','积分管理',1,0,0,1,'&#xe6b5;'),
	(66,'ScoreGoods/index','积分商城',1,65,0,1,''),
	(67,'ScoreOrder/index','积分订单',1,65,0,1,''),
	(68,'Goods/goodslist','商品列表',1,8,0,1,''),
	(69,'Order/index','订单列表',1,6,0,1,''),
	(70,'ScoreGoods/add','积分商城添加',1,66,0,0,''),
	(71,'ScoreGoods/edit','积分商城编辑',1,66,0,0,''),
	(72,'ScoreGoods/del','积分商城删除',1,66,0,0,''),
	(73,'ScoreOrder/edit','积分订单编辑',1,67,0,0,''),
	(74,'Base/listorder','积分商城排序',1,66,0,0,''),
	(75,'Adverts/indexs','广告管理',1,0,0,1,'&#xe613;'),
	(76,'Adverts/index','广告列表',1,75,0,1,''),
	(77,'Adverts/adverts_type','广告分组',1,75,0,1,''),
	(78,'Adverts/add','广告添加',1,76,0,0,''),
	(79,'Adverts/edit','广告编辑',1,76,0,0,''),
	(80,'Adverts/del','广告删除',1,76,0,0,''),
	(81,'Adverts/adverts_type_add','广告分组添加',1,77,0,0,''),
	(82,'Adverts/adverts_type_edit','广告分组编辑',1,77,0,0,''),
	(83,'Adverts/adverts_type_del','广告分组删除',1,77,0,0,''),
	(84,'User/indexs','会员管理',1,0,0,1,'&#xe60d;'),
	(85,'User/index','会员列表',1,84,0,1,''),
	(86,'Comment/index','评论管理',1,84,0,1,''),
	(87,'Comment/del','删除评论',1,86,0,0,''),
	(88,'Type/index','商品类型',1,8,0,1,''),
	(89,'Type/add','商品类型添加',1,88,0,0,''),
	(90,'Type/edit','商品类型编辑',1,88,0,0,''),
	(91,'Type/del','商品类型删除',1,88,0,0,''),
	(92,'Standard/index','商品类型规格',1,8,0,0,''),
	(93,'Standard/add','商品规格添加',1,92,0,0,''),
	(94,'Standard/del','商品规格删除',1,92,0,0,''),
	(95,'Standard/edit','商品规格编辑',1,92,0,0,''),
	(96,'Standard/add_standard_value','规格属性添加',1,92,0,0,''),
	(97,'Standard/update_standard_value','规格属性修改',1,92,0,0,''),
	(98,'Standard/del_standard_value','规格属性删除',1,92,0,0,''),
	(99,'Category/index','栏目管理',1,8,0,1,''),
	(100,'Goods/status','商品状态修改',1,68,0,0,''),
	(101,'Goods/update','商品更新',1,68,0,0,''),
	(102,'Goods/ajaxGetAttrPrice','ajax获取商品价格',1,68,0,0,''),
	(103,'Goods/ajaxGetAttr','ajax实时通过类型获取商品属性',1,68,0,0,''),
	(104,'Category/ajaxGetCateByP','ajax获取栏目子目录',1,99,0,0,''),
	(105,'Category/add','商品栏目分类添加',1,99,0,0,''),
	(106,'Category/del','商品栏目分类删除',1,99,0,0,''),
	(107,'Category/edit','商品栏目分类编辑',1,99,0,0,''),
	(108,'Category/update','商品栏目分类更新',1,99,0,0,''),
	(109,'Category/search','商品栏目分类查找',1,99,0,0,'');

/*!40000 ALTER TABLE `qing_auth_rule` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_cart
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_cart`;

CREATE TABLE `qing_cart` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` mediumint unsigned NOT NULL COMMENT '商品ID',
  `sku` varchar(30) NOT NULL DEFAULT '0' COMMENT '选择的商品属性ID，多个用,隔开',
  `user_id` mediumint unsigned NOT NULL COMMENT '会员id',
  `amount` int NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1：选中，0：未选中',
  PRIMARY KEY (`id`),
  KEY `member_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购物车';



# Dump of table qing_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_category`;

CREATE TABLE `qing_category` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '栏目id',
  `cate_name` varchar(30) NOT NULL COMMENT '栏目名称',
  `seo_title` varchar(150) NOT NULL COMMENT '栏目标题',
  `seo_keywords` varchar(150) NOT NULL COMMENT '关键词',
  `seo_description` varchar(255) NOT NULL COMMENT '描述',
  `content` text COMMENT '内容',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1：显示 0：隐藏',
  `thumb` varchar(150) DEFAULT NULL COMMENT '图片',
  `link` varchar(150) NOT NULL COMMENT '栏目外链',
  `listorder` smallint NOT NULL DEFAULT '50' COMMENT '排序',
  `parent_id` int NOT NULL DEFAULT '0' COMMENT '上级id',
  `type_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `qing_category` WRITE;
/*!40000 ALTER TABLE `qing_category` DISABLE KEYS */;

INSERT INTO `qing_category` (`id`, `cate_name`, `seo_title`, `seo_keywords`, `seo_description`, `content`, `status`, `thumb`, `link`, `listorder`, `parent_id`, `type_id`)
VALUES
	(1,'女装 / 内衣','','','',NULL,1,'/public/upload/20200907/a9a2ebdfc574b7ba4f63111b725173a1.jpg','',1,0,2),
	(2,'浪漫裙装','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,1,1),
	(3,'美妆 / 护理','','','',NULL,1,'/public/upload/20200907/6720d04938b02fad0a8df2468a954b98.jpg','',3,0,0),
	(4,'生活电器','','','',NULL,1,'/public/static/index/images/thumb.jpg','',4,0,0),
	(5,'护肤品','','','',NULL,1,'/public/upload/20200907/3dfe3c4b167f688385657d72d1321bc5.jpg','',50,3,0),
	(6,'家居服','','','',NULL,1,'/public/upload/20200907/bf429889460c7a17b6521c201ef06b18.jpg','',50,1,0),
	(7,'彩妆','','','',NULL,1,'/public/upload/20200907/59feb5c76f25fc55545541c381f6fb7f.jpg','',50,3,0),
	(8,'洗衣机','','','',NULL,1,NULL,'',50,4,NULL),
	(9,'电冰箱','','','',NULL,1,NULL,'',50,4,NULL),
	(10,'家居 / 建材','','','',NULL,1,NULL,'',50,0,NULL),
	(11,'母婴 / 玩具','','','',NULL,1,NULL,'',50,0,NULL),
	(12,'图书 / 音像','','','',NULL,1,NULL,'',30,0,NULL),
	(13,'零食 / 茶酒','','','',NULL,1,'/public/static/index/images/thumb.jpg','',5,0,0),
	(14,'手机 / 数码','','','',NULL,1,'/public/upload/20200907/5feb28d16702eaf24bf85ebccc954d2d.jpg','',2,0,0),
	(15,'腕表 / 首饰','','','',NULL,1,'/public/static/index/images/thumb.jpg','',6,0,0),
	(16,'小米','','','',NULL,1,NULL,'',50,18,1),
	(17,'荣耀','','','',NULL,1,NULL,'',50,18,NULL),
	(18,'热门手机','','','',NULL,1,NULL,'',50,14,1),
	(19,'电脑整机','','','',NULL,1,NULL,'',50,14,NULL),
	(20,'笔记本','','','',NULL,1,NULL,'',50,19,NULL),
	(21,'平板电脑','','','',NULL,1,NULL,'',50,19,NULL),
	(22,'台式机','','','',NULL,1,NULL,'',50,19,NULL),
	(23,'一体机','','','',NULL,1,NULL,'',50,19,NULL),
	(24,'游戏本','','','',NULL,1,NULL,'',50,19,NULL),
	(25,'iPad','','','',NULL,1,NULL,'',50,19,NULL),
	(26,'Iphone','','','',NULL,1,NULL,'',50,18,NULL),
	(27,'魅族','','','',NULL,1,NULL,'',50,18,NULL),
	(28,'华为','','','',NULL,1,NULL,'',50,18,NULL),
	(29,'OPPO','','','',NULL,1,NULL,'',50,18,NULL),
	(30,'智能数码','','','',NULL,1,NULL,'',50,14,NULL),
	(31,'智能设备','','','',NULL,1,NULL,'',50,30,NULL),
	(32,'智能手表','','','',NULL,1,NULL,'',50,30,NULL),
	(33,'智能手环','','','',NULL,1,NULL,'',50,30,NULL),
	(34,'VR眼镜','','','',NULL,1,NULL,'',50,30,NULL),
	(35,'智能摄像','','','',NULL,1,NULL,'',50,30,NULL),
	(36,'智能健康','','','',NULL,1,NULL,'',50,30,NULL),
	(37,'智能机器人','','','',NULL,1,NULL,'',50,30,NULL),
	(38,'硬件存储','','','',NULL,1,NULL,'',50,14,NULL),
	(39,'显示器','','','',NULL,1,NULL,'',50,38,NULL),
	(40,'机械键盘','','','',NULL,1,NULL,'',50,38,NULL),
	(41,'固态硬盘','','','',NULL,1,NULL,'',50,38,NULL),
	(42,'CPU','','','',NULL,1,NULL,'',50,38,NULL),
	(43,'显卡','','','',NULL,1,NULL,'',50,38,NULL),
	(44,'主板','','','',NULL,1,NULL,'',50,38,NULL),
	(45,'高速U盘','','','',NULL,1,NULL,'',50,38,NULL),
	(46,'路由器','','','',NULL,1,NULL,'',50,38,NULL),
	(47,'摄影摄像','','','',NULL,1,NULL,'',50,14,NULL),
	(48,'相机','','','',NULL,1,NULL,'',50,47,NULL),
	(49,'单反','','','',NULL,1,NULL,'',50,47,NULL),
	(50,'单电微单','','','',NULL,1,NULL,'',50,47,NULL),
	(51,'摄像机','','','',NULL,1,NULL,'',50,47,NULL),
	(52,'自拍神器','','','',NULL,1,NULL,'',50,47,NULL),
	(53,'拍立得','','','',NULL,1,NULL,'',50,47,NULL),
	(54,'镜头','','','',NULL,1,NULL,'',50,47,NULL),
	(55,'自拍杆','','','',NULL,1,NULL,'',50,47,NULL),
	(56,'影音娱乐','','','',NULL,1,NULL,'',50,14,NULL),
	(57,'耳机','','','',NULL,1,NULL,'',50,56,NULL),
	(58,'天猫魔盒','','','',NULL,1,NULL,'',50,56,NULL),
	(59,'数码影音','','','',NULL,1,NULL,'',50,56,NULL),
	(60,'家庭影院','','','',NULL,1,NULL,'',50,56,NULL),
	(61,'蓝牙耳机','','','',NULL,1,NULL,'',50,56,NULL),
	(62,'网络播放器','','','',NULL,1,NULL,'',50,56,NULL),
	(63,'精选上装','','','',NULL,1,'/public/upload/20200907/12e2badae06d0be8dd1f6186c4142ea6.jpg','',50,1,2),
	(64,'女士下装','','','',NULL,1,'/public/upload/20200907/bc7a1f58d4d737ffc2842f9483f2d7b0.jpg','',50,1,0),
	(65,'特色女装','','','',NULL,1,'/public/upload/20200907/5b999d2f7a73e7616fe6f86e8b9eab4b.jpg','',50,1,0),
	(66,'文胸塑身','','','',NULL,1,'/public/upload/20200907/049adc94a689a1ec4bccba90c43e719a.jpg','',50,1,0),
	(67,'毛呢外套','','','',NULL,1,NULL,'',50,63,NULL),
	(68,'羽绒服','','','',NULL,1,NULL,'',50,63,NULL),
	(69,'棉服','','','',NULL,1,NULL,'',50,63,NULL),
	(70,'丝绒卫衣','','','',NULL,1,NULL,'',50,63,NULL),
	(71,'毛针织衫','','','',NULL,1,NULL,'',50,63,NULL),
	(72,'皮毛一体','','','',NULL,1,NULL,'',50,63,NULL),
	(73,'皮草','','','',NULL,1,NULL,'',50,63,NULL),
	(74,'毛衣','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,63,2),
	(75,'衬衫','','','',NULL,1,NULL,'',50,63,NULL),
	(76,'卫衣','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,63,2),
	(77,'针织衫','','','',NULL,1,NULL,'',50,63,NULL),
	(78,'T恤','','','',NULL,1,NULL,'',50,63,NULL),
	(79,'短外套','','','',NULL,1,NULL,'',50,63,NULL),
	(80,'小西装','','','',NULL,1,NULL,'',50,63,NULL),
	(81,'风衣','','','',NULL,1,NULL,'',50,63,NULL),
	(82,'连衣裙','','','',NULL,1,NULL,'',50,2,NULL),
	(83,'半身裙','','','',NULL,1,NULL,'',50,2,NULL),
	(84,'A字裙','','','',NULL,1,NULL,'',50,2,NULL),
	(85,'荷叶边裙','','','',NULL,1,NULL,'',50,2,NULL),
	(86,'大摆裙','','','',NULL,1,NULL,'',50,2,NULL),
	(87,'包臀裙','','','',NULL,1,NULL,'',50,2,NULL),
	(88,'百褶裙','','','',NULL,1,NULL,'',50,2,NULL),
	(89,'长袖','','','',NULL,1,NULL,'',50,2,NULL),
	(90,'连衣裙','','','',NULL,1,NULL,'',50,2,NULL),
	(91,'棉麻连衣裙','','','',NULL,1,NULL,'',50,2,NULL),
	(92,'牛仔裙','','','',NULL,1,NULL,'',50,2,NULL),
	(93,'蕾丝连衣裙','','','',NULL,1,NULL,'',50,2,NULL),
	(94,'真丝连衣裙','','','',NULL,1,NULL,'',50,2,NULL),
	(95,'印花连衣裙','','','',NULL,1,NULL,'',50,2,NULL),
	(96,'春夏家居服','','','',NULL,1,NULL,'',50,6,NULL),
	(97,'纯棉家居服','','','',NULL,1,NULL,'',50,6,NULL),
	(98,'莫代尔家居服','','','',NULL,1,NULL,'',50,6,NULL),
	(99,'真丝家居服','','','',NULL,1,NULL,'',50,6,NULL),
	(100,'春夏睡裙','','','',NULL,1,NULL,'',50,6,NULL),
	(101,'男士家居服','','','',NULL,1,NULL,'',50,6,NULL),
	(102,'情侣家居服','','','',NULL,1,NULL,'',50,6,NULL),
	(103,'性感睡裙','','','',NULL,1,NULL,'',50,6,NULL),
	(104,'休闲裤','','','',NULL,1,NULL,'',50,64,NULL),
	(105,'阔腿裤','','','',NULL,1,NULL,'',50,64,NULL),
	(106,'牛仔裤','','','',NULL,1,NULL,'',50,64,NULL),
	(107,'打底裤','','','',NULL,1,NULL,'',50,64,NULL),
	(108,'开叉运动裤','','','',NULL,1,NULL,'',50,64,NULL),
	(109,'哈伦裤','','','',NULL,1,NULL,'',50,64,NULL),
	(110,'背带裤','','','',NULL,1,NULL,'',50,64,NULL),
	(111,'小脚裤','','','',NULL,1,NULL,'',50,64,NULL),
	(112,'西装裤','','','',NULL,1,NULL,'',50,64,NULL),
	(113,'短裤','','','',NULL,1,NULL,'',50,64,NULL),
	(114,'时尚套装','','','',NULL,1,NULL,'',50,65,NULL),
	(115,'休闲套装','','','',NULL,1,NULL,'',50,65,NULL),
	(116,'日系女装','','','',NULL,1,NULL,'',50,65,NULL),
	(117,'精选妈妈装','','','',NULL,1,NULL,'',50,65,NULL),
	(118,'大码女装','','','',NULL,1,NULL,'',50,65,NULL),
	(119,'职业套装','','','',NULL,1,NULL,'',50,65,NULL),
	(120,'优雅旗袍','','','',NULL,1,NULL,'',50,65,NULL),
	(121,'精致礼服','','','',NULL,1,NULL,'',50,65,NULL),
	(122,'婚纱','','','',NULL,1,NULL,'',50,65,NULL),
	(123,'唐装','','','',NULL,1,NULL,'',50,65,NULL),
	(124,'小码女装','','','',NULL,1,NULL,'',50,65,NULL),
	(125,'光面文胸','','','',NULL,1,NULL,'',50,66,NULL),
	(126,'运动文胸','','','',NULL,1,NULL,'',50,66,NULL),
	(127,'美背文胸','','','',NULL,1,NULL,'',50,66,NULL),
	(128,'聚拢文胸','','','',NULL,1,NULL,'',50,66,NULL),
	(129,'大杯文胸','','','',NULL,1,NULL,'',50,66,NULL),
	(130,'轻薄塑身','','','',NULL,1,NULL,'',50,66,NULL),
	(131,'家居服','','','','',1,'/public/upload/20200907/6502a54382a96c2f370933c280a8456f.jpg','',50,1,1),
	(132,'精选上衣','','','','',1,'/public/upload/20200907/be5921de5ee20e4932305a8dabd8d7d8.jpg','',50,1,1),
	(133,'家居服A','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,6,2),
	(134,'家居服B','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,6,2),
	(138,'进口零食','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,13,1),
	(139,'休闲零食','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,13,1),
	(140,'酒类','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,13,1),
	(141,'茶叶1','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,13,1),
	(142,'茶叶2','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,13,1),
	(143,'','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,13,1),
	(144,'大牌乐器','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,12,1),
	(145,'儿童读书','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,12,1),
	(146,'儿童读物1','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,12,1),
	(148,'儿童读书2','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,12,1),
	(149,'儿童读书3','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,12,1),
	(150,'玩具','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,11,1),
	(151,'童装','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,11,1),
	(155,'电视机','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,4,3),
	(153,'婴儿服','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,11,1),
	(154,'奶粉','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,11,1),
	(156,'液晶电视机','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,155,3),
	(157,'灯饰照明','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,4,0),
	(158,'灯泡','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,157,0),
	(159,'办公文教','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,14,0),
	(160,'婴儿玩具','','','',NULL,1,'/public/static/index/images/thumb.jpg','',50,150,0);

/*!40000 ALTER TABLE `qing_category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_collect
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_collect`;

CREATE TABLE `qing_collect` (
  `id` int NOT NULL AUTO_INCREMENT,
  `goods_id` int NOT NULL,
  `time` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品收藏';

LOCK TABLES `qing_collect` WRITE;
/*!40000 ALTER TABLE `qing_collect` DISABLE KEYS */;

INSERT INTO `qing_collect` (`id`, `goods_id`, `time`, `user_id`)
VALUES
	(19,1,0,1),
	(30,3,1613400954,1),
	(23,5,0,1),
	(28,9,1613378109,1),
	(29,2,1613400889,1),
	(27,8,0,1);

/*!40000 ALTER TABLE `qing_collect` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_comment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_comment`;

CREATE TABLE `qing_comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL DEFAULT '0',
  `content` varchar(255) NOT NULL DEFAULT '',
  `goods_id` int NOT NULL DEFAULT '0',
  `status` int DEFAULT '0' COMMENT '0 正常 1删除',
  `time` int NOT NULL DEFAULT '0',
  `star` tinyint NOT NULL DEFAULT '3',
  `order_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `qing_comment` WRITE;
/*!40000 ALTER TABLE `qing_comment` DISABLE KEYS */;

INSERT INTO `qing_comment` (`id`, `user_id`, `content`, `goods_id`, `status`, `time`, `star`, `order_id`)
VALUES
	(1,1,'很棒的哦',5,0,1599182644,5,2),
	(2,1,'还可以吧',4,0,1599182681,4,4),
	(3,1,'还凑合吧',5,0,1599182705,4,6),
	(4,1,'手感不错呢',1,0,1599182723,5,6),
	(6,1,'好评',4,0,1599211817,5,10),
	(7,1,'好评',6,0,1599212292,5,3),
	(15,1,'好评',4,0,1599221031,5,13),
	(14,1,'好评',7,0,1599221030,5,13),
	(13,1,'好评',5,0,1599221028,5,12),
	(12,1,'好评',3,0,1599221027,5,12),
	(16,1,'好评',8,1,1599221571,5,11),
	(17,1,'嘿嘿嘿',8,0,1613479346,4,16),
	(18,1,'嘿嘿嘿',8,0,1613479411,5,16),
	(19,1,'嘿嘿嘿',8,0,1613479453,5,16),
	(20,1,'嘿嘿嘿',8,0,1613479482,5,16),
	(21,1,'嘿嘿嘿',8,0,1613479526,4,16),
	(22,1,'嘿嘿嘿',8,0,1613479562,2,16),
	(23,1,'嘿嘿嘿',8,0,1613479627,2,16);

/*!40000 ALTER TABLE `qing_comment` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_config`;

CREATE TABLE `qing_config` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT COMMENT '配置项id',
  `cname` varchar(60) NOT NULL COMMENT '中文名称',
  `ename` varchar(60) NOT NULL COMMENT '英文名称',
  `value` text NOT NULL COMMENT '默认值',
  `values` varchar(255) NOT NULL COMMENT '可选值',
  `field_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1：输入框 2：文本域  3：复选 4：下拉菜单 6：附件',
  `config_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1：基本信息 2：联系方式 3：seo设置 ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `qing_config` WRITE;
/*!40000 ALTER TABLE `qing_config` DISABLE KEYS */;

INSERT INTO `qing_config` (`id`, `cname`, `ename`, `value`, `values`, `field_type`, `config_type`)
VALUES
	(1,'网站名称','webname','小刘科技有限公司','',1,1),
	(2,'网站域名','domain','http://www.think.com','',1,1),
	(13,'SEO关键字','index_keywords','','',1,1),
	(4,'版权信息','copyright','© 版权所有 北京小米科技有限中心 保留所有权利','',1,1),
	(5,'备案号','beian','沪ICP备14037785号','',1,1),
	(6,'统计代码','cnzz','','',2,1),
	(7,'地址','address','1','',1,2),
	(8,'电话1','tel1','010-8697888','',1,2),
	(9,'电话2','tel2','','',1,2),
	(10,'QQ','qq','','',1,2),
	(11,'邮箱','email','','',1,2),
	(12,'传真','fax','','',1,2),
	(14,'SEO描述','seo_description','','',1,1),
	(15,'签到赚钱积分','score','11','',1,1),
	(16,'生日值每天领取数量','shengrizhi_number','10','',1,1),
	(17,'积分兑换','jifen','每次只能兑换5个','',1,2);

/*!40000 ALTER TABLE `qing_config` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_coupons
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_coupons`;

CREATE TABLE `qing_coupons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `money1` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `money2` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `time1` int NOT NULL COMMENT '开始时间',
  `time2` int NOT NULL COMMENT '结束时间',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `count` int NOT NULL DEFAULT '1' COMMENT '优惠券数量',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='优惠券';

LOCK TABLES `qing_coupons` WRITE;
/*!40000 ALTER TABLE `qing_coupons` DISABLE KEYS */;

INSERT INTO `qing_coupons` (`id`, `money1`, `money2`, `time1`, `time2`, `name`, `count`, `status`)
VALUES
	(2,'100','10',1588671659,1620207661,'春节大促',4,1),
	(3,'200','20',1595952000,1715875200,'2月就业季',5,1),
	(4,'50','10',1613368388,1615787601,'新人优惠券',4,1),
	(5,'100','20',1412158748,1415787554,'2月就业季',5,1);

/*!40000 ALTER TABLE `qing_coupons` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_coupons_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_coupons_user`;

CREATE TABLE `qing_coupons_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `coupons_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '0' COMMENT '0未使用，1已使用',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='优惠券';

LOCK TABLES `qing_coupons_user` WRITE;
/*!40000 ALTER TABLE `qing_coupons_user` DISABLE KEYS */;

INSERT INTO `qing_coupons_user` (`id`, `user_id`, `coupons_id`, `status`)
VALUES
	(8,1,4,0),
	(7,1,2,0);

/*!40000 ALTER TABLE `qing_coupons_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_express
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_express`;

CREATE TABLE `qing_express` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL COMMENT '快递公司代号',
  `name` varchar(20) NOT NULL COMMENT '快递公司名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='快递公司代号';

LOCK TABLES `qing_express` WRITE;
/*!40000 ALTER TABLE `qing_express` DISABLE KEYS */;

INSERT INTO `qing_express` (`id`, `code`, `name`)
VALUES
	(1,'BTWL','百世快运'),
	(2,'DBL','德邦'),
	(3,'EMS','EMS'),
	(4,'HHTT','天天快递'),
	(5,'HTKY','百世快递'),
	(6,'JJKY','佳吉快运'),
	(7,'QFKD','全峰快递'),
	(8,'SF','顺丰快递'),
	(9,'STO','申通快递'),
	(10,'YD','韵达快递'),
	(11,'YTO','圆通速递'),
	(12,'YZPY','邮政平邮/小包'),
	(13,'ZTO','中通速递');

/*!40000 ALTER TABLE `qing_express` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_fapiao
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_fapiao`;

CREATE TABLE `qing_fapiao` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `time` int NOT NULL,
  `money` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `beizhu` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未开具,1已开具',
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `out_trade_no` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '订单号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='发票管理';

LOCK TABLES `qing_fapiao` WRITE;
/*!40000 ALTER TABLE `qing_fapiao` DISABLE KEYS */;

INSERT INTO `qing_fapiao` (`id`, `company`, `code`, `time`, `money`, `beizhu`, `status`, `email`, `user_id`, `out_trade_no`)
VALUES
	(1,'北京小米科技有限公司','ABCDEFG',1575511676,'1000','网站建设',1,'1031950015@qq.com',5,'1a1dd5c799de66c67736ca2d7a2e5305'),
	(2,'北京百度','ABCCCCC',144444444,'2000','网站建设',0,'1231312131@qq.com',5,'79d582a96a711d0cddcd05f87aa5261e'),
	(3,'小刘科技技术有限公司','111331331113',1613222932,'3811','测试',0,'bj_liunan@126.com',5,'aa30be8e6f38677635b40e778e272879'),
	(4,'小刘科技技术有限公司','111331331113',1613367243,'1899','测试',0,'bj_liunan@126.com',1,'61f7f2bf2d2dc72a38a8ebe80e2fc974'),
	(5,'小刘科技技术有限公司','111331331113',1613474813,'0.02','测试',0,'bj_liunan@126.com',1,'79d582a96a711d0cddcd05f87aa5261e');

/*!40000 ALTER TABLE `qing_fapiao` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_fenyong
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_fenyong`;

CREATE TABLE `qing_fenyong` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int NOT NULL COMMENT '用户ID',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `qing_fenyong` WRITE;
/*!40000 ALTER TABLE `qing_fenyong` DISABLE KEYS */;

INSERT INTO `qing_fenyong` (`id`, `code`, `user_id`)
VALUES
	(2,'YJ1613276098',5),
	(3,'YJ1613276098',6);

/*!40000 ALTER TABLE `qing_fenyong` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_flink
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_flink`;

CREATE TABLE `qing_flink` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='友情链接表';

LOCK TABLES `qing_flink` WRITE;
/*!40000 ALTER TABLE `qing_flink` DISABLE KEYS */;

INSERT INTO `qing_flink` (`id`, `name`, `url`)
VALUES
	(1,'米家','www.mijia1.com');

/*!40000 ALTER TABLE `qing_flink` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_goods
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_goods`;

CREATE TABLE `qing_goods` (
  `goods_id` int NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(200) NOT NULL,
  `goods_short_name` varchar(60) DEFAULT NULL,
  `goods_thumb` varchar(200) DEFAULT NULL,
  `goods_price` varchar(50) DEFAULT NULL,
  `goods_status` tinyint NOT NULL DEFAULT '1' COMMENT '上架1下架-1',
  `goods_cate_id` int NOT NULL DEFAULT '0',
  `market_price` varchar(100) DEFAULT NULL,
  `keywords` varchar(200) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `time` int NOT NULL DEFAULT '0',
  `type_id` int DEFAULT NULL,
  `isnew` int NOT NULL DEFAULT '0',
  `isbest` int NOT NULL DEFAULT '0',
  `ishot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '热销',
  `goods_code` int DEFAULT NULL,
  `listorder` int DEFAULT '0',
  `cate_path` varchar(100) DEFAULT NULL COMMENT '分类路径',
  `xiaoliang` int DEFAULT '0',
  `single_standard` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1统一规格，2多规格',
  `post_money` varchar(20) NOT NULL DEFAULT '0' COMMENT '邮费',
  `stock` int NOT NULL DEFAULT '0' COMMENT '库存',
  `selnumber` int NOT NULL DEFAULT '0' COMMENT '销量',
  `click` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品表';

LOCK TABLES `qing_goods` WRITE;
/*!40000 ALTER TABLE `qing_goods` DISABLE KEYS */;

INSERT INTO `qing_goods` (`goods_id`, `goods_name`, `goods_short_name`, `goods_thumb`, `goods_price`, `goods_status`, `goods_cate_id`, `market_price`, `keywords`, `description`, `time`, `type_id`, `isnew`, `isbest`, `ishot`, `goods_code`, `listorder`, `cate_path`, `xiaoliang`, `single_standard`, `post_money`, `stock`, `selnumber`, `click`)
VALUES
	(1,'小米11','享12期分期免息，赠小米129元双动圈耳机','/public/upload/20200812/ee3efe2b59b4f924b27f3939c74348bc.jpg','1600',1,16,'1800','','',1597200669,1,0,1,1,NULL,0,'14_18_16',0,2,'0',0,1,93),
	(2,'小米22',' 4500mAh+33W闪充','/public/upload/20200625/cd4055e97782ef3b2d60fea5076c1572.jpg','1600',1,16,'1800','','',1600656761,1,0,1,1,NULL,1,'14_18_16',0,2,'1',0,5,15),
	(3,'华为111','120Hz弹出全面屏','/public/upload/20200625/d4df8f952223634d1fec851dd0522107.jpg','0.01',1,28,'0.02','','',1600656726,1,0,1,1,NULL,2,'14_18_28',0,2,'0',0,0,63),
	(4,'电视机11','','/public/upload/20200625/0759f34f3c05e3e800fe811f8d94c2e6.jpg','5888',1,156,'8000','','',1593080663,3,0,1,1,NULL,0,'4_155_156',0,2,'0',0,12,17),
	(5,'智睿LED灯泡 10只装','','/public/upload/20200625/b014f074c410cd1f72b2c05e5dc37d04.jpg','99',1,158,'199','','',1593080706,0,0,1,1,NULL,1,'4_157_158',0,1,'0',100,2,43),
	(6,'小米33','「6GB+128GB到手价仅1399元；8GB+128GB到手价仅1599元；8GB+256GB到手价仅1799元」','/public/upload/20200810/7c2921c04fda2943e8b4d41011ea99d1.jpg','1900',1,16,'2100','','5000mAh长循环大电量 / 6.53\"超大护眼屏幕 / G25八核处理器 / 大音量扬声器 / 1300万 AI相机 / 人脸解锁 / 最高支持512GB存储扩展',1597049554,1,0,1,1,NULL,0,'14_18_16',0,2,'11',0,4,46),
	(7,'Redmi手环','','/public/upload/20200904/bc87fb6becf47e9afabfc0ee61a6c3bd.jpg','99',1,33,'149','','1.08英寸大屏彩显 / 14天续航，快拆直插充电 / 腕上信息提醒，一目了然 / 科学算法，守护你的健康',1599181773,0,0,1,1,NULL,0,'14_30_33',0,1,'0',0,0,11),
	(8,'小米户外蓝牙音箱','','/public/upload/20200904/d0aa10a258d17ec93e6bd03ea5d80719.jpg','0.02',1,59,'199','','大音量 / 长续航 / 轻盈便携 / 360°环绕立体声 / IP55防尘防水 / 双麦克风降噪通话 / 蓝牙5.0 / type-c接口',1600508382,0,0,1,1,NULL,0,'14_56_59',0,1,'0',0,0,9),
	(9,'2020鼠年卡通创意滴胶小老鼠可爱钥匙扣挂件钥匙链情侣网红包挂件','','/public/upload/20200919/324f03e92af57a6f1dbbec0e04f29870.jpg','1',1,160,'6','','',1600509569,0,0,1,1,NULL,0,'11_150_160',0,1,'0',0,0,9),
	(10,'洋气网红上衣春秋季2020新款秋装短款针织开衫粗线很仙的毛衣外套','','/public/upload/20200919/702a1b47fd2d89863efb072179c9d4d3.jpg','2',-1,74,'5','','',1600510090,2,0,1,1,NULL,0,'1_63_74',0,2,'0',0,0,2),
	(11,'MLB官方 男女卫衣复古老花系列长袖宽松运动休闲潮流圆领秋季MTM1','','/public/upload/20200919/3ab5ae38ba4b52b26bddb09f5d32c2ea.jpg','1.5',-1,76,'2','','',1600659603,2,0,1,1,NULL,0,'1_63_76',0,2,'0',0,0,5),
	(12,'全面屏电视E43K','全面屏设计，海量内容','/public/upload/20200921/266ea6ccc529110760cb3b238b8528da.jpg','1300',1,156,'1500','','',1600657906,3,0,1,1,NULL,0,'4_155_156',0,2,'0',0,0,6),
	(13,'高腰打底裤女裤外穿春秋冬款紧身显瘦百搭小脚黑色铅笔魔术小黑裤','高腰收腹版型显瘦/显腿长+定制不抽丝不起球','/public/upload/20200921/0f857bc7876c24ab07aabb5589b8b716.jpg','65',1,111,'88','','',1600660630,2,0,1,1,NULL,0,'1_64_111',0,2,'1',0,0,7),
	(14,'测试1','测试1','/public/upload/20210211/2eb6913af643d8f35ad0a25ff9dfd776.jpeg','0100',-1,82,'00100','测试1','测试1',1613052813,2,0,1,1,NULL,0,'1_2_82',0,2,'0',100,0,1);

/*!40000 ALTER TABLE `qing_goods` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_goods_attr
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_goods_attr`;

CREATE TABLE `qing_goods_attr` (
  `id` int NOT NULL AUTO_INCREMENT,
  `goods_id` int NOT NULL,
  `standard_value_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商品属性表';

LOCK TABLES `qing_goods_attr` WRITE;
/*!40000 ALTER TABLE `qing_goods_attr` DISABLE KEYS */;

INSERT INTO `qing_goods_attr` (`id`, `goods_id`, `standard_value_id`)
VALUES
	(1,1,2),
	(2,1,6),
	(3,1,10),
	(4,2,5),
	(5,2,3),
	(6,2,10),
	(7,3,11),
	(8,3,16);

/*!40000 ALTER TABLE `qing_goods_attr` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_goods_content
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_goods_content`;

CREATE TABLE `qing_goods_content` (
  `goods_id` int NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商品详情表';

LOCK TABLES `qing_goods_content` WRITE;
/*!40000 ALTER TABLE `qing_goods_content` DISABLE KEYS */;

INSERT INTO `qing_goods_content` (`goods_id`, `content`)
VALUES
	(1,''),
	(2,''),
	(3,''),
	(4,''),
	(5,''),
	(6,'<p>小米手机还不错的</p>'),
	(7,'<p><img src=\"https://cdn.cnbj1.fds.api.mi-img.com/mi-mall/c9db39101d147cdf7a70b672cf0f1cf4.jpg\"/></p>'),
	(8,'<p style=\"text-align: center;\"><img src=\"https://cdn.cnbj1.fds.api.mi-img.com/mi-mall/c46236720812ec59cf38fae1faddcdd1.jpg\"/></p>'),
	(9,''),
	(10,'<p><img src=\"https://img.alicdn.com/imgextra/i3/1792580015/TB2pTmDairz11Bjy1XaXXbRrFXa_!!1792580015.jpg\"/></p><p><img src=\"https://img.alicdn.com/imgextra/i2/1792580015/TB2lEayaaLB11BjSspkXXcy9pXa_!!1792580015.jpg\"/></p>'),
	(11,'<p style=\"text-align: center;\"><img src=\"https://img.alicdn.com/imgextra/i1/2201410209674/O1CN01LKNbm22LKk8kepzxG_!!2201410209674.jpg\"/></p>'),
	(12,'<p style=\"text-align: center;\"><img src=\"https://cdn.cnbj1.fds.api.mi-img.com/mi-mall/e627ee5af6c036206496abc30102d994.jpg?w=1212&h=716\"/></p>'),
	(13,'<p style=\"text-align: center;\"><img src=\"https://img.alicdn.com/imgextra/i2/3829438756/O1CN01GBJhCv2EYIXasjmuR_!!3829438756.jpg\"/></p>'),
	(14,'<p>0100</p>');

/*!40000 ALTER TABLE `qing_goods_content` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_goods_img
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_goods_img`;

CREATE TABLE `qing_goods_img` (
  `id` int NOT NULL AUTO_INCREMENT,
  `goods_id` int NOT NULL,
  `image` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商品轮播图';

LOCK TABLES `qing_goods_img` WRITE;
/*!40000 ALTER TABLE `qing_goods_img` DISABLE KEYS */;

INSERT INTO `qing_goods_img` (`id`, `goods_id`, `image`)
VALUES
	(20,4,'/public/upload/20200625/0759f34f3c05e3e800fe811f8d94c2e6.jpg'),
	(21,4,'/public/upload/20200625/332d25febd2802c6d25646e490f904e6.jpg'),
	(22,4,'/public/upload/20200625/e2edfb80ef55f6b3d5e3f5143ec2f451.jpg'),
	(23,4,'/public/upload/20200625/77d3b56a29fea1d1e52644972bb649e1.jpg'),
	(24,4,'/public/upload/20200625/6e902f359bc7be76f32a6355dfac2746.jpg'),
	(25,5,'/public/upload/20200625/b014f074c410cd1f72b2c05e5dc37d04.jpg'),
	(68,6,'/public/upload/20200810/7c2921c04fda2943e8b4d41011ea99d1.jpg'),
	(69,6,'/public/upload/20200810/7e9ba4838998b81b1b8db30628ec0b7b.jpg'),
	(70,6,'/public/upload/20200810/6645cedc4b3209c22722f7296c310311.jpg'),
	(71,6,'/public/upload/20200810/9307a11a5f65ccda184792ff8ffc2834.jpg'),
	(72,1,'/public/upload/20200812/ee3efe2b59b4f924b27f3939c74348bc.jpg'),
	(73,1,'/public/upload/20200812/9814b9a180076f7e2b80498c2b8bd0ae.jpg'),
	(74,1,'/public/upload/20200812/9814b9a180076f7e2b80498c2b8bd0ae.jpg'),
	(75,1,'/public/upload/20200812/dd0fb4eec5422a2677406e96bb6fadfb.jpg'),
	(76,1,'/public/upload/20200812/392f31c3418bed3ce41d01470db4b7d3.jpg'),
	(82,7,'/public/upload/20200904/bc87fb6becf47e9afabfc0ee61a6c3bd.jpg'),
	(88,8,'/public/upload/20200904/d0aa10a258d17ec93e6bd03ea5d80719.jpg'),
	(89,8,'/public/upload/20200904/ad79ea14236173520b3b0fb00999f408.jpg'),
	(90,8,'/public/upload/20200904/627afb0553e9bde45664dd500c6c7c8c.jpg'),
	(91,8,'/public/upload/20200904/9feba4043dfd2d2bb27f78976a03ddb4.jpg'),
	(92,8,'/public/upload/20200904/4396635239cc67b0be30a98333a3c22c.jpg'),
	(93,9,'/public/upload/20200919/324f03e92af57a6f1dbbec0e04f29870.jpg'),
	(94,9,'/public/upload/20200919/db94d077adc4e9a9c9ba508f8eb72768.jpg'),
	(95,9,'/public/upload/20200919/b4fb57a344e0998d56e78f236ca4bd35.jpg'),
	(100,10,'/public/upload/20200919/702a1b47fd2d89863efb072179c9d4d3.jpg'),
	(101,10,'/public/upload/20200919/b45cfdc09b411bb3b00da4d845fd255a.jpg'),
	(102,10,'/public/upload/20200919/b9d032f681d267e796300dbcb780664b.jpg'),
	(103,10,'/public/upload/20200919/4bfa2441d7f54b36b89ed1fd4c91ab00.jpg'),
	(106,3,'/public/upload/20200625/d4df8f952223634d1fec851dd0522107.jpg'),
	(107,3,'/public/upload/20200625/ff665c01957af1b48ff7978132310e81.jpg'),
	(108,3,'/public/upload/20200625/0978b87433b960b2d2b54128b6d238da.jpg'),
	(109,3,'/public/upload/20200625/43d53215feaebb803e3e8f1ef5bae756.jpg'),
	(110,3,'/public/upload/20200625/ffb84865e7ca78eff20070d918af34ee.jpg'),
	(111,2,'/public/upload/20200625/cd4055e97782ef3b2d60fea5076c1572.jpg'),
	(112,2,'/public/upload/20200625/c2854a4d78f1f8433a4f266f477d8e64.jpg'),
	(113,2,'/public/upload/20200625/7f45b8c16aad5cc21627309114bb21ab.jpg'),
	(114,2,'/public/upload/20200625/ec97ef5110c2d4a29e26e354b187d76c.jpg'),
	(115,2,'/public/upload/20200625/ed8a71b4be046e840404804b3b71c5dd.jpg'),
	(131,12,'/public/upload/20200921/266ea6ccc529110760cb3b238b8528da.jpg'),
	(132,12,'/public/upload/20200921/ef79cb9bd8947a88c2dd775571ebb12e.jpg'),
	(133,12,'/public/upload/20200921/6c50423542b12e4968a54a93ebb9bd27.jpg'),
	(134,12,'/public/upload/20200921/4404ad5bac0cd8a39966d8cbff60f426.jpg'),
	(135,12,'/public/upload/20200921/40663991fce833735ee5a82852133fe9.jpg'),
	(136,11,'/public/upload/20200919/3ab5ae38ba4b52b26bddb09f5d32c2ea.jpg'),
	(137,11,'/public/upload/20200919/a97128f488f3868c0a3cc50f3f7f4205.jpg'),
	(142,13,'/public/upload/20200921/0f857bc7876c24ab07aabb5589b8b716.jpg'),
	(143,13,'/public/upload/20200921/0f857bc7876c24ab07aabb5589b8b716.jpg'),
	(144,13,'/public/upload/20200921/61816b80ac5b6e138e828f116c4749ec.jpg'),
	(145,13,'/public/upload/20200921/326f5f49115feaea20d390f222745df8.jpg'),
	(152,14,'/public/upload/20210211/2eb6913af643d8f35ad0a25ff9dfd776.jpeg'),
	(153,14,'/public/upload/20210211/e0f224d0104ac47469bf3da3fcdd7905.jpeg'),
	(154,14,'/public/upload/20210211/378ae400b9d26a08d7e7a3d815672107.jpeg'),
	(155,14,'/public/upload/20210211/a080f520ab23cf0a40b73846ba8a2b52.jpeg'),
	(156,14,'/public/upload/20210211/2818e8f0b2fc2fb985e6e33a78b870cb.jpeg'),
	(157,14,'/public/upload/20210211/312d3ac5b01ccdfbc888f2bfd5ba60dc.jpeg');

/*!40000 ALTER TABLE `qing_goods_img` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_goods_standard
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_goods_standard`;

CREATE TABLE `qing_goods_standard` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `goods_id` int unsigned NOT NULL DEFAULT '0' COMMENT '基本信息ID',
  `goods_price` varchar(100) DEFAULT NULL COMMENT '市场价',
  `market_price` varchar(100) DEFAULT '0' COMMENT '市场价格',
  `stock` int DEFAULT NULL COMMENT '库存',
  `sku` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品表';

LOCK TABLES `qing_goods_standard` WRITE;
/*!40000 ALTER TABLE `qing_goods_standard` DISABLE KEYS */;

INSERT INTO `qing_goods_standard` (`id`, `goods_id`, `goods_price`, `market_price`, `stock`, `sku`)
VALUES
	(63,1,'1800','2000',0,'2,10,23'),
	(82,2,'1700','1800',0,'3,6,23'),
	(81,2,'1700','1800',0,'3,5,23'),
	(77,3,'0.01','0.02',0,'2,6,24'),
	(13,4,'5888','8000',0,'11,16'),
	(80,2,'1600','1800',0,'2,6,23'),
	(79,2,'1600','1800',0,'2,5,23'),
	(59,6,'2000','2100',0,'3,6,23'),
	(62,1,'1800','2000',0,'2,6,23'),
	(61,1,'1600','1800',0,'1,10,23'),
	(60,1,'1600','1800',0,'1,6,23'),
	(58,6,'2000','2100',0,'3,5,23'),
	(57,6,'1900','2100',0,'2,6,23'),
	(56,6,'1900','2100',0,'2,5,23'),
	(69,10,'2','5',0,'7,14'),
	(70,10,'2','5',0,'7,15'),
	(71,10,'2','5',0,'8,14'),
	(72,10,'2','5',0,'8,15'),
	(87,11,'1.5','2',0,'26,14'),
	(86,11,'1.5','2',0,'7,25'),
	(85,11,'1.5','2',0,'7,14'),
	(78,3,'0.01','0.02',0,'2,10,24'),
	(84,12,'1300','1500',0,'27,28'),
	(88,11,'1.5','2',0,'26,25'),
	(89,13,'65','88',0,'7,30'),
	(90,13,'65','88',0,'8,30'),
	(91,13,'65','88',0,'29,30'),
	(102,14,'00100','0100',100,'9,14'),
	(101,14,'00100','00100',100,'8,15'),
	(100,14,'00100','00100',100,'8,14'),
	(99,14,'00100','00100',100,'7,15'),
	(98,14,'0100','00100',100,'7,14'),
	(103,14,'0100','0100',100,'9,15');

/*!40000 ALTER TABLE `qing_goods_standard` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_message
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_message`;

CREATE TABLE `qing_message` (
  `id` int NOT NULL AUTO_INCREMENT,
  `content` varchar(200) NOT NULL,
  `name` varchar(30) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `email` varchar(20) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `time` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `qing_message` WRITE;
/*!40000 ALTER TABLE `qing_message` DISABLE KEYS */;

INSERT INTO `qing_message` (`id`, `content`, `name`, `mobile`, `email`, `address`, `time`)
VALUES
	(2,'还哦不错','王大力','13988598596','shijiazhuang@126.com','河北省石家庄市裕华区和合大厦201',1575511676);

/*!40000 ALTER TABLE `qing_message` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_news
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_news`;

CREATE TABLE `qing_news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `thumb` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `time` int NOT NULL,
  `description` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='新闻文章';

LOCK TABLES `qing_news` WRITE;
/*!40000 ALTER TABLE `qing_news` DISABLE KEYS */;

INSERT INTO `qing_news` (`id`, `title`, `thumb`, `content`, `time`, `description`)
VALUES
	(2,'永远相信美好的事情即将发生','\\public\\upload/20200504\\c88accb7e23efbc1d0e36d956b958c53.jpg','<p style=\"text-align: center;\"><img src=\"/ueditor/php/upload/image/20200504/1588583651379544.png\" title=\"1588583651379544.png\" alt=\"image.png\"/></p><p><br/></p><p>小米公司正式成立于2010年4月，是一家以手机、智能硬件和 IoT 平台为核心的互联网公司。创业仅7年时间，小米的年收入就突破了千亿元人民币。截止2018年，小米的业务遍及全球80多个国家和地区。&nbsp;&nbsp;</p><p>小米的使命是，始终坚持做“感动人心、价格厚道”的好产品，让全球每个人都能享受科技带来的美好生活。</p>',1588562918,'小米的使命是，始终坚持做“感动人心、价格厚道”的好产品，让全球每个人都能享受科技带来的美好生活。'),
	(3,'感谢您关注小米','\\public\\upload/20200504\\6b0b7e2c56044e7b9264576d8e5ea97e.jpg','<p>目前，小米是全球第四大智能手机制造商，在30余个国家和地区的手机市场进入了前五名，特别是在印度，连续5个季度保持手机出货量第一。通过独特的“生态链模式”，小米投资、带动了更多志同道合的创业者，同时建成了连接超过1.3亿台智能设备的IoT平台。&nbsp;</p><p>2018年7月9日，小米成功在香港主板上市，成为了港交所首个同股不同权上市公司，创造了香港史上最大规模科技股IPO，以及当时历史上全球第三大科技股IPO。&nbsp;&nbsp;</p><p>感谢您关注小米，和我们并肩投身于创造商业效率新典范，用科技改善人类生活的壮丽事业。许商业以敦厚，许科技以温暖，许大众以幸福，我们的征途是星辰大海，请和我们一起，永远相信美好的事情即将发生。</p>',1588562950,'感谢您关注小米，和我们并肩投身于创造商业效率新典范，用科技改善人类生活的壮丽事业。'),
	(4,'小米集团申请推迟召开发审委会议的公告','\\public\\upload/20200504\\125ff34e05f1bc9c821392c0c3252b7f.jpg','<p><span style=\"color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Microsoft Yahei&quot;, &quot;Hiragino Sans GB&quot;, &quot;Heiti SC&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 19px; background-color: rgb(255, 255, 255);\">公司经过反复慎重研究，决定分步实施在香港和境内的上市计划，即先在香港上市之后，再择机通过发行CDR的方式在境内上市。为此，公司将向中国证券监督管理委员会发起申请，推迟召开发审委会议审核公司的CDR发行申请。特此公告</span></p>',1588581991,'公司经过反复慎重研究，决定分步实施在香港和境内的上市计划，即先在香港上市之后，再择机通过发行CDR的方式在境内上市。'),
	(6,'简历','/public/upload/20210207/9f7b09b2ec6bcd4656688edfa5986b09.png','<p>简历简历简历</p>',1612672263,'简历');

/*!40000 ALTER TABLE `qing_news` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_notice
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_notice`;

CREATE TABLE `qing_notice` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为全体，1为某个',
  `title` varchar(100) NOT NULL,
  `content` text,
  `time` int NOT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='通知消息';

LOCK TABLES `qing_notice` WRITE;
/*!40000 ALTER TABLE `qing_notice` DISABLE KEYS */;

INSERT INTO `qing_notice` (`id`, `type`, `title`, `content`, `time`, `user_id`)
VALUES
	(1,0,'刘同学回复了你的商品分类与商品sku问题，可能有你需要的答案。','<p>刘同学回复了你的商品分类与商品sku问题，可能有你需要的答案。刘同学回复了你的商品分类与商品sku问题，可能有你需要的答案。</p>',1575511676,''),
	(2,0,'天下谁人不识君关注了你','天下谁人不识君关注了你天下谁人不识君关注了你',1575860919,''),
	(3,0,'一大把破优惠券来袭！','fff',1575860999,''),
	(4,0,'你参与的问题被回复了，可能有新收获？','<p><a target=\"_blank\" href=\"http://coding.imooc.com/learn/questiondetail/159290.html\" style=\"margin: 0px; padding: 0px 5px; outline: 0px; text-decoration-line: none; color: rgb(147, 153, 159); font-weight: 700; font-family: 微软雅黑; white-space: normal; background-color: rgb(255, 255, 255);\">你参与的问题被回复了，可能有新收获？</a></p>',1588646762,''),
	(5,1,'尊敬的用户，您有8张优惠券即将过期，机不可失，来选一门心仪的课程吧！','<p><a target=\"_blank\" href=\"https://order.imooc.com/coupon\" style=\"margin: 0px; padding: 0px 5px; outline: 0px; text-decoration-line: none; color: rgb(147, 153, 159); font-weight: 700; font-family: 微软雅黑; white-space: normal; background-color: rgb(255, 255, 255);\">尊敬的用户，您有8张优惠券即将过期，机不可失，来选一门心仪的课程吧！</a></p>',1588647018,'1,2,3,5'),
	(6,1,'你的状态筛选性能问题问题，可能有你需要的答案。','<p><a target=\"_blank\" href=\"http://coding.imooc.com/learn/questiondetail/167280.html\" style=\"margin: 0px; padding: 0px 5px; outline: 0px; text-decoration-line: none; color: rgb(147, 153, 159); font-weight: 700; font-family: 微软雅黑; white-space: normal; background-color: rgb(255, 255, 255);\">你的状态筛选性能问题问题，可能有你需要的答案。</a></p>',1588647789,'1'),
	(7,1,'你的状态筛选性能问题问题，可能有你需要的答案。','<p><a target=\"_blank\" href=\"http://coding.imooc.com/learn/questiondetail/167280.html\" style=\"margin: 0px; padding: 0px 5px; outline: 0px; text-decoration-line: none; color: rgb(147, 153, 159); font-weight: 700; font-family: 微软雅黑; white-space: normal; background-color: rgb(255, 255, 255);\">你的状态筛选性能问题问题，可能有你需要的答案。</a></p>',1588647789,'1,2,3,5'),
	(8,1,'您的优惠券马上过期了，请尽快使用','',1589938762,'1'),
	(9,1,'尊敬的用户，您有8张优惠券即将过期，机不可失，来选一门心仪的课程吧！','<p><a target=\"_blank\" href=\"https://order.imooc.com/coupon\" style=\"margin: 0px; padding: 0px 5px; outline: 0px; text-decoration-line: none; color: rgb(147, 153, 159); font-weight: 700; font-family: 微软雅黑; white-space: normal; background-color: rgb(255, 255, 255);\">尊敬的用户，您有8张优惠券即将过期，机不可失，来选一门心仪的课程吧！</a></p>',1588647018,'6'),
	(10,1,'你的状态筛选性能问题问题，可能有你需要的答案。','<p><a target=\"_blank\" href=\"http://coding.imooc.com/learn/questiondetail/167280.html\" style=\"margin: 0px; padding: 0px 5px; outline: 0px; text-decoration-line: none; color: rgb(147, 153, 159); font-weight: 700; font-family: 微软雅黑; white-space: normal; background-color: rgb(255, 255, 255);\">你的状态筛选性能问题问题，可能有你需要的答案。</a></p>',1588647789,'6'),
	(11,1,'你的状态筛选性能问题问题，可能有你需要的答案。','<p><a target=\"_blank\" href=\"http://coding.imooc.com/learn/questiondetail/167280.html\" style=\"margin: 0px; padding: 0px 5px; outline: 0px; text-decoration-line: none; color: rgb(147, 153, 159); font-weight: 700; font-family: 微软雅黑; white-space: normal; background-color: rgb(255, 255, 255);\">你的状态筛选性能问题问题，可能有你需要的答案。</a></p>',1588647789,'6'),
	(12,1,'您的优惠券马上过期了，请尽快使用','',1589938762,'6');

/*!40000 ALTER TABLE `qing_notice` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_notice_read
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_notice_read`;

CREATE TABLE `qing_notice_read` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `notice_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='通知消息已读表';

LOCK TABLES `qing_notice_read` WRITE;
/*!40000 ALTER TABLE `qing_notice_read` DISABLE KEYS */;

INSERT INTO `qing_notice_read` (`id`, `user_id`, `notice_id`)
VALUES
	(7,1,5),
	(6,1,2),
	(5,1,1),
	(8,1,6);

/*!40000 ALTER TABLE `qing_notice_read` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_order
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_order`;

CREATE TABLE `qing_order` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` mediumint unsigned NOT NULL COMMENT '会员id',
  `time` int unsigned NOT NULL COMMENT '下单时间',
  `address_id` varchar(30) NOT NULL COMMENT '收货信息',
  `content` varchar(50) DEFAULT NULL COMMENT '订单备注',
  `total_price` varchar(100) NOT NULL COMMENT '定单总价',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0：待付款，1：支付完成，待发货，2：已完成，4：已发货未签收',
  `pay_method` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1：微信支付，2：支付宝支付',
  `postcode` varchar(50) DEFAULT NULL COMMENT '快递单号',
  `express_code` varchar(10) DEFAULT NULL COMMENT '快递公司代号',
  `isfapiao` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1：已开发票 0：未开发票',
  `out_trade_no` varchar(100) NOT NULL COMMENT '订单号',
  `pay_time` int DEFAULT NULL COMMENT '支付时间',
  `iscomment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未评论 1已评论',
  PRIMARY KEY (`id`),
  UNIQUE KEY `out_trade_no` (`out_trade_no`),
  KEY `out_trade_no_2` (`out_trade_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='定单基本信息';

LOCK TABLES `qing_order` WRITE;
/*!40000 ALTER TABLE `qing_order` DISABLE KEYS */;

INSERT INTO `qing_order` (`id`, `user_id`, `time`, `address_id`, `content`, `total_price`, `status`, `pay_method`, `postcode`, `express_code`, `isfapiao`, `out_trade_no`, `pay_time`, `iscomment`)
VALUES
	(16,1,1600508714,'2','','0.02',2,1,'1','百世快运',0,'79d582a96a711d0cddcd05f87aa5261e',NULL,1),
	(18,1,1613472363,'3','','16.02',4,1,'SF1108389126359','顺丰快递',0,'10e333ff828b1a522de607e9512fc192',NULL,0);

/*!40000 ALTER TABLE `qing_order` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_order_goods
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_order_goods`;

CREATE TABLE `qing_order_goods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `goods_id` int NOT NULL,
  `amount` int NOT NULL,
  `goods_price` float NOT NULL,
  `sku` varchar(50) NOT NULL,
  `post_money` float NOT NULL DEFAULT '0' COMMENT '邮费',
  `iscomment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未评论 1已评论',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户订单商品表';

LOCK TABLES `qing_order_goods` WRITE;
/*!40000 ALTER TABLE `qing_order_goods` DISABLE KEYS */;

INSERT INTO `qing_order_goods` (`id`, `order_id`, `goods_id`, `amount`, `goods_price`, `sku`, `post_money`, `iscomment`)
VALUES
	(3,2,5,1,99,'',0,1),
	(4,3,6,2,1900,'64G,玫瑰粉,标准套餐',11,1),
	(5,4,4,1,5888,'70寸,灰色',0,1),
	(7,6,5,1,99,'',0,1),
	(8,6,1,1,1800,'64G,土豪金,标准套餐',0,1),
	(10,8,1,2,1600,'32G,土豪金,标准套餐',0,0),
	(11,8,6,1,2000,'128G,玫瑰粉,标准套餐',11,0),
	(13,10,4,1,5888,'70寸,灰色',0,1),
	(14,11,8,1,169,'',0,1),
	(15,12,3,1,1500,'64G,土豪金',0,1),
	(16,12,5,1,99,'',0,1),
	(17,13,7,1,99,'',0,1),
	(18,13,4,1,5888,'70寸,灰色',0,1),
	(19,14,8,1,0.02,'',0,0),
	(20,4,8,1,0.02,'',0,0),
	(21,16,8,1,0.02,'',0,1),
	(24,18,4,0,11,'1111',0,0);

/*!40000 ALTER TABLE `qing_order_goods` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_page
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_page`;

CREATE TABLE `qing_page` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='单页面';

LOCK TABLES `qing_page` WRITE;
/*!40000 ALTER TABLE `qing_page` DISABLE KEYS */;

INSERT INTO `qing_page` (`id`, `title`, `content`)
VALUES
	(1,'商品购买政策','<p>小米商城的部分商品，当您选择的收货区域无货时，可以点选到货通知。开启到货通知功能后，产品开售前，小米商城APP会分批以PUSH的形式通知您。设置成功后支持取消。</p>'),
	(2,'商品购买流程','<p><span style=\"color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Microsoft Yahei&quot;, &quot;Hiragino Sans GB&quot;, &quot;Heiti SC&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; background-color: rgb(251, 251, 251);\">根据国家税务总局公告2017年第16号第一条规定：自2017年7月1日起，购买方为企业的，索取增值税普通发票时，应向销售方提供纳税人识别号或统一社会信用代码；销售方为其开具增值税普通发票时，应在“购买方纳税人识 别号”栏填写购买方的纳税人识别号或统一社会信用代码。不符合规定的发票，不得作为税收凭证。纳税人识别号有两种方式获取：①联系公司财务咨询开票信息；②登录全国组织代码管理中心查询。</span></p>'),
	(3,'如何注册会员','<p><span style=\"color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Microsoft Yahei&quot;, &quot;Hiragino Sans GB&quot;, &quot;Heiti SC&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; background-color: rgb(251, 251, 251);\">开具电子发票的订单申请部分退货，原电子发票会通过系统自动冲红（原电子发票显示无效），并对未发生退货的商品重新开具电子发票。如整单退货，则我司将原电子发票做冲红处理（原电子发票显示无效）。</span></p>');

/*!40000 ALTER TABLE `qing_page` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_score
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_score`;

CREATE TABLE `qing_score` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `score` int NOT NULL,
  `time` int NOT NULL,
  `info` varchar(30) DEFAULT NULL,
  `source` int NOT NULL DEFAULT '1' COMMENT '1签到2推荐',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员积分';

LOCK TABLES `qing_score` WRITE;
/*!40000 ALTER TABLE `qing_score` DISABLE KEYS */;

INSERT INTO `qing_score` (`id`, `user_id`, `score`, `time`, `info`, `source`)
VALUES
	(29,6,300,1613364748,'小伙伴奖励',2),
	(28,4,10,1613364362,'签到赚取积分',1),
	(27,5,50,1613364315,'新用户奖励',2),
	(26,1,500,1613364315,'推荐返佣',2),
	(25,5,300,1613364315,'小伙伴奖励',2),
	(22,3,300,1613363429,'小伙伴奖励',2),
	(23,1,500,1613363429,'推荐返佣',2),
	(24,3,50,1613363429,'新用户奖励',2),
	(30,1,500,1613364748,'推荐返佣',2),
	(31,6,50,1613364748,'新用户奖励',2),
	(32,6,10,1613364776,'签到赚取积分',1),
	(33,1,10,1613378091,'签到赚取积分',1),
	(34,1,-1000,1613480072,'商品换购',1);

/*!40000 ALTER TABLE `qing_score` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_score_goods
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_score_goods`;

CREATE TABLE `qing_score_goods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(110) NOT NULL,
  `thumb` varchar(110) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `content` text,
  `num` int DEFAULT '0' COMMENT '库存',
  `time` int NOT NULL,
  `score` int NOT NULL,
  `listorder` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='积分商品表';

LOCK TABLES `qing_score_goods` WRITE;
/*!40000 ALTER TABLE `qing_score_goods` DISABLE KEYS */;

INSERT INTO `qing_score_goods` (`id`, `title`, `thumb`, `description`, `content`, `num`, `time`, `score`, `listorder`)
VALUES
	(1,'联通话费10元','/public/upload/20210207/f8b5cc2fdf4da52834c185dcb64bb622.jpg','','<p>联通话费10元，快来抢</p>',10,1545964839,1,4),
	(2,'黄睡衣','/public/upload/20210213/8e0a41a5a0c812b7ca5a34a396b41349.jpeg','','<p>黄睡衣</p>',10,1545968008,6800,2),
	(3,'蓝polo衫','/public/upload/20210213/73f06734ac9e94264745f131b8661833.jpeg',NULL,'<p>《创世学说——游戏系统设计指南》是腾讯游戏人十多年工作经验和总结的精华输出，涵盖了无数珍贵的实战案例、千锤百炼的经验总结，以及在用户研究、项目管理、测试迭代等方面的全方位的游戏开发心得和体会。系统设计是游戏设计领域的核心，与游戏的乐趣和核心玩法息息相关，只有通过设计优秀的游戏系统，才能诞生优秀的游戏。</p><p>在国内普遍缺乏优秀的游戏系统设计人才的情况下，《创世学说——游戏系统设计指南》对游戏行业系统策划新人的培养意义尤为重大。广大游戏爱好者可以通过阅读本书了解游戏是怎么制作出来的；有志于从事游戏设计事业的学生可以借助本书快速入门；在游戏行业中沉淀已久的专业人员更是可以把本书作为重要的工具书来使用。</p><p><br/></p>',10,1588664854,1000,0),
	(4,'旺旺T恤','/public/upload/20210213/ea93521db23265e276f5b710311bfa88.jpeg',NULL,'<p>《创世学说——游戏系统设计指南》是腾讯游戏人十多年工作经验和总结的精华输出，涵盖了无数珍贵的实战案例、千锤百炼的经验总结，以及在用户研究、项目管理、测试迭代等方面的全方位的游戏开发心得和体会。系统设计是游戏设计领域的核心，与游戏的乐趣和核心玩法息息相关，只有通过设计优秀的游戏系统，才能诞生优秀的游戏。</p><p>在国内普遍缺乏优秀的游戏系统设计人才的情况下，《创世学说——游戏系统设计指南》对游戏行业系统策划新人的培养意义尤为重大。广大游戏爱好者可以通过阅读本书了解游戏是怎么制作出来的；有志于从事游戏设计事业的学生可以借助本书快速入门；在游戏行业中沉淀已久的专业人员更是可以把本书作为重要的工具书来使用。</p><p><br/></p>',10,1588665216,1000,3);

/*!40000 ALTER TABLE `qing_score_goods` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_score_record
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_score_record`;

CREATE TABLE `qing_score_record` (
  `id` int NOT NULL AUTO_INCREMENT,
  `score` int NOT NULL,
  `goods_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `empress` varchar(100) DEFAULT NULL,
  `time` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='积分兑换记录';

LOCK TABLES `qing_score_record` WRITE;
/*!40000 ALTER TABLE `qing_score_record` DISABLE KEYS */;

INSERT INTO `qing_score_record` (`id`, `score`, `goods_id`, `status`, `empress`, `time`, `user_id`)
VALUES
	(9,1000,3,1,NULL,1613480072,1);

/*!40000 ALTER TABLE `qing_score_record` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_search
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_search`;

CREATE TABLE `qing_search` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='热门搜索词';

LOCK TABLES `qing_search` WRITE;
/*!40000 ALTER TABLE `qing_search` DISABLE KEYS */;

INSERT INTO `qing_search` (`id`, `name`)
VALUES
	(2,'羽绒服'),
	(3,'手机'),
	(4,'毛呢大衣'),
	(5,'电视'),
	(6,'小米');

/*!40000 ALTER TABLE `qing_search` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_standard
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_standard`;

CREATE TABLE `qing_standard` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '规格ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规格名称',
  `type_id` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品规格表';

LOCK TABLES `qing_standard` WRITE;
/*!40000 ALTER TABLE `qing_standard` DISABLE KEYS */;

INSERT INTO `qing_standard` (`id`, `name`, `type_id`)
VALUES
	(1,'内存',1),
	(2,'颜色',1),
	(3,'尺寸',2),
	(4,'尺寸',3),
	(5,'颜色',2),
	(6,'颜色',3),
	(7,'颜色',4),
	(8,'尺寸',5),
	(9,'手机套餐',1);

/*!40000 ALTER TABLE `qing_standard` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_standard_value
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_standard_value`;

CREATE TABLE `qing_standard_value` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '属性值ID',
  `standard_id` int unsigned NOT NULL DEFAULT '0' COMMENT '规格ID',
  `standard_value` varchar(255) NOT NULL DEFAULT '' COMMENT '属性值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='规格属性值表';

LOCK TABLES `qing_standard_value` WRITE;
/*!40000 ALTER TABLE `qing_standard_value` DISABLE KEYS */;

INSERT INTO `qing_standard_value` (`id`, `standard_id`, `standard_value`)
VALUES
	(1,1,'32G'),
	(2,1,'64G'),
	(3,1,'128G'),
	(4,1,'320G'),
	(5,2,'科技黑'),
	(6,2,'玫瑰粉'),
	(7,3,'L'),
	(8,3,'XL'),
	(9,3,'XXL'),
	(10,2,'土豪金'),
	(11,4,'70寸'),
	(12,4,'65寸'),
	(13,4,'55寸'),
	(14,5,'酒红色'),
	(15,5,'藏青色'),
	(16,6,'灰色'),
	(17,7,'黄色'),
	(18,7,'白色'),
	(22,2,'丹霞橙'),
	(23,9,'标准套餐'),
	(24,9,'豪华套餐'),
	(25,5,'卡其色'),
	(26,3,'M'),
	(27,4,'43寸'),
	(28,6,'黑色'),
	(29,3,'S'),
	(30,5,'黑色');

/*!40000 ALTER TABLE `qing_standard_value` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_type`;

CREATE TABLE `qing_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `qing_type` WRITE;
/*!40000 ALTER TABLE `qing_type` DISABLE KEYS */;

INSERT INTO `qing_type` (`id`, `type_name`)
VALUES
	(1,'手机'),
	(2,'女装'),
	(3,'电视'),
	(4,'插排'),
	(5,'床');

/*!40000 ALTER TABLE `qing_type` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_user`;

CREATE TABLE `qing_user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT 'qing',
  `password` char(32) NOT NULL DEFAULT '',
  `last_login_ip` varchar(20) NOT NULL DEFAULT '',
  `last_login_time` int unsigned DEFAULT NULL,
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '默认是1，不合格-1',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  `time` int unsigned NOT NULL DEFAULT '0',
  `forget_code` int DEFAULT NULL,
  `face` varchar(200) DEFAULT NULL,
  `access_token` int DEFAULT NULL,
  `openid` int DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `sex` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1男2女3保密',
  `birthday` int DEFAULT NULL COMMENT '生日',
  `xingzuo` varchar(100) DEFAULT NULL,
  `parent_id` int DEFAULT '0',
  `code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '推荐码',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `mobile` (`mobile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `qing_user` WRITE;
/*!40000 ALTER TABLE `qing_user` DISABLE KEYS */;

INSERT INTO `qing_user` (`id`, `username`, `password`, `last_login_ip`, `last_login_time`, `mobile`, `status`, `create_time`, `time`, `forget_code`, `face`, `access_token`, `openid`, `email`, `sex`, `birthday`, `xingzuo`, `parent_id`, `code`)
VALUES
	(1,'18810118687','0a96d9d3d6c8d6f554a6dc02e3d1c9b0','127.0.0.1',1613624763,'18810118687',1,0,1613276098,NULL,'\\public\\upload/20210214/c8fa4ef30d6b01478a88cd6b67df50d7.jpg',NULL,NULL,'bj_liunan@126.com',3,NULL,'摩羯座',0,'YJ1613276098'),
	(6,'18810118686','0a96d9d3d6c8d6f554a6dc02e3d1c9b0','127.0.0.1',1613364773,'18810118686',1,0,1613364748,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,1,'YJ1613364748');

/*!40000 ALTER TABLE `qing_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table qing_user_trace
# ------------------------------------------------------------

DROP TABLE IF EXISTS `qing_user_trace`;

CREATE TABLE `qing_user_trace` (
  `id` int NOT NULL AUTO_INCREMENT,
  `goods_id` int NOT NULL,
  `user_id` int NOT NULL,
  `time` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户足迹表';

LOCK TABLES `qing_user_trace` WRITE;
/*!40000 ALTER TABLE `qing_user_trace` DISABLE KEYS */;

INSERT INTO `qing_user_trace` (`id`, `goods_id`, `user_id`, `time`)
VALUES
	(34,2,1,1613400884),
	(35,3,1,1613621208),
	(36,9,1,1613480553),
	(37,13,1,1613483289),
	(38,6,1,1613570304);

/*!40000 ALTER TABLE `qing_user_trace` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
