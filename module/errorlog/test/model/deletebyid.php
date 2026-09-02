#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::deleteByID();
timeout=0
cid=0

- 执行errorlog模块的deleteByIDTest方法，参数是2 @1
- 执行errorlog模块的getListTest方法 @2
- 执行errorlog模块的getByIDTest方法，参数是2 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$errorlog = zenData('errorlog');
$errorlog->requestID->range('req-001{3}');
$errorlog->gen(3);

su('admin');

$errorlogModel = new errorlogModelTest();

r($errorlogModel->deleteByIDTest(2)) && p() && e('1');
r(count($errorlogModel->getListTest())) && p() && e('2');
r($errorlogModel->getByIDTest(2)) && p() && e('0');
