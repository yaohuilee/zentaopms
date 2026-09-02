#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::getList();
timeout=0
cid=0

- 执行errorlog模块的getListTest方法，参数是'', 'id_desc'
 - 第1条的requestID属性 @req-002
 - 第2条的requestID属性 @req-002
- 执行errorlog模块的getListTest方法，参数是t1.`module` = 'bug', 'id_desc'
 - 第1条的requestID属性 @req-002
 - 第2条的requestID属性 @req-001

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$errorlog = zenData('errorlog');
$errorlog->requestID->range('req-001{2},req-002{3}');
$errorlog->module->range('bug{3},task{2}');
$errorlog->gen(5);

su('admin');

$errorlogModel = new errorlogModelTest();

r($errorlogModel->getListTest()) && p('1.requestID,2.requestID') && e('req-002,req-002');
r($errorlogModel->getListTest("`module` = 'bug'")) && p('1.requestID,2.requestID') && e('req-002,req-001');
