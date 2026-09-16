#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getListByAccount() 搜索场景;
timeout=0
cid=0

- 管理员模式下bySearch无会话查询返回空 @0
- 管理员模式下bySearch带会话查询返回匹配数量 @1
- 管理员模式下bySearch带会话查询返回匹配空间名称 @1
- 管理员模式下bySearch查询不存在的名称返回空 @0
- 管理员模式下type为all返回全部空间 @3
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('ops_space')->gen(0);
zenData('ops_spaceuser')->gen(0);
zenData('group')->gen(0);
zenData('usergroup')->gen(0);
zenData('ops_repo')->gen(0);
zenData('ops_repouser')->gen(0);
zenData('entry')->loadYaml('entry')->gen(1);

su('admin');

$spaceTester = new spaceModelTest();

$spaceData = zenData('ops_space');
$spaceData->id->range('1-3');
$spaceData->name->range('ut-search-space-12,ut-search-space-34,ut-search-space-other');
$spaceData->code->range('utsearch12,utsearch34,utsearchother');
$spaceData->acl->range('open{3}');
$spaceData->auth->range('extend{3}');
$spaceData->createdBy->range('admin{3}');
$spaceData->deleted->range('0{3}');
$spaceData->gen(3);

unset($_SESSION['spaceSearchQuery']);
r($spaceTester->getListByAccountCountByTypeTest('admin', 'bySearch')) && p() && e('0');                                        // 管理员模式下bySearch无会话查询返回空

$_SESSION['spaceSearchQuery'] = "(( 1 = 1 AND `name` LIKE '%12%' ) AND ( 1 = 1 ))";
r($spaceTester->getListByAccountCountByTypeTest('admin', 'bySearch')) && p() && e('1');                                        // 管理员模式下bySearch带会话查询返回匹配数量
r($spaceTester->getListByAccountFirstNameByTypeTest('admin', 'bySearch')) && p() && e('ut-search-space-12');                   // 管理员模式下bySearch带会话查询返回匹配空间名称

$_SESSION['spaceSearchQuery'] = "(( 1 = 1 AND `name` LIKE '%notexist%' ) AND ( 1 = 1 ))";
r($spaceTester->getListByAccountCountByTypeTest('admin', 'bySearch')) && p() && e('0');                                        // 管理员模式下bySearch查询不存在的名称返回空

r($spaceTester->getListByAccountCountByTypeTest('admin')) && p() && e('3');                                                    // 管理员模式下type为all返回全部空间
