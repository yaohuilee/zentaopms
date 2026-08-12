#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getSearchQuery();
timeout=0
cid=0

- 无queryID且有会话查询返回会话SQL @1
- 无queryID且无会话查询返回false @0
- 有效queryID返回保存的查询SQL @1
- 无效queryID且无会话查询返回false @0
- 负责人条件被翻译为空间ID过滤 @1
- 无效queryID但有会话查询返回会话SQL @1
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
zenData('userquery')->gen(0);
zenData('entry')->loadYaml('entry')->gen(1);

su('admin');

$spaceTester = new spaceModelTest();

unset($_SESSION['spaceSearchQuery']);
$_SESSION['spaceSearchQuery'] = "(( 1 = 1 AND `name` = 'ut-search-12' ) AND ( 1 = 1 ))";
r($spaceTester->getSearchQueryTest()) && p() && e("(( 1 = 1 AND `name` = 'ut-search-12' ) AND ( 1 = 1 ))");       // 无queryID且有会话查询返回会话SQL

unset($_SESSION['spaceSearchQuery']);
r($spaceTester->getSearchQueryTest()) && p() && e('0');                                                          // 无queryID且无会话查询返回false

$tester->dao->insert(TABLE_USERQUERY)->data(array('account' => 'admin', 'module' => 'spaceSearch', 'title' => 'ut saved query', 'form' => serialize(array()), 'sql' => "(( 1 = 1 AND `name` = 'ut-saved-query' ) AND ( 1 = 1 ))"))->exec();
$queryID = (int)$tester->dao->lastInsertID();
unset($_SESSION['spaceSearchQuery']);
r($spaceTester->getSearchQueryTest($queryID)) && p() && e("(( 1 = 1 AND `name` = 'ut-saved-query' ) AND ( 1 = 1 ))"); // 有效queryID返回保存的查询SQL
r($_SESSION['spaceSearchQuery'] ?? false) && p() && e("(( 1 = 1 AND `name` = 'ut-saved-query' ) AND ( 1 = 1 ))"); // 有效queryID将查询写入会话

unset($_SESSION['spaceSearchQuery']);
r($spaceTester->getSearchQueryTest(99999)) && p() && e('0');                                                     // 无效queryID且无会话查询返回false

$members = array(
    1 => array('user1' => (object)array('account' => 'user1', 'space' => 1, 'role' => 'manager')),
    2 => array('user1' => (object)array('account' => 'user1', 'space' => 2, 'role' => 'member')),
    3 => array('user2' => (object)array('account' => 'user2', 'space' => 3, 'role' => 'manager')),
);
$_SESSION['spaceSearchQuery'] = "(( 1 = 1 AND `managers` = 'user1' ) AND ( 1 = 1 ))";
r($spaceTester->getSearchQueryTest(0, $members)) && p() && e("(( 1 = 1 AND `id` IN (1) ) AND ( 1 = 1 ))");       // 负责人条件被翻译为空间ID过滤

$_SESSION['spaceSearchQuery'] = "(( 1 = 1 AND `name` = 'ut-search-12' ) AND ( 1 = 1 ))";
r($spaceTester->getSearchQueryTest(99999)) && p() && e("(( 1 = 1 AND `name` = 'ut-search-12' ) AND ( 1 = 1 ))"); // 无效queryID但有会话查询返回会话SQL
