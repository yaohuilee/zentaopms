<?php
/** 执行：php framework/api/test/browsetypevalidation.php。 */
require __DIR__ . '/responsecontracts.php';
class browseTypeValidationTestAPI extends responseContractTestAPI
{
    public function validateFilter() { $this->validateProjectBrowseType(); }
    public function parameters() { return $this->params; }
}
$app = new browseTypeValidationTestAPI();
foreach(array(array('project','browse'), array('program','project'), array('program','browse')) as $route)
{
    $app->configure('moduleName', $route[0]);$app->configure('methodName', $route[1]);
    foreach(array('my', 'unknown', '', 123, array('all')) as $value)
    {
        $app->configure('params', array('browseType' => $value));$rejected = false;
        try {$app->validateFilter();} catch(RuntimeException $error) {$rejected = strpos($error->getMessage(), '不支持 browseType=') === 0;}
        verifyContract($rejected, '未知值或错误类型明确拒绝');
    }
    foreach(array('all','wait','doing','suspended','closed','delayed','bysearch') as $value)
    {
        $app->configure('params', array('browseType' => $value));$app->validateFilter();
        verifyContract($app->parameters()['browseType'] === $value, '合法值不变');
    }
    $app->configure('params', array());$app->validateFilter();verifyContract($app->parameters() === array(), '省略参数不改默认流程');
    $app->configure('params', array('browseType'=>'ALL'));$app->validateFilter();verifyContract($app->parameters()['browseType'] === 'all', '保留大小写兼容');
}
$app->configure('moduleName','project');$app->configure('methodName','browse');
foreach(array('undone','unclosed','involved','review','reviewedby') as $value)
{
    $app->configure('params',array('browseType'=>$value));$app->validateFilter();verifyContract(true,'保留既有项目筛选分支');
}
$app->configure('moduleName','program');$app->configure('methodName','kanban');$app->configure('params',array('browseType'=>'my'));$app->validateFilter();verifyContract(true,'其他接口的 my 不受影响');
$app->configure('moduleName','project');$app->configure('methodName','browse');$app->configure('action','post');$app->validateFilter();verifyContract(true,'写接口不受影响');
$app->configure('action','get');$app->configure('apiVersion','v1');$app->validateFilter();verifyContract(true,'API v1 不受影响');
