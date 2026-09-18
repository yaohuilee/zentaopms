<?php
/** Standalone regression: php framework/api/test/programprojectid.php */
require dirname(__DIR__) . '/router.class.php';

foreach(array('PROJECT', 'PRODUCT', 'PRODUCTPLAN', 'STORY', 'TASK', 'BUG', 'FEEDBACK', 'BUILD', 'CASE', 'USER', 'TICKET', 'DEPT', 'TESTTASK') as $table)
{
    if(!defined('TABLE_' . $table)) define('TABLE_' . $table, strtolower($table));
}

class programProjectIDTestAPI extends api
{
    public $fixtureType = 'program';
    public $allowed = true;
    public $exists = true;
    public $privilegeChecks = 0;

    public function __construct(string $appName = 'api', string $appRoot = '')
    {
        $this->apiVersion = 'v2';
        $this->action = 'get';
        $this->moduleName = 'program';
        $this->methodName = 'project';
        $this->params = array('programID' => 7);
        $this->control = new class
        {
            public function sendError($message) { throw new RuntimeException($message); }
        };
    }

    public function configure($key, $value) { $this->$key = $value; }
    public function checkObjectExists($table, $objectIDList)
    {
        return $this->exists ? array((object)array('id' => $objectIDList, 'type' => $this->fixtureType)) : false;
    }
    public function checkObjectPriv(object $object, string $table): bool
    {
        $this->privilegeChecks++;
        return $this->allowed;
    }
}

$_POST = array();
$cases = array(
    'valid program' => array(array(), null, 1),
    'project ID' => array(array('fixtureType' => 'project'), 'Program does not exist.', 0),
    'execution ID' => array(array('fixtureType' => 'sprint'), 'Program does not exist.', 0),
    'zero ID' => array(array('params' => array('programID' => 0)), 'Program does not exist.', 0),
    'missing ID' => array(array('params' => array()), 'Program does not exist.', 0),
    'nonexistent or deleted' => array(array('exists' => false), 'Program does not exist.', 0),
    'forbidden program' => array(array('allowed' => false), 'Program is not allowed.', 1),
    'other route unchanged' => array(array('methodName' => 'view', 'fixtureType' => 'project'), null, 1),
    'other version unchanged' => array(array('apiVersion' => 'v1', 'fixtureType' => 'project'), null, 1)
);
foreach($cases as $name => $case)
{
    list($settings, $expected, $checks) = $case;
    $app = new programProjectIDTestAPI();
    foreach($settings as $key => $value) $app->configure($key, $value);
    $actual = null;
    try { $app->checkAccess(); } catch(RuntimeException $error) { $actual = $error->getMessage(); }
    if($actual !== $expected || $app->privilegeChecks !== $checks)
    {
        fwrite(STDERR, "FAIL: $name\n");
        exit(1);
    }
    echo "PASS: $name\n";
}
