ALTER TABLE `zt_ai_agent` ADD `type` varchar(20) NOT NULL DEFAULT 'normal' COMMENT '智能体类型：normal普通/timer定时' AFTER `code`;
ALTER TABLE `zt_ai_agent` ADD `operation` varchar(30) NOT NULL DEFAULT '' COMMENT '定时操作目的：report/risk/notify' AFTER `actionPurpose`;
ALTER TABLE `zt_ai_agent` ADD `cycleType` varchar(10) NOT NULL DEFAULT '' COMMENT '周期类型：day/week/month' AFTER `operation`;
ALTER TABLE `zt_ai_agent` ADD `cycleConfig` text NULL DEFAULT NULL COMMENT '周期配置' AFTER `cycleType`;
ALTER TABLE `zt_ai_agent` ADD `notifyRule` text NULL DEFAULT NULL COMMENT '通知规则JSON：roles/users/methods' AFTER `cycleConfig`;
ALTER TABLE `zt_ai_agent` ADD `lastRunDate` datetime NULL DEFAULT NULL COMMENT '最近一次定时执行时间' AFTER `editedDate`;

CREATE TABLE IF NOT EXISTS `zt_ai_timerlog` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `agent` int unsigned NOT NULL DEFAULT 0 COMMENT '智能体ID',
  `status` varchar(20) NOT NULL DEFAULT '' COMMENT '执行状态：success/fail/partial',
  `successCount` int unsigned NOT NULL DEFAULT 0 COMMENT '成功数量',
  `failCount` int unsigned NOT NULL DEFAULT 0 COMMENT '失败数量',
  `message` varchar(500) NOT NULL DEFAULT '' COMMENT '展示文案',
  `error` varchar(1000) NOT NULL DEFAULT '' COMMENT '错误摘要',
  `createdDate` datetime NULL DEFAULT NULL COMMENT '执行时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

REPLACE INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES
('*/5', '*', '*', '*', '*', 'moduleName=ai&methodName=runTimerAgents', '执行定时智能体', 'zentao', 1, 'normal', NULL);