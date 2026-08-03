ALTER TABLE `zt_ai_agent` MODIFY COLUMN `skill` varchar(255) NOT NULL DEFAULT '' COMMENT '关联的技能ID列表';
UPDATE `zt_ai_agent` SET `skill` = '' WHERE `skill` = '0';

-- DROP TABLE IF EXISTS `zt_ai_vectorqueue`;
CREATE TABLE IF NOT EXISTS `zt_ai_vectorqueue` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `objectType` varchar(255) NOT NULL DEFAULT '' COMMENT '对象类型',
  `objectID` int unsigned NOT NULL DEFAULT 0 COMMENT '对象ID',
  `retries` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '重试次数',
  `lastError` text NULL COMMENT '最后错误',
  `lastSyncTime` datetime NULL DEFAULT NULL COMMENT '最后同步时间',
  `createdDate` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `editedDate` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE UNIQUE INDEX `uk_object` ON `zt_ai_vectorqueue` (`objectType`, `objectID`);
CREATE INDEX `idx_retries` ON `zt_ai_vectorqueue` (`retries`);

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`) VALUES
('*/5', '*', '*', '*', '*', 'moduleName=zai&methodName=syncVectorization', '自动同步向量化数据', 'zentao', 1, 'normal');
