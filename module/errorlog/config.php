<?php
$config->errorlog = new stdclass();
$config->errorlog->enabled     = true;
$config->errorlog->saveDays    = 30;
$config->errorlog->maxSaveDays = 90;
$config->errorlog->maxRecords  = 100;

global $lang;
if(!isset($lang->errorlog)) $lang->errorlog = new stdclass();

$config->errorlog->search['module']   = 'errorlog';
$config->errorlog->search['fields']['id']          = $lang->idAB;
$config->errorlog->search['fields']['requestID']   = $lang->errorlog->requestID;
$config->errorlog->search['fields']['module']      = $lang->errorlog->module;
$config->errorlog->search['fields']['method']      = $lang->errorlog->method;
$config->errorlog->search['fields']['level']       = $lang->errorlog->level;
$config->errorlog->search['fields']['message']     = $lang->errorlog->message;
$config->errorlog->search['fields']['createdDate'] = $lang->errorlog->createdDate;

$config->errorlog->search['params']['id']          = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->errorlog->search['params']['requestID']   = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->errorlog->search['params']['module']      = array('operator' => '=',       'control' => 'select', 'values' => array());
$config->errorlog->search['params']['method']      = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->errorlog->search['params']['level']       = array('operator' => '=',       'control' => 'select', 'values' => $lang->errorlog->levelList);
$config->errorlog->search['params']['message']     = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->errorlog->search['params']['createdDate'] = array('operator' => '=',       'control' => 'date',   'values' => '');

$config->errorlog->actionList = array();
$config->errorlog->actionList['view']['icon']        = 'eye';
$config->errorlog->actionList['view']['text']        = '';
$config->errorlog->actionList['view']['hint']        = $lang->errorlog->view;
$config->errorlog->actionList['view']['url']         = array('module' => 'errorlog', 'method' => 'view', 'params' => 'id={id}');
$config->errorlog->actionList['view']['data-toggle'] = 'modal';
$config->errorlog->actionList['view']['data-size']   = 'lg';

$config->errorlog->dtable = new stdclass();
$config->errorlog->dtable->fieldList = array();
$config->errorlog->dtable->fieldList['id']['name']     = 'id';
$config->errorlog->dtable->fieldList['id']['title']    = $lang->idAB;
$config->errorlog->dtable->fieldList['id']['type']     = 'id';
$config->errorlog->dtable->fieldList['id']['fixed']    = 'left';
$config->errorlog->dtable->fieldList['id']['sortType'] = true;

$config->errorlog->dtable->fieldList['requestID']['name']     = 'requestID';
$config->errorlog->dtable->fieldList['requestID']['title']    = $lang->errorlog->requestID;
$config->errorlog->dtable->fieldList['requestID']['type']     = 'text';
$config->errorlog->dtable->fieldList['requestID']['sortType'] = true;

$config->errorlog->dtable->fieldList['module']['name']     = 'module';
$config->errorlog->dtable->fieldList['module']['title']    = $lang->errorlog->module;
$config->errorlog->dtable->fieldList['module']['type']     = 'text';
$config->errorlog->dtable->fieldList['module']['sortType'] = true;

$config->errorlog->dtable->fieldList['method']['name']     = 'method';
$config->errorlog->dtable->fieldList['method']['title']    = $lang->errorlog->method;
$config->errorlog->dtable->fieldList['method']['type']     = 'text';
$config->errorlog->dtable->fieldList['method']['sortType'] = true;

$config->errorlog->dtable->fieldList['level']['name']     = 'level';
$config->errorlog->dtable->fieldList['level']['title']    = $lang->errorlog->level;
$config->errorlog->dtable->fieldList['level']['type']     = 'text';
$config->errorlog->dtable->fieldList['level']['sortType'] = true;

$config->errorlog->dtable->fieldList['account']['name']     = 'account';
$config->errorlog->dtable->fieldList['account']['title']    = $lang->errorlog->account;
$config->errorlog->dtable->fieldList['account']['type']     = 'text';
$config->errorlog->dtable->fieldList['account']['sortType'] = true;

$config->errorlog->dtable->fieldList['createdDate']['name']     = 'createdDate';
$config->errorlog->dtable->fieldList['createdDate']['title']    = $lang->errorlog->createdDate;
$config->errorlog->dtable->fieldList['createdDate']['type']     = 'datetime';
$config->errorlog->dtable->fieldList['createdDate']['sortType'] = true;

$config->errorlog->dtable->fieldList['message']['name']     = 'message';
$config->errorlog->dtable->fieldList['message']['title']    = $lang->errorlog->message;
$config->errorlog->dtable->fieldList['message']['type']     = 'text';
$config->errorlog->dtable->fieldList['message']['flex']     = true;

$config->errorlog->dtable->fieldList['actions']['title'] = $lang->actions;
$config->errorlog->dtable->fieldList['actions']['type']  = 'actions';
$config->errorlog->dtable->fieldList['actions']['list']  = $config->errorlog->actionList;
$config->errorlog->dtable->fieldList['actions']['menu']  = array_keys($config->errorlog->actionList);
