<?php
$config->runner->dtable = new stdclass();

$config->runner->dtable->fieldList['id']['title'] = 'ID';
$config->runner->dtable->fieldList['id']['type']  = 'id';

$config->runner->dtable->fieldList['name']['title']       = $lang->runner->name;
$config->runner->dtable->fieldList['name']['name']        = 'name';
$config->runner->dtable->fieldList['name']['fixed']       = 'left';
$config->runner->dtable->fieldList['name']['type']        = 'shortTitle';
$config->runner->dtable->fieldList['name']['sortType']    = false;
$config->runner->dtable->fieldList['name']['width']       = '200';
$config->runner->dtable->fieldList['name']['hint']        = true;
$config->runner->dtable->fieldList['name']['show']        = true;
$config->runner->dtable->fieldList['name']['required']    = true;
$config->runner->dtable->fieldList['name']['checkbox']    = false;

$config->runner->dtable->fieldList['runnerStatus']['title']     = $lang->runner->status;
$config->runner->dtable->fieldList['runnerStatus']['name']      = 'runnerStatus';
$config->runner->dtable->fieldList['runnerStatus']['type']      = 'status';
$config->runner->dtable->fieldList['runnerStatus']['statusMap'] = $lang->runner->statusList;
$config->runner->dtable->fieldList['runnerStatus']['sortType']  = true;
$config->runner->dtable->fieldList['runnerStatus']['show']      = true;
$config->runner->dtable->fieldList['runnerStatus']['width']     = '40';

$config->runner->dtable->fieldList['platOrArch']['title']    = $lang->runner->platOrArch;
$config->runner->dtable->fieldList['platOrArch']['name']     = 'platOrArch';
$config->runner->dtable->fieldList['platOrArch']['sortType'] = true;
$config->runner->dtable->fieldList['platOrArch']['width']    = '60';
$config->runner->dtable->fieldList['platOrArch']['hint']     = true;
$config->runner->dtable->fieldList['platOrArch']['show']     = true;

$config->runner->dtable->fieldList['version']['title']    = $lang->runner->version;
$config->runner->dtable->fieldList['version']['name']     = 'version';
$config->runner->dtable->fieldList['version']['sortType'] = false;
$config->runner->dtable->fieldList['version']['width']    = '60';
$config->runner->dtable->fieldList['version']['hint']     = true;
$config->runner->dtable->fieldList['version']['show']     = true;

$config->runner->dtable->fieldList['ip']['title']    = $lang->runner->ip;
$config->runner->dtable->fieldList['ip']['name']     = 'ip';
$config->runner->dtable->fieldList['ip']['sortType'] = false;
$config->runner->dtable->fieldList['ip']['width']    = '20';
$config->runner->dtable->fieldList['ip']['hint']     = true;
$config->runner->dtable->fieldList['ip']['show']     = true;

$config->runner->dtable->fieldList['actions']['name']  = 'actions';
$config->runner->dtable->fieldList['actions']['title'] = $lang->actions;
$config->runner->dtable->fieldList['actions']['type']  = 'actions';
$config->runner->dtable->fieldList['actions']['width'] = '80';
$config->runner->dtable->fieldList['actions']['menu']  = array('enable|disable', 'edit', 'delete');
$config->runner->dtable->fieldList['actions']['list']  = $config->runner->actionList;
