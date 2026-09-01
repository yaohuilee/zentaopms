-- DROP TABLE IF EXISTS `zt_errorlog`;
CREATE TABLE IF NOT EXISTS `zt_errorlog` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `requestID` varchar(64) NOT NULL DEFAULT '' COMMENT '请求ID',
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '用户账号',
  `module` varchar(30) NOT NULL DEFAULT '' COMMENT '模块',
  `method` varchar(30) NOT NULL DEFAULT '' COMMENT '方法',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '请求地址',
  `level` smallint unsigned NOT NULL DEFAULT 0 COMMENT '错误级别',
  `message` text DEFAULT NULL COMMENT '错误信息',
  `file` varchar(255) NOT NULL DEFAULT '' COMMENT '错误文件',
  `line` int unsigned NOT NULL DEFAULT 0 COMMENT '错误行号',
  `trace` text DEFAULT NULL COMMENT '错误堆栈',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `requestID` (`requestID`),
  KEY `module` (`module`),
  KEY `level` (`level`),
  KEY `createdDate` (`createdDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='错误日志';

REPLACE INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES
('*/5', '*', '*', '*', '*', 'moduleName=errorlog&methodName=deleteLog', '删除过期错误日志', 'zentao', 1, 'normal', NULL);
