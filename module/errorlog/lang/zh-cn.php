<?php
$lang->errorlog->common      = '错误日志';
$lang->errorlog->browse      = '浏览错误日志';
$lang->errorlog->view        = '查看错误日志';
$lang->errorlog->delete      = '删除';
$lang->errorlog->setting     = '设置';
$lang->errorlog->days        = '保存天数';
$lang->errorlog->info        = '超过保存天数的错误日志会被删除，需要开启计划任务。';
$lang->errorlog->notFound    = '没有找到对应的错误日志。';
$lang->errorlog->empty       = '暂无错误日志。';
$lang->errorlog->confirmDelete = '您确认要删除该错误日志吗？';

$lang->errorlog->requestID   = '请求ID';
$lang->errorlog->account     = '账号';
$lang->errorlog->module      = '模块';
$lang->errorlog->method      = '方法';
$lang->errorlog->url         = '请求地址';
$lang->errorlog->level       = '错误级别';
$lang->errorlog->message     = '错误信息';
$lang->errorlog->file        = '文件';
$lang->errorlog->line        = '行号';
$lang->errorlog->trace       = '堆栈';
$lang->errorlog->createdDate = '发生时间';

$lang->errorlog->featureBar = array();
$lang->errorlog->featureBar['browse'] = array('all' => '全部');

$lang->errorlog->notice = new stdclass();
$lang->errorlog->notice->int = '『%s』应当是正整数。';

$lang->errorlog->levelList = array();
$lang->errorlog->levelList[E_ERROR]             = 'Fatal Error';
$lang->errorlog->levelList[E_WARNING]           = 'Warning';
$lang->errorlog->levelList[E_PARSE]             = 'Parse Error';
$lang->errorlog->levelList[E_NOTICE]            = 'Notice';
$lang->errorlog->levelList[E_CORE_ERROR]        = 'Core Error';
$lang->errorlog->levelList[E_CORE_WARNING]      = 'Core Warning';
$lang->errorlog->levelList[E_COMPILE_ERROR]     = 'Compile Error';
$lang->errorlog->levelList[E_COMPILE_WARNING]   = 'Compile Warning';
$lang->errorlog->levelList[E_USER_ERROR]        = 'User Error';
$lang->errorlog->levelList[E_USER_WARNING]      = 'User Warning';
$lang->errorlog->levelList[E_USER_NOTICE]       = 'User Notice';
$lang->errorlog->levelList[E_STRICT]            = 'Strict';
$lang->errorlog->levelList[E_RECOVERABLE_ERROR] = 'Recoverable Error';
$lang->errorlog->levelList[E_DEPRECATED]        = 'Deprecated';
$lang->errorlog->levelList[E_USER_DEPRECATED]   = 'User Deprecated';

$lang->resource->errorlog = new stdclass();
$lang->resource->errorlog->browse     = 'browse';
$lang->resource->errorlog->view       = 'view';
$lang->resource->errorlog->delete     = 'delete';
$lang->resource->errorlog->ajaxGetLog = 'ajaxGetLog';
$lang->resource->errorlog->setting    = 'setting';
$lang->resource->errorlog->deleteLog  = 'deleteLog';
