-- DROP TABLE IF EXISTS `zt_errorlog`;
CREATE TABLE IF NOT EXISTS `zt_errorlog` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `requestID` varchar(64) NOT NULL DEFAULT '' COMMENT '请求ID',
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '用户账号',
  `module` varchar(30) NOT NULL DEFAULT '' COMMENT '模块',
  `method` varchar(100) NOT NULL DEFAULT '' COMMENT '方法',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '请求地址',
  `level` smallint unsigned NOT NULL DEFAULT 0 COMMENT '错误级别',
  `message` text DEFAULT NULL COMMENT '错误信息',
  `file` varchar(255) NOT NULL DEFAULT '' COMMENT '错误文件',
  `line` int unsigned NOT NULL DEFAULT 0 COMMENT '错误行号',
  `trace` text DEFAULT NULL COMMENT '错误堆栈',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='错误日志';

CREATE INDEX `idx_requestID`   ON `zt_errorlog`(`requestID`);
CREATE INDEX `idx_module`      ON `zt_errorlog`(`module`);
CREATE INDEX `idx_level`       ON `zt_errorlog`(`level`);
CREATE INDEX `idx_createdDate` ON `zt_errorlog`(`createdDate`);

REPLACE INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES
('*/5', '*', '*', '*', '*', 'moduleName=errorlog&methodName=deleteLog', '删除过期错误日志', 'zentao', 1, 'normal', NULL);
