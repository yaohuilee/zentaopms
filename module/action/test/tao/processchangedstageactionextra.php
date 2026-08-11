#!/usr/bin/env php
<?php
/**

title=测试 actionTao::processChangedStageActionExtra();
timeout=0
cid=0

- 步骤1：linkPlan触发类型，验证输出包含"关联到计划"和"阶段更新为" >> 包含关联到计划和阶段更新为文案
- 步骤2：unlinkPlan触发类型，验证输出包含"移除了计划" >> 包含移除了计划文案
- 步骤3：linkProject触发类型，验证输出包含"关联到项目" >> 包含关联到项目文案
- 步骤4：unlinkProject触发类型，验证输出包含"移除了项目" >> 包含移除了项目文案
- 步骤5：startTask触发类型（设计任务），验证输出包含"开始了设计任务"和"阶段更新为" >> 包含开始了设计任务和阶段更新为文案
- 步骤6：finishTask触发类型（开发任务），验证输出包含"完成了开发任务" >> 包含完成了开发任务文案
- 步骤7：linkRelease触发类型，验证输出包含"关联到发布" >> 包含关联到发布文案
- 步骤8：unlinkRelease触发类型，验证输出包含"移除了发布" >> 包含移除了发布文案
- 步骤9：edit触发类型，验证输出包含"编辑需求后" >> 包含编辑需求后文案
- 步骤10：无效触发类型，验证返回空 >> 返回空

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备
$productplanTable = zenData('productplan');
$productplanTable->id->range('10001');
$productplanTable->title->range('测试计划');
$productplanTable->product->range('1');
$productplanTable->gen(1);

$projectTable = zenData('project');
$projectTable->id->range('10001');
$projectTable->name->range('测试项目');
$projectTable->type->range('project');
$projectTable->model->range('scrum');
$projectTable->gen(1);

$taskTable = zenData('task');
$taskTable->id->range('10001-10002');
$taskTable->name->range('设计任务1,开发任务1');
$taskTable->type->range('design,devel');
$taskTable->status->range('doing,done');
$taskTable->project->range('1');
$taskTable->execution->range('1');
$taskTable->gen(2);

$releaseTable = zenData('release');
$releaseTable->id->range('10001');
$releaseTable->name->range('测试发布');
$releaseTable->product->range('1');
$releaseTable->gen(1);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$actionTest = new actionTaoTest();

// 5. 测试步骤（至少5个，每个r()...e()写在同一行，从行首开始）
r($actionTest->processChangedStageActionExtraTest('linkPlan|10001|planned'))        && p() && e('1'); // 步骤1：linkPlan触发，关联到计划+阶段更新为已计划
r($actionTest->processChangedStageActionExtraTest('unlinkPlan|10001|wait'))         && p() && e('1'); // 步骤2：unlinkPlan触发，移除计划+阶段更新为未开始
r($actionTest->processChangedStageActionExtraTest('linkProject|10001|projected'))   && p() && e('1'); // 步骤3：linkProject触发，关联到项目+阶段更新为研发立项
r($actionTest->processChangedStageActionExtraTest('unlinkProject|10001|wait'))      && p() && e('1'); // 步骤4：unlinkProject触发，移除项目+阶段更新为未开始
r($actionTest->processChangedStageActionExtraTest('startTask|10001|designing'))     && p() && e('1'); // 步骤5：startTask触发设计任务，开始设计任务+阶段更新为设计中
r($actionTest->processChangedStageActionExtraTest('finishTask|10002|developed'))    && p() && e('1'); // 步骤6：finishTask触发开发任务，完成开发任务+阶段更新为研发完毕
r($actionTest->processChangedStageActionExtraTest('linkRelease|10001|released'))    && p() && e('1'); // 步骤7：linkRelease触发，关联到发布+阶段更新为已发布
r($actionTest->processChangedStageActionExtraTest('unlinkRelease|10001|projected')) && p() && e('1'); // 步骤8：unlinkRelease触发，移除发布+阶段更新为研发立项
r($actionTest->processChangedStageActionExtraTest('edit|0|testing'))                && p() && e('1'); // 步骤9：edit触发，编辑需求后+阶段更新为测试中
r($actionTest->processChangedStageActionExtraTest('invalid|0|wait'))                && p() && e('0'); // 步骤10：无效触发类型，返回空
