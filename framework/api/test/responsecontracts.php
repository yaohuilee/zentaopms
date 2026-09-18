<?php
/** 执行：php framework/api/test/responsecontracts.php；不连接数据库。 */
require __DIR__ . '/programprojectid.php';

class responseContractTestAPI extends programProjectIDTestAPI
{
    public $fixtureManagers = array();
    public $fixtureFields = array();
    public $fixtureModulePath = '';
    public $requestedIDs = array();
    public $executionAllowed = false;
    public function getModulePath(string $appName = '', string $moduleName = '') { return $this->fixtureModulePath; }
    protected function sendV2Error(string $message): void { throw new RuntimeException($message); }
    protected function getManagerIdentities(array $ids): array { $this->requestedIDs = $ids; return $this->fixtureManagers; }
    public function identities($body) { return $this->appendManagerIdentity($body); }
    public function availability() { $this->validateAPIAvailability(); }
    public function review()
    {
        $this->dao = new class($this->fixtureFields) {
            private $fields;
            public function __construct($fields) { $this->fields = $fields; }
            public function descTable($table) { return $this->fields; }
        };
        $this->validateProjectReviewFilter();
    }
    public function checkObjectExists($table, $ids) { return array((object)array('id' => $ids, 'execution' => 8, 'type' => 'project')); }
    public function loadModel(string $moduleName, string $appName = ''): object|bool
    {
        return new class($this->executionAllowed) {
            private $allowed;
            public function __construct($allowed) { $this->allowed = $allowed; }
            public function checkPriv($id) { return $this->allowed; }
        };
    }
}
class missingModuleRouteTestAPI extends responseContractTestAPI
{
    public function parseRouteV2($routes) { return ''; }
    public function setModuleName(string $moduleName = '') { $this->moduleName = $moduleName; }
    public function setMethodName(string $methodName = '') { $this->methodName = $methodName; }
    public function setControlFile($exitIfNone = true) { throw new RuntimeException('不应先加载缺失控制器'); }
}
function verifyContract($condition, $name)
{
    if(!$condition) { fwrite(STDERR, "失败：$name\n"); exit(1); }
    echo "通过：$name\n";
}
function expectContractError($callback, $message)
{
    try { $callback(); } catch(RuntimeException $error) { return $error->getMessage() === $message; }
    return false;
}
$app = new responseContractTestAPI();
$app->configure('moduleName', 'project');
$app->fixtureManagers = array(7 => array('PMAccount' => 'tester', 'PMName' => '测试人', 'PMUserID' => 11), 8 => array('PMAccount' => '', 'PMName' => '', 'PMUserID' => 0));
$body = '{"status":"success","projects":[{"id":7,"PM":"测试人"},{"id":8,"PM":""}],"pager":{"recTotal":2}}';
$result = json_decode($app->identities($body));
verifyContract($result->projects[0]->PM === '测试人' && $result->projects[0]->PMAccount === 'tester' && $result->projects[0]->PMUserID === 11, '保留原字段并补充稳定身份');
verifyContract($result->projects[1]->PMUserID === 0 && $result->pager->recTotal === 2 && $app->requestedIDs === array(7,8), '空负责人和查询范围');
$keyed = json_decode($app->identities('{"status":"success","programs":{"7":{"id":7,"PM":"tester"}}}'));
verifyContract($keyed->programs->{7}->PM === 'tester' && $keyed->programs->{7}->PMName === '测试人', '键值对象保持结构');
$detail = json_decode($app->identities('{"status":"success","project":{"id":7,"PM":"tester"}}'));
verifyContract($detail->project->PMAccount === 'tester', '详情响应补齐身份');
foreach(array('', '{broken', '{"status":"fail"}', '{"status":"success","projects":[]}') as $raw) verifyContract($app->identities($raw) === $raw, '错误或空结果不重写');
$app->configure('action', 'post');verifyContract($app->identities($body) === $body, '不更改写接口');$app->configure('action', 'get');
$app->configure('moduleName', 'ticket');verifyContract(expectContractError(function() use($app) {$app->availability();}, '当前版本未提供此功能。'), '工单模块缺失明确拒绝');
$app->configure('moduleName', 'feedback');verifyContract(expectContractError(function() use($app) {$app->availability();}, '当前版本未提供此功能。'), '反馈模块缺失明确拒绝');
$app->fixtureModulePath = dirname(__DIR__, 3) . '/module/project/';$app->availability();verifyContract(true, '已安装模块不拦截');
$app->configure('moduleName', 'project');$app->configure('methodName', 'browse');$app->configure('params', array('browseType' => 'review'));
verifyContract(expectContractError(function() use($app) {$app->review();}, '当前版本不支持项目评审筛选。'), '评审字段缺失明确拒绝');
$app->fixtureFields = array('reviewers' => true, 'reviewstatus' => true);$app->review();verifyContract(true, '具备评审字段的版本不拦截');
$app->configure('params', array('browseType' => 'all'));$app->fixtureFields = array();$app->review();verifyContract(true, '普通筛选不受影响');
$app->configure('moduleName', 'bug');$app->configure('methodName', 'view');$app->configure('params', array('bugID' => 7));
verifyContract(expectContractError(function() use($app) {$app->checkAccess();}, '无权访问该 Bug 所属的执行。'), '执行无权限时终止 API');
$app->executionAllowed = true;$app->checkAccess();verifyContract(true, '有执行权限时保留原流程');
$routeApp = new missingModuleRouteTestAPI();
$routeApp->configure('path', '/tickets');$routeApp->configure('originRouteInfo', array());
$_SERVER['REQUEST_METHOD'] = 'GET';
verifyContract(expectContractError(function() use($routeApp) {$routeApp->routeV2(array());}, '当前版本未提供此功能。'), '实际路由在加载缺失控制器之前拒绝');
