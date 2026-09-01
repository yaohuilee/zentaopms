<?php
$lang->errorlog->common      = 'Fehlerprotokoll';
$lang->errorlog->browse      = 'Fehlerprotokolle durchsuchen';
$lang->errorlog->view        = 'Fehlerprotokoll anzeigen';
$lang->errorlog->setting     = 'Einstellungen';
$lang->errorlog->days        = 'Speichertage';
$lang->errorlog->info        = 'Fehlerprotokolle, die älter als die Aufbewahrungsfrist sind, werden gelöscht. Bitte aktivieren Sie die geplanten Aufgaben (Cron).';
$lang->errorlog->notFound    = 'Kein entsprechendes Fehlerprotokoll gefunden.';
$lang->errorlog->empty       = 'Noch keine Fehlerprotokolle.';

$lang->errorlog->requestID   = 'Anfrage-ID';
$lang->errorlog->account     = 'Konto';
$lang->errorlog->module      = 'Modul';
$lang->errorlog->method      = 'Methode';
$lang->errorlog->url         = 'URL';
$lang->errorlog->level       = 'Stufe';
$lang->errorlog->message     = 'Meldung';
$lang->errorlog->file        = 'Datei';
$lang->errorlog->line        = 'Zeile';
$lang->errorlog->trace       = 'Ablaufverfolgung';
$lang->errorlog->createdDate = 'Aufgetreten am';

$lang->errorlog->notice = new stdclass();
$lang->errorlog->notice->int = '『%s』sollte eine positive Zahl sein.';

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
$lang->resource->errorlog->ajaxGetLog = 'ajaxGetLog';
$lang->resource->errorlog->setting    = 'setting';
$lang->resource->errorlog->deleteLog  = 'deleteLog';
