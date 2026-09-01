<?php
$lang->errorlog->common      = 'Journal des erreurs';
$lang->errorlog->browse      = 'Parcourir les journaux d\'erreurs';
$lang->errorlog->view        = 'Afficher le journal d\'erreurs';
$lang->errorlog->setting     = 'Paramètres';
$lang->errorlog->days        = 'Jours de conservation';
$lang->errorlog->info        = 'Les journaux d\'erreurs dépassant la durée de conservation seront supprimés. Veuillez activer les tâches planifiées (Cron).';
$lang->errorlog->notFound    = 'Aucun journal d\'erreurs correspondant trouvé.';
$lang->errorlog->empty       = 'Aucun journal d\'erreurs pour le moment.';

$lang->errorlog->requestID   = 'ID de requête';
$lang->errorlog->account     = 'Compte';
$lang->errorlog->module      = 'Module';
$lang->errorlog->method      = 'Méthode';
$lang->errorlog->url         = 'URL';
$lang->errorlog->level       = 'Niveau';
$lang->errorlog->message     = 'Message';
$lang->errorlog->file        = 'Fichier';
$lang->errorlog->line        = 'Ligne';
$lang->errorlog->trace       = 'Trace';
$lang->errorlog->createdDate = 'Survenu le';

$lang->errorlog->notice = new stdclass();
$lang->errorlog->notice->int = '『 %s 』 devrait être un entier positif.';

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
