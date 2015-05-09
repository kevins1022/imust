/*
Navicat MySQL Data Transfer

Source Server         : 本机
Source Server Version : 50528
Source Host           : localhost:3306
Source Database       : schoolcms

Target Server Type    : MYSQL
Target Server Version : 50528
File Encoding         : 65001

Date: 2014-01-14 21:29:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `schoolcms_achievement`
-- ----------------------------
DROP TABLE IF EXISTS `schoolcms_achievement`;
CREATE TABLE `schoolcms_achievement` (
  `atid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '成绩表id',
  `cmid` int(10) unsigned NOT NULL COMMENT '科目id',
  `sid` int(10) unsigned NOT NULL COMMENT '学生id',
  `atcid` int(10) unsigned NOT NULL COMMENT '成绩分类表id',
  `srid` int(10) unsigned NOT NULL COMMENT '学期id',
  `atfraction` int(3) unsigned NOT NULL COMMENT '分数',
  `atstatu` int(1) unsigned DEFAULT '1' COMMENT '状态（1：正常，0：不可用）',
  `atscoretype` int(1) unsigned DEFAULT '6' COMMENT '分数类型（1:差，2：较差，3：中，4：良，5：优，6：未知）',
  PRIMARY KEY (`atid`),
  KEY `cmid` (`cmid`),
  KEY `sid` (`sid`),
  KEY `atcid` (`atcid`),
  KEY `srid` (`srid`),
  CONSTRAINT `schoolcms_achievement_ibfk_1` FOREIGN KEY (`cmid`) REFERENCES `schoolcms_curriculum` (`cmid`),
  CONSTRAINT `schoolcms_achievement_ibfk_2` FOREIGN KEY (`sid`) REFERENCES `schoolcms_student` (`sid`),
  CONSTRAINT `schoolcms_achievement_ibfk_3` FOREIGN KEY (`atcid`) REFERENCES `schoolcms_achievementclass` (`atcid`),
  CONSTRAINT `schoolcms_achievement_ibfk_4` FOREIGN KEY (`srid`) REFERENCES `schoolcms_semester` (`srid`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='成绩表';

-- ----------------------------
-- Records of schoolcms_achievement
-- ----------------------------
INSERT INTO `schoolcms_achievement` VALUES ('3', '2', '4', '2', '1', '66', '1', '4');
INSERT INTO `schoolcms_achievement` VALUES ('4', '2', '6', '2', '1', '55', '1', '3');
INSERT INTO `schoolcms_achievement` VALUES ('10', '1', '19', '1', '1', '23', '1', '2');
INSERT INTO `schoolcms_achievement` VALUES ('11', '1', '25', '2', '1', '65', '1', '4');
INSERT INTO `schoolcms_achievement` VALUES ('13', '2', '22', '1', '1', '78', '1', '4');
INSERT INTO `schoolcms_achievement` VALUES ('14', '1', '20', '1', '1', '77', '1', '4');
INSERT INTO `schoolcms_achievement` VALUES ('15', '2', '12', '1', '1', '88', '1', '5');
INSERT INTO `schoolcms_achievement` VALUES ('16', '2', '30', '1', '1', '99', '1', '5');
INSERT INTO `schoolcms_achievement` VALUES ('17', '1', '18', '1', '1', '87', '1', '5');
INSERT INTO `schoolcms_achievement` VALUES ('19', '2', '25', '1', '1', '43', '1', '3');
INSERT INTO `schoolcms_achievement` VALUES ('20', '1', '2', '1', '1', '76', '1', '4');
INSERT INTO `schoolcms_achievement` VALUES ('22', '2', '31', '1', '1', '99', '1', '5');
INSERT INTO `schoolcms_achievement` VALUES ('23', '1', '45', '1', '1', '66', '1', '4');
INSERT INTO `schoolcms_achievement` VALUES ('24', '2', '23', '1', '1', '98', '1', '5');

-- ----------------------------
-- Table structure for `schoolcms_achievementclass`
-- ----------------------------
DROP TABLE IF EXISTS `schoolcms_achievementclass`;
CREATE TABLE `schoolcms_achievementclass` (
  `atcid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '成绩分类表id',
  `atcname` char(28) NOT NULL COMMENT '成绩分类名称',
  `atcstatu` int(1) unsigned DEFAULT '1' COMMENT '状态（1：正常，0：不可用）',
  `atcsort` int(6) unsigned DEFAULT '0' COMMENT '显示顺序',
  PRIMARY KEY (`atcid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='成绩分类表';

-- ----------------------------
-- Records of schoolcms_achievementclass
-- ----------------------------
INSERT INTO `schoolcms_achievementclass` VALUES ('1', '第一期', '1', '4');
INSERT INTO `schoolcms_achievementclass` VALUES ('2', '第二期', '1', '0');

-- ----------------------------
-- Table structure for `schoolcms_admin`
-- ----------------------------
DROP TABLE IF EXISTS `schoolcms_admin`;
CREATE TABLE `schoolcms_admin` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `aname` char(28) NOT NULL COMMENT '管理员名称',
  `apwd` char(32) NOT NULL COMMENT '管理员密码',
  `amobile` char(11) DEFAULT NULL COMMENT '管理员手机',
  `amail` char(68) DEFAULT NULL COMMENT '管理员电子邮箱',
  `anick` char(28) DEFAULT NULL COMMENT '管理员昵称',
  `afullname` char(38) DEFAULT NULL COMMENT '管理员姓名',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of schoolcms_admin
-- ----------------------------
INSERT INTO `schoolcms_admin` VALUES ('1', 'admin', 'f6fdffe48c908deb0f4c3bd36c032e72', '13263176222', '386392432@qq.come', '嘻嘻嘻', '小龚2');
INSERT INTO `schoolcms_admin` VALUES ('2', 'root', 'b4b8daf4b8ea9d39568719e1e320076f', '13233221123', 'gfx@kjt.com', 'root', '小龙龙1');
INSERT INTO `schoolcms_admin` VALUES ('6', 'fff', 'eed8cdc400dfd4ec85dff70a170066b7', '13222333322', 'ww@ww.com', 'fff', 'fff');
INSERT INTO `schoolcms_admin` VALUES ('5', 'ssssss', 'af15d5fdacd5fdfea300e88a8e253e82', '13233222233', 'ww@ww.me', 'sss', 'sss');

-- ----------------------------
-- Table structure for `schoolcms_basicsetup`
-- ----------------------------
DROP TABLE IF EXISTS `schoolcms_basicsetup`;
CREATE TABLE `schoolcms_basicsetup` (
  `bid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '基本设置id',
  `bmark` char(28) DEFAULT NULL COMMENT '使用标识符（可以多个相同）',
  `bdatavalue` char(60) DEFAULT NULL COMMENT '使用的值',
  `bname` char(38) DEFAULT NULL COMMENT '使用名称',
  `bdescription` char(100) DEFAULT NULL COMMENT '详细描述',
  `btype` char(100) DEFAULT NULL COMMENT '类型',
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='基本设置参数表';

-- ----------------------------
-- Records of schoolcms_basicsetup
-- ----------------------------
INSERT INTO `schoolcms_basicsetup` VALUES ('1', 'page', '9', '学员管理分页数据条数：', '设置每页显示数据的条数', 'studentpage');
INSERT INTO `schoolcms_basicsetup` VALUES ('2', 'page', '9', '学员成绩管理分页：', '设置每页显示数据的条数', 'achievementpage');
INSERT INTO `schoolcms_basicsetup` VALUES ('3', 'achievement', '20', '差最大值', null, 'poor');
INSERT INTO `schoolcms_basicsetup` VALUES ('4', 'achievement', '40', '较差最大值', null, 'cypoor');
INSERT INTO `schoolcms_basicsetup` VALUES ('5', 'achievement', '60', '中最大值', null, 'medium');
INSERT INTO `schoolcms_basicsetup` VALUES ('6', 'achievement', '80', '良最大值', null, 'good');
INSERT INTO `schoolcms_basicsetup` VALUES ('7', 'achievement', '100', '优最大值', null, 'excellent');
INSERT INTO `schoolcms_basicsetup` VALUES ('8', 'semester', '1', '当前学期类id', null, 'semester');
INSERT INTO `schoolcms_basicsetup` VALUES ('9', 'page', '9', '教师管理分页数据条数：', '设置每页显示数据的条数', 'teacherpage');
INSERT INTO `schoolcms_basicsetup` VALUES ('10', 'page', '9', '教师课程安排管理：', '设置每页显示数据的条数', 'teachercurriculumpage');
INSERT INTO `schoolcms_basicsetup` VALUES ('11', 'csv', '1', 'csv编码选择：', 'csv模块编码选择', 'modelcsv');
INSERT INTO `schoolcms_basicsetup` VALUES ('12', 'page', '3', '后台分页参数设置：', '后台分页显示设置', 'modelpage');
INSERT INTO `schoolcms_basicsetup` VALUES ('13', 'page', '9', '财务管理分页数据条数：', '设置每页显示数据的条数', 'financepage');
INSERT INTO `schoolcms_basicsetup` VALUES ('14', 'page', '9', '财务明细管理分页数据条数：', '设置每页显示数据的条数', 'financialdetailspage');

-- ----------------------------
-- Table structure for `schoolcms_class`
-- ----------------------------
DROP TABLE IF EXISTS `schoolcms_class`;
CREATE TABLE `schoolcms_class` (
  `cid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '班级id',
  `cname` char(28) NOT NULL COMMENT '班级名称',
  `csort` int(6) unsigned DEFAULT '0' COMMENT '显示顺序',
  `cstatu` int(1) unsigned DEFAULT '1' COMMENT '状态（1：正常，0：不可用）',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='班级表';

-- ----------------------------
-- Records of schoolcms_class
-- ----------------------------
INSERT INTO `schoolcms_class` VALUES ('1', '一年级', '0', '1');
INSERT INTO `schoolcms_class` VALUES ('2', '二年级', '0', '1');
INSERT INTO `schoolcms_class` VALUES ('3', '三年级', '0', '1');
INSERT INTO `schoolcms_class` VALUES ('4', '四年级', '0', '1');
INSERT INTO `schoolcms_class` VALUES ('5', '五年级', '0', '1');
INSERT INTO `schoolcms_class` VALUES ('6', '六年级', '0', '1');
INSERT INTO `schoolcms_class` VALUES ('7', '学前班', '0', '1');

-- ----------------------------
-- Table structure for `schoolcms_curriculum`
-- ----------------------------
DROP TABLE IF EXISTS `schoolcms_curriculum`;
CREATE TABLE `schoolcms_curriculum` (
  `cmid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '科目id',
  `cmname` char(28) NOT NULL COMMENT '科目名称',
  `cmstatu` int(1) unsigned DEFAULT '1' COMMENT '状态（1：正常，0：不可用）',
  `cmsort` int(6) unsigned DEFAULT '0' COMMENT '显示顺序',
  PRIMARY KEY (`cmid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='科目表';

-- ----------------------------
-- Records of schoolcms_curriculum
-- ----------------------------
INSERT INTO `schoolcms_curriculum` VALUES ('1', '语文', '1', '0');
INSERT INTO `schoolcms_curriculum` VALUES ('2', '数学', '1', '0');

-- ----------------------------
-- Table structure for `schoolcms_payment`
-- ----------------------------
DROP TABLE IF EXISTS `schoolcms_payment`;
CREATE TABLE `schoolcms_payment` (
  `ptid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '缴费id',
  `sid` int(10) unsigned NOT NULL COMMENT '学员id',
  `ptmoney` float(6,2) NOT NULL COMMENT '缴费金额',
  `ptremarks` char(255) DEFAULT NULL COMMENT '缴费备注',
  `ptupdate` datetime DEFAULT NULL COMMENT '更新时间',
  `ptdate` datetime DEFAULT NULL COMMENT '缴费时间',
  PRIMARY KEY (`ptid`),
  KEY `sid` (`sid`),
  CONSTRAINT `schoolcms_payment_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `schoolcms_student` (`sid`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COMMENT='缴费表';

-- ----------------------------
-- Records of schoolcms_payment
-- ----------------------------
INSERT INTO `schoolcms_payment` VALUES ('11', '1', '0.00', '', '2014-01-12 17:59:01', '2013-12-09 02:58:41');
INSERT INTO `schoolcms_payment` VALUES ('12', '2', '0.00', '', '2014-01-12 18:02:37', '2013-12-14 19:26:20');
INSERT INTO `schoolcms_payment` VALUES ('13', '4', '0.00', '', '2014-01-12 18:02:57', '2013-12-15 20:01:22');
INSERT INTO `schoolcms_payment` VALUES ('14', '6', '0.00', '', '2014-01-12 18:07:19', '2013-12-15 20:06:02');
INSERT INTO `schoolcms_payment` VALUES ('15', '9', '56.00', '放入yt', '2014-01-14 20:08:48', '2013-12-20 20:32:19');
INSERT INTO `schoolcms_payment` VALUES ('16', '10', '54.00', '增加了元', '2014-01-14 20:10:59', '2013-12-20 21:04:17');
INSERT INTO `schoolcms_payment` VALUES ('17', '11', '788.00', '交学费', '2014-01-14 21:04:15', '2013-12-20 21:06:55');
INSERT INTO `schoolcms_payment` VALUES ('18', '12', '0.00', null, null, '2013-12-20 21:08:24');
INSERT INTO `schoolcms_payment` VALUES ('19', '13', '0.00', null, null, '2013-12-20 21:10:16');
INSERT INTO `schoolcms_payment` VALUES ('20', '14', '0.00', null, null, '2013-12-21 01:39:06');
INSERT INTO `schoolcms_payment` VALUES ('21', '15', '0.00', null, null, '2013-12-21 01:51:01');
INSERT INTO `schoolcms_payment` VALUES ('22', '16', '0.00', null, null, '2013-12-21 01:52:18');
INSERT INTO `schoolcms_payment` VALUES ('23', '17', '0.00', null, null, '2013-12-21 01:53:56');
INSERT INTO `schoolcms_payment` VALUES ('24', '18', '0.00', null, null, '2013-12-21 02:44:46');
INSERT INTO `schoolcms_payment` VALUES ('25', '19', '0.00', null, null, '2013-12-21 02:53:09');
INSERT INTO `schoolcms_payment` VALUES ('26', '20', '0.00', null, null, '2013-12-21 12:12:53');
INSERT INTO `schoolcms_payment` VALUES ('27', '21', '0.00', null, null, '2013-12-21 12:14:39');
INSERT INTO `schoolcms_payment` VALUES ('28', '22', '0.00', null, null, '2013-12-21 12:15:43');
INSERT INTO `schoolcms_payment` VALUES ('29', '23', '23.00', '一天天测试减少', '2014-01-14 21:02:30', '2013-12-21 12:16:51');
INSERT INTO `schoolcms_payment` VALUES ('30', '24', '0.00', null, null, '2013-12-21 12:17:48');
INSERT INTO `schoolcms_payment` VALUES ('31', '25', '0.00', null, null, '2013-12-21 12:20:14');
INSERT INTO `schoolcms_payment` VALUES ('32', '26', '0.00', null, null, '2013-12-21 12:21:50');
INSERT INTO `schoolcms_payment` VALUES ('33', '27', '0.00', null, null, '2013-12-21 12:22:33');
INSERT INTO `schoolcms_payment` VALUES ('34', '29', '0.00', null, null, '2013-12-21 12:24:12');
INSERT INTO `schoolcms_payment` VALUES ('35', '30', '0.00', null, null, '2013-12-21 12:25:48');
INSERT INTO `schoolcms_payment` VALUES ('36', '31', '0.00', null, null, '2013-12-24 23:40:23');
INSERT INTO `schoolcms_payment` VALUES ('37', '45', '0.00', null, null, '2013-12-26 02:00:45');
INSERT INTO `schoolcms_payment` VALUES ('40', '50', '35.00', '沟通', '2014-01-14 00:01:43', '2014-01-12 12:07:40');
INSERT INTO `schoolcms_payment` VALUES ('41', '53', '0.00', null, null, '2014-01-12 16:42:28');
INSERT INTO `schoolcms_payment` VALUES ('42', '54', '0.00', '', '2014-01-13 15:10:30', '2014-01-13 15:10:30');
INSERT INTO `schoolcms_payment` VALUES ('43', '55', '32.00', '缴费了', '2014-01-13 23:46:41', '2014-01-13 15:11:31');

-- ----------------------------
-- Table structure for `schoolcms_paymentmodifylog`
-- ----------------------------
DROP TABLE IF EXISTS `schoolcms_paymentmodifylog`;
CREATE TABLE `schoolcms_paymentmodifylog` (
  `pgid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '学员缴费日志表id',
  `aid` int(10) unsigned NOT NULL COMMENT '操作人id',
  `aname` char(10) NOT NULL COMMENT '管理员名称',
  `sid` int(10) unsigned NOT NULL COMMENT '学员id',
  `sname` char(38) NOT NULL COMMENT '学员姓名',
  `pgoriginalmoney` float(6,0) NOT NULL COMMENT '原始金额',
  `pgupdatemoney` float(6,0) DEFAULT NULL COMMENT '变更后金额',
  `pgupdatetype` char(255) DEFAULT NULL COMMENT '变更类型',
  `pgoperatingdate` datetime NOT NULL COMMENT '操作时间',
  `pgoperatingtype` char(38) DEFAULT '创建' COMMENT '操作类型（创建，更新）',
  `pgremark` char(255) DEFAULT NULL COMMENT '操作备注',
  PRIMARY KEY (`pgid`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='学员缴费金额变更记录表';

-- ----------------------------
-- Records of schoolcms_paymentmodifylog
-- ----------------------------
INSERT INTO `schoolcms_paymentmodifylog` VALUES ('1', '1', 'admin', '23', '冉小飞', '65', '65', '金额未变更', '2014-01-13 23:32:11', '更新', '纷纷扰扰');
INSERT INTO `schoolcms_paymentmodifylog` VALUES ('2', '1', 'admin', '10', '龚嘘嘘', '56', '56', '金额未变更', '2014-01-13 23:32:36', '更新', '也一样');
INSERT INTO `schoolcms_paymentmodifylog` VALUES ('3', '1', 'admin', '10', '龚嘘嘘', '88', '88', '金额未变更', '2014-01-13 23:33:14', '更新', '增加了');
INSERT INTO `schoolcms_paymentmodifylog` VALUES ('4', '1', 'admin', '9', '龚菲菲', '566', '566', '金额未变更', '2014-01-13 23:35:08', '更新', '公分');
INSERT INTO `schoolcms_paymentmodifylog` VALUES ('5', '1', 'admin', '9', '龚菲菲', '566', '43', '减少<span style=\"font-size:14px;color:#F05648;font-weight:700;\"> 523 </span>元', '2014-01-13 23:40:23', '更新', '放入');
INSERT INTO `schoolcms_paymentmodifylog` VALUES ('6', '1', 'admin', '10', '龚嘘嘘', '88', '453', '增加<span style=\"font-size:14px;color:#F05648;font-weight:700;\"> 365 </span>元', '2014-01-13 23:40:49', '更新', '增加了元');
INSERT INTO `schoolcms_paymentmodifylog` VALUES ('7', '1', 'admin', '50', '滚滚滔滔', '432', '35', '减少<span style=\"font-size:14px;color:#F05648;font-weight:700;\"> 397 </span>元', '2014-01-13 23:46:08', '更新', '沟通');
INSERT INTO `schoolcms_paymentmodifylog` VALUES ('8', '1', 'admin', '55', '测试缴费学生', '32', '32', '金额未变更', '2014-01-13 23:46:25', '更新', '缴费了');
INSERT INTO `schoolcms_paymentmodifylog` VALUES ('9', '1', 'admin', '55', '测试缴费学生', '32', '32', '金额未变更', '2014-01-13 23:46:42', '更新', '缴费了');
INSERT INTO `schoolcms_paymentmodifylog` VALUES ('10', '1', 'admin', '50', '滚滚滔滔', '35', '35', '金额未变更', '2014-01-13 23:53:35', '更新', '沟通');
INSERT INTO `schoolcms_paymentmodifylog` VALUES ('11', '1', 'admin', '23', '冉小飞', '65', '65', '金额未变更', '2014-01-14 00:01:37', '更新', '纷纷扰扰');
INSERT INTO `schoolcms_paymentmodifylog` VALUES ('12', '1', 'admin', '50', '滚滚滔滔', '35', '35', '金额未变更', '2014-01-14 00:01:44', '更新', '沟通');
INSERT INTO `schoolcms_paymentmodifylog` VALUES ('16', '1', 'admin', '11', '小拉拉', '0', '788', '增加<span style=\"font-size:14px;color:#F05648;font-weight:700;\"> 788 </span>元', '2014-01-14 21:04:16', '更新', '交学费');
INSERT INTO `schoolcms_paymentmodifylog` VALUES ('15', '1', 'admin', '23', '冉小飞', '65', '23', '减少<span style=\"font-size:14px;color:#F05648;font-weight:700;\"> 42 </span>元', '2014-01-14 21:02:31', '更新', '一天天测试减少');

-- ----------------------------
-- Table structure for `schoolcms_semester`
-- ----------------------------
DROP TABLE IF EXISTS `schoolcms_semester`;
CREATE TABLE `schoolcms_semester` (
  `srid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '学期id',
  `srname` char(38) NOT NULL COMMENT '学期名称',
  `srsort` int(6) unsigned DEFAULT '0' COMMENT '显示顺序',
  `srstatu` int(1) unsigned DEFAULT '1' COMMENT '状态（1：正常，0：不可用）',
  PRIMARY KEY (`srid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='学期表';

-- ----------------------------
-- Records of schoolcms_semester
-- ----------------------------
INSERT INTO `schoolcms_semester` VALUES ('1', '2013上学期', '0', '1');
INSERT INTO `schoolcms_semester` VALUES ('2', '2013下学期', '0', '1');

-- ----------------------------
-- Table structure for `schoolcms_student`
-- ----------------------------
DROP TABLE IF EXISTS `schoolcms_student`;
CREATE TABLE `schoolcms_student` (
  `sid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '学生id',
  `ptid` int(10) unsigned NOT NULL COMMENT '缴费id',
  `cid` int(10) unsigned NOT NULL COMMENT '班级id',
  `cyid` int(10) unsigned NOT NULL COMMENT '所属地区id',
  `srid` int(10) unsigned NOT NULL COMMENT '学期id',
  `sname` char(38) NOT NULL COMMENT '学生姓名',
  `sbirthdate` int(10) unsigned DEFAULT NULL COMMENT '出生日期',
  `ssex` int(1) unsigned DEFAULT '1' COMMENT '学生性别（1：保密，2：女，3：男）',
  `ssreatedate` datetime DEFAULT NULL COMMENT '创建日期时间',
  `stuitionstatu` int(1) unsigned DEFAULT '1' COMMENT '缴费状态（1:未缴费，2:已缴费）',
  `seffectivetime` int(10) unsigned DEFAULT NULL COMMENT '生效时间',
  `stheendtime` int(10) unsigned DEFAULT NULL COMMENT '终止时间',
  `smobile` char(11) DEFAULT NULL COMMENT '联系手机',
  `shomephone` char(16) DEFAULT NULL COMMENT '家庭电话',
  PRIMARY KEY (`sid`),
  KEY `cid` (`cid`),
  KEY `cyid` (`cyid`),
  KEY `srid` (`srid`),
  CONSTRAINT `schoolcms_student_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `schoolcms_class` (`cid`),
  CONSTRAINT `schoolcms_student_ibfk_2` FOREIGN KEY (`cyid`) REFERENCES `schoolcms_studentclassify` (`cyid`),
  CONSTRAINT `schoolcms_student_ibfk_3` FOREIGN KEY (`srid`) REFERENCES `schoolcms_semester` (`srid`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8 COMMENT='学生表';

-- ----------------------------
-- Records of schoolcms_student
-- ----------------------------
INSERT INTO `schoolcms_student` VALUES ('1', '0', '6', '1', '1', '龚福祥', '885484800', '3', '2013-12-09 02:58:41', '1', '1387468800', '1418832000', '13222336662', '021-33221111');
INSERT INTO `schoolcms_student` VALUES ('2', '0', '2', '3', '1', '小露', '738691332', '2', '2013-12-14 19:26:20', '1', '1387555200', '1407859200', '13122111111', '');
INSERT INTO `schoolcms_student` VALUES ('4', '0', '6', '1', '1', '龚福刚', '937843200', '3', '2013-12-15 20:01:22', '1', '1387555200', '1402329600', '13655332266', '');
INSERT INTO `schoolcms_student` VALUES ('6', '0', '3', '3', '1', '龚辉', '968169221', '3', '2013-12-15 20:06:02', '1', '1387555200', '1388419200', '13433222233', '');
INSERT INTO `schoolcms_student` VALUES ('9', '0', '3', '1', '1', '龚菲菲', '822326400', '1', '2013-12-20 20:32:19', '2', '1387555200', '1417449600', '13233444422', '0856-33221166');
INSERT INTO `schoolcms_student` VALUES ('10', '0', '4', '1', '1', '龚嘘嘘', '1011024000', '3', '2013-12-20 21:04:17', '2', '1387814400', '1408464000', '13555444433', '0856-99888877');
INSERT INTO `schoolcms_student` VALUES ('11', '0', '3', '1', '1', '小拉拉', '738691200', '2', '2013-12-20 21:06:55', '2', '1387641600', '1388332800', '13455444444', '0573-33222244');
INSERT INTO `schoolcms_student` VALUES ('12', '0', '4', '2', '1', '小努努', '781804800', '2', '2013-12-20 21:08:24', '1', '1387555200', '1413302400', '13566555566', '0571-3322211');
INSERT INTO `schoolcms_student` VALUES ('13', '0', '6', '2', '0', '大努努', '781804834', '2', '2013-12-20 21:10:16', '1', '1387814400', '1394467200', '13766555555', '021-22333322');
INSERT INTO `schoolcms_student` VALUES ('14', '0', '5', '2', '1', '嘻嘻嘻', '781804223', '1', '2013-12-21 01:39:06', '1', '1387555200', '1417449600', '13233222222', '021-22334422');
INSERT INTO `schoolcms_student` VALUES ('15', '0', '1', '3', '1', '周咪咪', '968169988', '2', '2013-12-21 01:51:01', '1', '1387468800', '1418659200', '13233222222', '0856-33223333');
INSERT INTO `schoolcms_student` VALUES ('16', '0', '4', '3', '1', '周步步', '968164466', '3', '2013-12-21 01:52:18', '1', '1387468800', '1418140800', '13544554444', '0856-99998888');
INSERT INTO `schoolcms_student` VALUES ('17', '0', '6', '4', '1', '李亚莉', '968169445', '2', '2013-12-21 01:53:56', '1', '1387382400', '1418832000', '13211222222', '0856-33221111');
INSERT INTO `schoolcms_student` VALUES ('18', '0', '2', '4', '1', '李美美', '968169888', '2', '2013-12-21 02:44:46', '1', '1387555200', '1402329600', '13766776666', '0856-44335555');
INSERT INTO `schoolcms_student` VALUES ('19', '0', '6', '1', '1', '刘小米', '831916800', '2', '2013-12-21 02:53:09', '1', '1387555200', '1417536000', '13233111111', '0676-33222222');
INSERT INTO `schoolcms_student` VALUES ('20', '0', '6', '4', '1', '李笑笑', '968169667', '2', '2013-12-21 12:12:53', '1', '1387555200', '1418745600', '13233111222', '0856-33999999');
INSERT INTO `schoolcms_student` VALUES ('21', '0', '3', '4', '1', '李次次', '968169665', '1', '2013-12-21 12:14:39', '1', '1387555200', '1410192000', '13455443322', '0856-33999988');
INSERT INTO `schoolcms_student` VALUES ('22', '0', '1', '2', '1', '刘飞卢', '758908800', '3', '2013-12-21 12:15:43', '1', '1387555200', '1410192000', '13455443344', '0856-33223322');
INSERT INTO `schoolcms_student` VALUES ('23', '0', '2', '1', '1', '冉小飞', '1260288000', '2', '2013-12-21 12:16:51', '2', '1387555200', '1411228800', '13111222211', '0856-99887766');
INSERT INTO `schoolcms_student` VALUES ('24', '0', '5', '1', '1', '冉小东', '1011628800', '3', '2013-12-21 12:17:48', '1', '1387555200', '1413302400', '13222333322', '0856-33223322');
INSERT INTO `schoolcms_student` VALUES ('25', '0', '3', '2', '1', '刘亦菲', '1046620800', '2', '2013-12-21 12:20:14', '1', '1387555200', '1413302400', '13211222222', '0856-99888899');
INSERT INTO `schoolcms_student` VALUES ('26', '0', '6', '4', '1', '李学福', '987436800', '3', '2013-12-21 12:21:50', '1', '1387555200', '1402934400', '13222115555', '0856-33222222');
INSERT INTO `schoolcms_student` VALUES ('27', '0', '2', '4', '1', '李东东', '968163344', '3', '2013-12-21 12:22:33', '1', '1387555200', '1412611200', '13455555544', '0856-33332222');
INSERT INTO `schoolcms_student` VALUES ('29', '0', '6', '2', '1', '刘文华', '968162233', '3', '2013-12-21 12:24:12', '1', '1387555200', '1418745600', '13555666666', '0856-33222222');
INSERT INTO `schoolcms_student` VALUES ('30', '0', '5', '2', '1', '刘文娟', '968169443', '2', '2013-12-21 12:25:48', '1', '1387555200', '1418227200', '', '0856-88333333');
INSERT INTO `schoolcms_student` VALUES ('31', '0', '3', '1', '1', '小嘻嘻', '968169600', '2', '2013-12-24 23:40:23', '1', '1387814400', '1418227200', '13221111111', '0856-99999999');
INSERT INTO `schoolcms_student` VALUES ('45', '0', '1', '2', '1', '刘福福', '1166630400', '2', '2013-12-26 02:00:45', '1', '1387987200', '1393862400', '13566666677', '');
INSERT INTO `schoolcms_student` VALUES ('50', '0', '1', '1', '1', '滚滚滔滔', '1389196800', '1', '2014-01-12 12:07:40', '2', '1389715200', '1453305600', '13455555555', '');
INSERT INTO `schoolcms_student` VALUES ('53', '0', '4', '1', '1', '测试学员', '1230652800', '2', '2014-01-12 16:42:28', '1', '1389456000', '1421164800', '13222333333', '');
INSERT INTO `schoolcms_student` VALUES ('54', '0', '2', '1', '1', '测试可爱学生', '726249600', '2', '2014-01-13 15:10:30', '1', '1389542400', '1420560000', '13666555566', '021-66553322');
INSERT INTO `schoolcms_student` VALUES ('55', '0', '4', '1', '1', '测试缴费学生', '1011110400', '1', '2014-01-13 15:11:31', '2', '1389542400', '1420560000', '13222334444', '023-22333333');

-- ----------------------------
-- Table structure for `schoolcms_studentclassify`
-- ----------------------------
DROP TABLE IF EXISTS `schoolcms_studentclassify`;
CREATE TABLE `schoolcms_studentclassify` (
  `cyid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `cypid` char(255) DEFAULT '-0-' COMMENT '分类的父级id',
  `cyname` char(16) NOT NULL COMMENT '分类名称',
  `cystatu` int(1) unsigned DEFAULT '1' COMMENT '状态（1：正常，0：不可用）',
  `cysort` int(6) unsigned DEFAULT '0' COMMENT '显示顺序',
  PRIMARY KEY (`cyid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='地区表';

-- ----------------------------
-- Records of schoolcms_studentclassify
-- ----------------------------
INSERT INTO `schoolcms_studentclassify` VALUES ('1', '-0-', '康家坨', '1', '0');
INSERT INTO `schoolcms_studentclassify` VALUES ('2', '-0-', '麻池', '1', '0');
INSERT INTO `schoolcms_studentclassify` VALUES ('3', '-0-', '马家山', '1', '0');
INSERT INTO `schoolcms_studentclassify` VALUES ('4', '-0-', '并胆丫', '1', '0');
INSERT INTO `schoolcms_studentclassify` VALUES ('5', '-0--2-', '古古怪怪', '1', '0');
INSERT INTO `schoolcms_studentclassify` VALUES ('6', '-0--2-', '对方答复', '1', '0');

-- ----------------------------
-- Table structure for `schoolcms_tcwct`
-- ----------------------------
DROP TABLE IF EXISTS `schoolcms_tcwct`;
CREATE TABLE `schoolcms_tcwct` (
  `ttid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '课程安排id',
  `tid` int(10) unsigned NOT NULL COMMENT '教师id',
  `cmid` int(10) unsigned NOT NULL COMMENT '科目id',
  `wid` int(10) unsigned NOT NULL COMMENT '周id',
  `cid` int(10) unsigned NOT NULL COMMENT '班级id',
  `teid` int(10) unsigned NOT NULL COMMENT '时段id',
  PRIMARY KEY (`ttid`),
  KEY `tid` (`tid`),
  KEY `cmid` (`cmid`),
  KEY `wid` (`wid`),
  KEY `cid` (`cid`),
  KEY `teid` (`teid`),
  CONSTRAINT `schoolcms_tcwct_ibfk_1` FOREIGN KEY (`tid`) REFERENCES `schoolcms_teacher` (`tid`),
  CONSTRAINT `schoolcms_tcwct_ibfk_2` FOREIGN KEY (`cmid`) REFERENCES `schoolcms_curriculum` (`cmid`),
  CONSTRAINT `schoolcms_tcwct_ibfk_3` FOREIGN KEY (`wid`) REFERENCES `schoolcms_week` (`wid`),
  CONSTRAINT `schoolcms_tcwct_ibfk_4` FOREIGN KEY (`cid`) REFERENCES `schoolcms_class` (`cid`),
  CONSTRAINT `schoolcms_tcwct_ibfk_5` FOREIGN KEY (`teid`) REFERENCES `schoolcms_time` (`teid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='教师表,科目表,周表,班级表,时段表的关联表';

-- ----------------------------
-- Records of schoolcms_tcwct
-- ----------------------------
INSERT INTO `schoolcms_tcwct` VALUES ('1', '1', '1', '1', '6', '1');
INSERT INTO `schoolcms_tcwct` VALUES ('2', '1', '1', '3', '1', '4');

-- ----------------------------
-- Table structure for `schoolcms_teacher`
-- ----------------------------
DROP TABLE IF EXISTS `schoolcms_teacher`;
CREATE TABLE `schoolcms_teacher` (
  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '教师id',
  `tname` char(38) NOT NULL COMMENT '教师姓名',
  `tbirthdate` int(10) unsigned DEFAULT NULL COMMENT '出生日期',
  `tmobile` char(11) DEFAULT NULL COMMENT '教师手机号码',
  `thomephone` char(16) DEFAULT NULL COMMENT '家庭电话',
  `tmail` char(68) DEFAULT NULL COMMENT '教师电子邮箱',
  `tsex` int(1) unsigned DEFAULT '1' COMMENT '教师性别（1：保密，2：女，3：男）',
  `tsreatedate` datetime DEFAULT NULL COMMENT '创建日期',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='教师表';

-- ----------------------------
-- Records of schoolcms_teacher
-- ----------------------------
INSERT INTO `schoolcms_teacher` VALUES ('1', '刘宗在', '885484800', '13263176238', '0856-88333322', '386392432@qq.com', '3', '2014-01-04 19:57:50');
INSERT INTO `schoolcms_teacher` VALUES ('2', '333', '1389024000', '13222223333', '', '', '1', '2014-01-10 21:29:20');
INSERT INTO `schoolcms_teacher` VALUES ('3', '呃呃呃', '1388851200', '13222333333', '', '34434545@qq.com', '1', '2014-01-10 21:39:11');
INSERT INTO `schoolcms_teacher` VALUES ('4', 'sky', '1262620800', '13444444444', '021-33444433', '', '2', '2014-01-11 00:44:48');

-- ----------------------------
-- Table structure for `schoolcms_time`
-- ----------------------------
DROP TABLE IF EXISTS `schoolcms_time`;
CREATE TABLE `schoolcms_time` (
  `teid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '时段id',
  `tename` char(68) NOT NULL COMMENT '时段名称',
  `tesort` int(1) unsigned DEFAULT '0' COMMENT '显示顺序',
  `testatu` int(1) unsigned DEFAULT '1' COMMENT '状态（1：正常，0：不可用）',
  PRIMARY KEY (`teid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='时段表';

-- ----------------------------
-- Records of schoolcms_time
-- ----------------------------
INSERT INTO `schoolcms_time` VALUES ('1', '09::00-09:45', '1', '1');
INSERT INTO `schoolcms_time` VALUES ('2', '10:00-10:45', '2', '1');
INSERT INTO `schoolcms_time` VALUES ('3', '11:00-11:45', '3', '1');
INSERT INTO `schoolcms_time` VALUES ('4', '13:00-13:45', '4', '1');
INSERT INTO `schoolcms_time` VALUES ('5', '14:00-14:45', '5', '1');
INSERT INTO `schoolcms_time` VALUES ('6', '15:00-15:45', '6', '1');
INSERT INTO `schoolcms_time` VALUES ('7', '16:00-16:45', '7', '1');

-- ----------------------------
-- Table structure for `schoolcms_week`
-- ----------------------------
DROP TABLE IF EXISTS `schoolcms_week`;
CREATE TABLE `schoolcms_week` (
  `wid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '周id',
  `wname` char(38) NOT NULL COMMENT '周名称',
  `wsort` int(5) unsigned DEFAULT '0' COMMENT '显示顺序',
  `wstatu` int(1) unsigned DEFAULT '1' COMMENT '状态（1：正常，0：不可用）',
  PRIMARY KEY (`wid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='周表';

-- ----------------------------
-- Records of schoolcms_week
-- ----------------------------
INSERT INTO `schoolcms_week` VALUES ('1', '星期一', '0', '1');
INSERT INTO `schoolcms_week` VALUES ('2', '星期二', '0', '1');
INSERT INTO `schoolcms_week` VALUES ('3', '星期三', '0', '1');
INSERT INTO `schoolcms_week` VALUES ('4', '星期四', '0', '1');
INSERT INTO `schoolcms_week` VALUES ('5', '星期五', '0', '1');
INSERT INTO `schoolcms_week` VALUES ('6', '星期六', '0', '1');
INSERT INTO `schoolcms_week` VALUES ('7', '星期日', '0', '1');

-- ----------------------------
-- View structure for `schoolcms_view_payment_student`
-- ----------------------------
DROP VIEW IF EXISTS `schoolcms_view_payment_student`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `schoolcms_view_payment_student` AS select `schoolcms_student`.`sname` AS `sname`,`schoolcms_payment`.`ptmoney` AS `ptmoney`,`schoolcms_payment`.`ptid` AS `ptid`,`schoolcms_payment`.`ptremarks` AS `ptremarks`,`schoolcms_payment`.`ptdate` AS `ptdate`,`schoolcms_payment`.`sid` AS `sid`,`schoolcms_payment`.`ptupdate` AS `ptupdate`,`schoolcms_semester`.`srid` AS `srid` from ((`schoolcms_student` join `schoolcms_payment` on((`schoolcms_payment`.`sid` = `schoolcms_student`.`sid`))) join `schoolcms_semester` on((`schoolcms_semester`.`srid` = `schoolcms_student`.`srid`))) where (`schoolcms_semester`.`srstatu` = 1) ;

-- ----------------------------
-- View structure for `schoolcms_view_schoolteachers`
-- ----------------------------
DROP VIEW IF EXISTS `schoolcms_view_schoolteachers`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `schoolcms_view_schoolteachers` AS select `schoolcms_teacher`.`tname` AS `tname`,`schoolcms_class`.`cname` AS `cname`,`schoolcms_curriculum`.`cmname` AS `cmname`,`schoolcms_week`.`wname` AS `wname`,`schoolcms_time`.`tename` AS `tename`,`schoolcms_tcwct`.`ttid` AS `ttid`,`schoolcms_tcwct`.`tid` AS `tid`,`schoolcms_tcwct`.`cmid` AS `cmid`,`schoolcms_tcwct`.`wid` AS `wid`,`schoolcms_tcwct`.`cid` AS `cid` from (((((`schoolcms_tcwct` join `schoolcms_teacher` on((`schoolcms_tcwct`.`tid` = `schoolcms_teacher`.`tid`))) join `schoolcms_class` on((`schoolcms_tcwct`.`cid` = `schoolcms_class`.`cid`))) join `schoolcms_curriculum` on((`schoolcms_curriculum`.`cmid` = `schoolcms_tcwct`.`cmid`))) join `schoolcms_week` on((`schoolcms_week`.`wid` = `schoolcms_tcwct`.`wid`))) join `schoolcms_time` on((`schoolcms_tcwct`.`teid` = `schoolcms_time`.`teid`))) where ((`schoolcms_time`.`testatu` = 1) and (`schoolcms_class`.`cstatu` = 1) and (`schoolcms_curriculum`.`cmstatu` = 1) and (`schoolcms_week`.`wstatu` = 1)) ;

-- ----------------------------
-- View structure for `schoolcms_view_student`
-- ----------------------------
DROP VIEW IF EXISTS `schoolcms_view_student`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `schoolcms_view_student` AS select `schoolcms_student`.`sid` AS `sid`,`schoolcms_student`.`cid` AS `cid`,`schoolcms_student`.`sname` AS `sname`,`schoolcms_student`.`ssex` AS `ssex`,`schoolcms_student`.`ssreatedate` AS `ssreatedate`,`schoolcms_class`.`cname` AS `cname`,`schoolcms_student`.`sbirthdate` AS `sbirthdate`,`schoolcms_student`.`stuitionstatu` AS `stuitionstatu`,`schoolcms_student`.`smobile` AS `smobile`,`schoolcms_student`.`shomephone` AS `shomephone`,`schoolcms_studentclassify`.`cyname` AS `cyname`,`schoolcms_student`.`cyid` AS `cyid`,`schoolcms_student`.`stheendtime` AS `stheendtime`,`schoolcms_student`.`seffectivetime` AS `seffectivetime`,`schoolcms_student`.`srid` AS `srid`,`schoolcms_semester`.`srname` AS `srname` from (((`schoolcms_student` join `schoolcms_class` on((`schoolcms_class`.`cid` = `schoolcms_student`.`cid`))) join `schoolcms_studentclassify` on((`schoolcms_studentclassify`.`cyid` = `schoolcms_student`.`cyid`))) join `schoolcms_semester` on((`schoolcms_student`.`srid` = `schoolcms_semester`.`srid`))) where ((`schoolcms_class`.`cstatu` = 1) and (`schoolcms_studentclassify`.`cystatu` = 1) and (`schoolcms_semester`.`srstatu` = 1)) ;

-- ----------------------------
-- View structure for `schoolcms_view_studentachievement`
-- ----------------------------
DROP VIEW IF EXISTS `schoolcms_view_studentachievement`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `schoolcms_view_studentachievement` AS select `schoolcms_achievementclass`.`atcname` AS `atcname`,`schoolcms_achievement`.`atfraction` AS `atfraction`,`schoolcms_achievement`.`sid` AS `sid`,`schoolcms_curriculum`.`cmname` AS `cmname`,`schoolcms_student`.`sname` AS `sname`,`schoolcms_student`.`ssex` AS `ssex`,`schoolcms_class`.`cname` AS `cname`,`schoolcms_student`.`cid` AS `cid`,`schoolcms_achievement`.`atcid` AS `atcid`,`schoolcms_achievement`.`cmid` AS `cmid`,`schoolcms_achievement`.`atid` AS `atid`,`schoolcms_achievement`.`atscoretype` AS `atscoretype`,`schoolcms_student`.`sbirthdate` AS `sbirthdate`,`schoolcms_achievement`.`srid` AS `srid`,`schoolcms_semester`.`srname` AS `srname` from (((((`schoolcms_achievement` join `schoolcms_achievementclass` on((`schoolcms_achievement`.`atcid` = `schoolcms_achievementclass`.`atcid`))) join `schoolcms_curriculum` on((`schoolcms_curriculum`.`cmid` = `schoolcms_achievement`.`cmid`))) join `schoolcms_student` on((`schoolcms_student`.`sid` = `schoolcms_achievement`.`sid`))) join `schoolcms_class` on((`schoolcms_class`.`cid` = `schoolcms_student`.`cid`))) join `schoolcms_semester` on((`schoolcms_achievement`.`srid` = `schoolcms_semester`.`srid`))) where ((`schoolcms_curriculum`.`cmstatu` = 1) and (`schoolcms_achievement`.`atstatu` = 1) and (`schoolcms_class`.`cstatu` = 1) and (`schoolcms_semester`.`srstatu` = 1) and (`schoolcms_achievementclass`.`atcstatu` = 1)) ;
