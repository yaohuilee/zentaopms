<?php
require __DIR__ . '/responsecontracts.php';
class bugBrowseTestAPI extends responseContractTestAPI
{
    public function validate() {$this->validateProductBugBrowseType();}
    public function parameters() {return $this->params;}
}
$app=new bugBrowseTestAPI();
$app->configure('moduleName','bug');$app->configure('methodName','browse');
$allowed=array('all','unclosed','unresolved','assigntome','openedbyme','assignedbyme','resolvedbyme','bysearch','customfilter');
$app->configure('config',(object)array('bug'=>(object)array('browseTypeList'=>$allowed)));
foreach(array('assignedtome','assigntome','ASSIGNEDTOME','ASSIGNTOME') as $value)
{
    $app->configure('params',array('browseType'=>$value));$app->validate();verifyContract($app->parameters()['browseType']==='assigntome','新旧拼写及大小写均归一化');
}
foreach($allowed as $value)
{
    $app->configure('params',array('browseType'=>$value));$app->validate();verifyContract($app->parameters()['browseType']===$value,'保留配置中的合法筛选含扩展');
}
foreach(array('unknown','',123,array('all')) as $value)
{
    $app->configure('params',array('browseType'=>$value));$rejected=false;
    try {$app->validate();} catch(RuntimeException $e) {$rejected=strpos($e->getMessage(),'不支持 browseType=')===0;}
    verifyContract($rejected,'非法参数明确拒绝');
}
$app->configure('params',array());$app->validate();verifyContract($app->parameters()===array(),'省略参数保留默认流程');
foreach(array(array('apiVersion','v1'),array('action','post'),array('moduleName','project'),array('methodName','view')) as $case)
{
    $app->configure('apiVersion','v2');$app->configure('action','get');$app->configure('moduleName','bug');$app->configure('methodName','browse');
    $app->configure($case[0],$case[1]);$app->configure('params',array('browseType'=>'unknown'));$app->validate();verifyContract(true,'非目标入口不改变行为');
}
