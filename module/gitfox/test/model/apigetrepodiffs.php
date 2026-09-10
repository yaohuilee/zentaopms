#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

zenData('entry')->loadYaml('entry')->gen(1);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('admin');
$zd_user->realname->range('admin');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

$tester->loadModel('gitfox');


$testObj = new gitfoxModelTest();
/**

title=测试 gitfoxModel::apiGetRepoDiffs()
timeout=0
cid=0

- 步骤1：正常输入，返回资源未找到 @failure,资源未找到。
- 步骤2：边界值输入，返回路径参数解析失败 @failure,Path 参数解析失败。
- 步骤3：无效输入，返回路径参数解析失败 @failure,Path 参数解析失败。
- 步骤4：大值输入，返回资源未找到 @failure,资源未找到。
- 步骤5：业务规则验证，仓库不存在时返回资源未找到 @failure,资源未找到。

*/

$result = json_decode($testObj->apiGetRepoDiffsTest(1, '1', '1'));
r($result) && p('code,message') && e('failure,资源未找到。'); // 步骤1：正常输入，返回资源未找到
$result = json_decode($testObj->apiGetRepoDiffsTest(0, '1', '1'));
r($result) && p('code,message') && e('failure,Path 参数解析失败。'); // 步骤2：边界值输入，返回路径参数解析失败
$result = json_decode($testObj->apiGetRepoDiffsTest(-1, '1', '1'));
r($result) && p('code,message') && e('failure,Path 参数解析失败。'); // 步骤3：无效输入，返回路径参数解析失败
$result = json_decode($testObj->apiGetRepoDiffsTest(999999, '1', '1'));
r($result) && p('code,message') && e('failure,资源未找到。'); // 步骤4：大值输入，返回资源未找到
$result = json_decode($testObj->apiGetRepoDiffsTest(2, 'test', '1'));
r($result) && p('code,message') && e('failure,资源未找到。'); // 步骤5：业务规则验证，仓库不存在时返回资源未找到
