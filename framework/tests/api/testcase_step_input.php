<?php declare(strict_types=1);
/** Standalone API form normalization regression; no database required. */
require_once dirname(__DIR__, 2) . '/api/router.class.php';
class testcaseStepProbe extends api
{
    public function __construct() {}
    protected function sendV2Error(string $message): void { throw new Exception($message); }
    public function normalize(): void { $this->normalizeTestcaseStepInput(); }
}
class testcaseStepModel
{
    public string $moduleName = 'testcase';
    public array $steps = array();
    public function loadModel($name) { return $this; }
    public function getByID($id)
    {
        if($id !== 123) throw new Exception('Unexpected case ID');
        return (object)array('steps' => $this->steps);
    }
}
$probe = new testcaseStepProbe();
$probe->apiVersion = 'v2';
$probe->action = 'put';
$probe->methodName = 'edit';
$probe->params = array('caseID' => 123);
$probe->control = new testcaseStepModel();
$probe->control->steps = array(
    (object)array('name' => '1', 'desc' => 'Group', 'expect' => '', 'type' => 'group'),
    (object)array('name' => '1.1', 'desc' => 'Child & text', 'expect' => 'Expected', 'type' => 'item'),
    (object)array('name' => '2', 'desc' => 'Last', 'expect' => 'Done', 'type' => 'step'),
);
foreach(array(array('title' => 'Title'), array('precondition' => 'New condition'), array()) as $input)
{
    $_POST = $input;
    $probe->normalize();
    if($_POST['steps'] !== array(1 => 'Group', '1.1' => 'Child & text', 2 => 'Last')) throw new Exception('Lost steps');
    if($_POST['expects'] !== array(1 => '', '1.1' => 'Expected', 2 => 'Done')) throw new Exception('Lost expectations');
    if($_POST['stepType'] !== array(1 => 'group', '1.1' => 'item', 2 => 'step')) throw new Exception('Lost types');
    echo "PASS omitted steps preserve hierarchy\n";
}
foreach(array('put', 'post') as $action)
{
    $probe->action = $action;
    $probe->methodName = $action == 'put' ? 'edit' : 'create';
    $_POST = array('steps' => array('First', 'Second'));
    $probe->normalize();
    if($_POST['stepType'] !== array('step', 'step') || $_POST['expects'] !== array('', '')) throw new Exception('Missing defaults');
    echo "PASS defaults for $action\n";
}
$probe->action = 'put';
$probe->methodName = 'edit';
foreach(array(
    array('expects' => array('Orphan')),
    array('stepType' => array('step')),
    array('steps' => array('First', 'Second'), 'stepType' => array('step')),
    array('steps' => array('First', 'Second'), 'expects' => array('Expected')),
    array('steps' => array('First', 'Second'), 'stepType' => array(1 => 'step', 2 => 'step')),
    array('steps' => array('First'), 'stepType' => array('bad')),
    array('steps' => array('First'), 'stepType' => null),
    array('steps' => array(array('desc' => 'First'))),
    array('steps' => null),
) as $input)
{
    $_POST = $input;
    $rejected = false;
    try { $probe->normalize(); } catch(Exception $error) { $rejected = true; }
    if(!$rejected) throw new Exception('Invalid input accepted');
    echo "PASS invalid input rejected\n";
}
$probe->control->steps = array();
$_POST = array('title' => 'Empty original');
$probe->normalize();
if($_POST['steps'] !== array()) throw new Exception('Empty original changed');
echo "PASS empty original\n";
$probe->control->moduleName = 'task';
$_POST = array('steps' => 'unchanged');
$probe->normalize();
if($_POST !== array('steps' => 'unchanged')) throw new Exception('Other module changed');
echo "PASS other module unchanged\n";
