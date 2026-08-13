ALTER TABLE `ops_runner` MODIFY COLUMN `labels` varchar(255) NOT NULL DEFAULT '' COMMENT '标签';
ALTER TABLE `ops_runner` ADD COLUMN `isDefault` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否为默认 runner(0:不是, 1:是)' AFTER `status`;
ALTER TABLE `ops_runner` ADD COLUMN `runtime`  varchar(50) NOT NULL DEFAULT '' COMMENT '运行方式' AFTER `arch`;

ALTER TABLE `ops_scan_issues`
CHANGE COLUMN `message` `title` varchar(500) NOT NULL DEFAULT '' COMMENT '问题描述信息',
CHANGE COLUMN `line` `startLine` int unsigned NOT NULL DEFAULT 0 COMMENT '问题起始行',
CHANGE COLUMN `resolved` `resolvedDate` datetime DEFAULT NULL COMMENT '问题解决时间',
CHANGE COLUMN `closed` `closedDate` datetime DEFAULT NULL COMMENT '问题关闭时间',
CHANGE COLUMN `ignored` `ignoredDate` bigint NOT NULL DEFAULT 0 COMMENT '问题忽略到期时间',
MODIFY COLUMN `scanMethod` varchar(20) NOT NULL DEFAULT '' COMMENT '扫描方法（check/smell/ai）';

ALTER TABLE `ops_scan_issues`
ADD COLUMN `ppmID` int unsigned NOT NULL DEFAULT 0 COMMENT '合并请求ID',
ADD COLUMN `content` text DEFAULT NULL COMMENT '问题详细内容',
ADD COLUMN `endLine` int unsigned NOT NULL DEFAULT 0 COMMENT '问题结束行',
ADD COLUMN `oldCode` text DEFAULT NULL COMMENT '原代码片段',
ADD COLUMN `newCode` text DEFAULT NULL COMMENT '建议代码片段',
ADD COLUMN `category` varchar(30) NOT NULL DEFAULT 'other' COMMENT '问题类别（critical,high,medium,low）',
ADD COLUMN `severity` varchar(30) NOT NULL DEFAULT '' COMMENT '严重程度（bug,security,performance,maintainability,test,style,documentation,other）',
ADD COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '创建人',
ADD COLUMN `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '更新人';

CREATE INDEX `idx_createdByTaskID` ON `ops_scan_issues` (`createdByTaskID`);
CREATE INDEX `idx_ppmID` ON `ops_scan_issues` (`ppmID`);
