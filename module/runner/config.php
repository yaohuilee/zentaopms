<?php
global $lang;
$config->runner->packageURL  = 'https://dl.zentao.net/gitfox/runner/gitfox_runner_';
$config->runner->cmdList     = $lang->runner->cmdList;

$config->runner->actionList = array();
$config->runner->actionList['enable']['icon']         = 'active';
$config->runner->actionList['enable']['hint']         = $lang->runner->enable;
$config->runner->actionList['enable']['url']          = array('module' => 'runner', 'method' => 'changeState', 'params' => 'runnerID={id}&status=enable');
$config->runner->actionList['enable']['ajaxSubmit']   = true;
$config->runner->actionList['enable']['notLoadModel'] = true;
$config->runner->actionList['enable']['showText']     = true;

$config->runner->actionList['disable']['icon']         = 'cancel';
$config->runner->actionList['disable']['hint']         = $lang->runner->disable;
$config->runner->actionList['disable']['url']          = array('module' => 'runner', 'method' => 'changeState', 'params' => 'runnerID={id}&status=disable');
$config->runner->actionList['disable']['data-confirm'] = array('message' => $lang->runner->notice->confirmDisable, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->runner->actionList['disable']['ajaxSubmit']   = true;
$config->runner->actionList['disable']['notLoadModel'] = true;
$config->runner->actionList['disable']['showText']     = true;

$config->runner->actionList['edit']['icon']        = 'edit';
$config->runner->actionList['edit']['hint']        = $lang->runner->edit;
$config->runner->actionList['edit']['url']         = array('module' => 'runner', 'method' => 'edit', 'params' => 'runnerID={id}');
$config->runner->actionList['edit']['showText']    = true;
$config->runner->actionList['edit']['data-toggle'] = 'modal';

$config->runner->actionList['delete']['icon']         = 'trash';
$config->runner->actionList['delete']['hint']         = $lang->runner->delete;
$config->runner->actionList['delete']['url']          = array('module' => 'runner', 'method' => 'delete', 'params' => 'runnerID={id}');
$config->runner->actionList['delete']['data-confirm'] = array('message' => $lang->runner->notice->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->runner->actionList['delete']['ajaxSubmit']   = true;
$config->runner->actionList['delete']['showText']     = true;

$config->runner->apiError = array();
