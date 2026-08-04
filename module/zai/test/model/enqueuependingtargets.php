#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->enqueuePendingTargets();
timeout=0
cid=19802

- 测试未启用时不分批入队 @0
- 测试启用后按游标入队数量 @1
- 测试入队后游标更新 @1
- 测试新类型自动初始化游标 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('config')->gen(0);
zenData('story')->gen(3);
zenData('bug')->gen(2);

su('admin');

global $tester;
$zai = new zaiModelTest();

$tester->dbh->exec("CREATE TABLE IF NOT EXISTS `zt_zaivectorqueue` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `objectType` varchar(30) NOT NULL DEFAULT '',
  `objectID` int unsigned NOT NULL DEFAULT 0,
  `retries` tinyint unsigned NOT NULL DEFAULT 0,
  `lastError` text NULL,
  `lastSyncTime` datetime DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `updatedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`objectType`, `objectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$tester->dbh->exec("DELETE FROM `zt_zaivectorqueue`");

r($zai->enqueuePendingTargetsTest(1000)) && p() && e('0'); // 测试未启用时不分批入队

$vectorInfo = new stdClass();
$vectorInfo->key = 'memory-key-2';
$vectorInfo->status = 'syncing';
$vectorInfo->syncedTime = 0;
$vectorInfo->syncedCount = 0;
$vectorInfo->syncFailedCount = 0;
$vectorInfo->syncTime = 0;
$vectorInfo->syncingType = 'story';
$vectorInfo->syncingID = 0;
$vectorInfo->syncDetails = new stdClass();
$vectorInfo->enqueueMaxIDs = new stdClass();
$vectorInfo->enqueueMaxIDs->story = 0;
$vectorInfo->createdAt = time();
$vectorInfo->createdBy = 'admin';
$zai->setVectorizedInfoTest($vectorInfo);

$enqueued = $zai->enqueuePendingTargetsTest(1);
r($enqueued) && p() && e('1'); // 测试启用后按游标入队数量

$info = $zai->getVectorizedInfoTest();
r((int)$info->enqueueMaxIDs->story) && p() && e('1'); // 测试入队后游标更新

$info = $zai->ensureEnqueueCursorsTest();
r(isset($info->enqueueMaxIDs->bug)) && p() && e('1'); // 测试新类型自动初始化游标
