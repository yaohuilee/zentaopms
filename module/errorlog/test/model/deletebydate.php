#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::deleteByDate();
timeout=0
cid=0

- 执行errorlog模块的deleteByDateTest方法，参数是2026-06-01 00:00:00 @1
- 执行errorlog模块的getListTest方法
 - 第1条的createdDate属性 @2026-08-01 00:00:00

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$errorlog = zenData('errorlog');
$errorlog->requestID->range('req-001{2},req-002{3}');
$errorlog->createdDate->range('2026-01-01 00:00:00{2},2026-08-01 00:00:00{3}');
$errorlog->gen(5);

su('admin');

$errorlogModel = new errorlogModelTest();

r($errorlogModel->deleteByDateTest('2026-06-01 00:00:00')) && p() && e('1');
r(count($errorlogModel->getListTest())) && p() && e('3');
r($errorlogModel->getListTest()) && p('1.createdDate') && e('2026-08-01 00:00:00');
