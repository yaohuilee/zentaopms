#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

error_reporting(E_ERROR);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('1-1');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

$tester->loadModel('pipeline');
helper::import($tester->app->getModulePath('', 'pipeline') . 'control.php');
helper::import($tester->app->getModulePath('', 'pipeline') . 'zen.php');


$testObj = new pipelineZenTest();
/**

title=测试 pipelineModel::getPipelineSearchQuery()
timeout=0
cid=0

- 步骤1：正常输入 @1 = 1
- 步骤2：边界值输入 @1 = 1
- 步骤3：无效输入 @1 = 1
- 步骤4：大值输入 @1 = 1
- 步骤5：业务规则验证 @1 = 1

*/

$result = $testObj->getPipelineSearchQueryTest(1, 'pipelineQuery');
r(trim($result)) && p() && e('1 = 1'); // 步骤1：正常输入
$result = $testObj->getPipelineSearchQueryTest(0, 'pipelineQuery');
r(trim($result)) && p() && e('1 = 1'); // 步骤2：边界值输入
$result = $testObj->getPipelineSearchQueryTest(-1, 'pipelineQuery');
r(trim($result)) && p() && e('1 = 1'); // 步骤3：无效输入
$result = $testObj->getPipelineSearchQueryTest(999999, 'pipelineQuery');
r(trim($result)) && p() && e('1 = 1'); // 步骤4：大值输入
$result = $testObj->getPipelineSearchQueryTest(2, 'test');
r(trim($result)) && p() && e('1 = 1'); // 步骤5：业务规则验证
