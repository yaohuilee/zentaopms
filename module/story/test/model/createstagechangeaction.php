#!/usr/bin/env php
<?php

/**

title=测试 storyModel::createStageChangeAction();
timeout=0
cid=0

- 步骤1：关联到计划，阶段从wait变为planned，验证创建action成功 >> action ID > 0
- 步骤2：阶段未变化(wait→wait)，验证不创建action >> 返回0
- 步骤3：trigger类型为空，验证不创建action >> 返回0
- 步骤4：关联到项目，阶段从wait变为projected，验证创建action成功 >> action ID > 0
- 步骤5：编辑需求，阶段从planned变为developing，验证创建action成功 >> action ID > 0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->stage->range('wait{3},planned{3},projected{4}');
$storyTable->product->range('1');
$storyTable->gen(10);

$planTable = zenData('productplan');
$planTable->id->range('1');
$planTable->title->range('测试计划');
$planTable->product->range('1');
$planTable->gen(1);

$projectTable = zenData('project');
$projectTable->id->range('1');
$projectTable->name->range('测试项目');
$projectTable->type->range('project');
$projectTable->gen(1);

$storyTest = new storyModelTest();

r($storyTest->createStageChangeActionTest(1, 'wait', 'planned', array('type' => 'linkPlan', 'objectID' => 1))) && p() && e('1'); // 步骤1：关联计划，wait→planned，应创建action
r($storyTest->createStageChangeActionTest(2, 'wait', 'wait', array('type' => 'linkPlan', 'objectID' => 1))) && p() && e('0'); // 步骤2：阶段未变，不创建action
r($storyTest->createStageChangeActionTest(3, 'wait', 'planned', array('objectID' => 1))) && p() && e('0'); // 步骤3：无trigger type，不创建action
r($storyTest->createStageChangeActionTest(5, 'wait', 'projected', array('type' => 'linkProject', 'objectID' => 1))) && p() && e('1'); // 步骤4：关联项目，wait→projected，应创建action
r($storyTest->createStageChangeActionTest(7, 'planned', 'developing', array('type' => 'edit', 'objectID' => 0))) && p() && e('1'); // 步骤5：编辑需求，planned→developing，应创建action
