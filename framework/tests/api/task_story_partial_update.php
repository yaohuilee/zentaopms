<?php declare(strict_types=1);
/**
 * Standalone regression for API v2 task form merging; no database required.
 * Run: php framework/tests/api/task_story_partial_update.php
 * This tests setFormData, not persistence or the task model's version handling.
 */
require_once dirname(__DIR__, 2) . '/api/router.class.php';

class taskStoryTestApi extends api
{
    public string $body = '{}';
    public bool $accessChecked = false;
    public bool $denyAccess = false;
    public function __construct() {}
    protected function getRequestBody(): string { return $this->body; }
    protected function normalizeBatchPostData(): void {}
    protected function mergeRouteParamsToPost(): void {}
    protected function normalizeRouteParamsAfterPostMerge(): void {}
    protected function mergeWorkflowFields(): void {}
    public function checkAccess()
    {
        $this->accessChecked = true;
        if($this->denyAccess) throw new Exception('Access denied');
    }
}

class taskStoryTestControl
{
    public string $moduleName = 'task';
    public string $methodName = 'edit';
    public string $viewType = 'json';
    public bool $getFormData = false;
    public array $formData = array('story' => 0, 'name' => 'Existing task');
    public object $taskZen;
    public object|false $record;
    public int $reads = 0;
    public function __construct()
    {
        $this->record = (object)array('story' => 42);
        $this->taskZen = (object)array('getFormData' => false, 'formData' => array('story' => 0));
    }
    public function edit($taskID) {}
    public function create($taskID) {}
    public function loadModel($module) { return $this; }
    public function getByID($taskID)
    {
        if($taskID !== 123) throw new Exception('Unexpected task ID');
        $this->reads++;
        return $this->record;
    }
}

$cases = array(
    array('description only', array('desc' => 'Updated description'), 42, 42, 1),
    array('name only', array('name' => 'Updated name'), 42, 42, 1),
    array('empty body', array(), 42, 42, 1),
    array('already unlinked', array('desc' => 'Updated'), 0, 0, 1),
    array('explicit unlink', array('story' => 0), 42, 0, 0),
    array('explicit link', array('story' => 43), 42, 43, 0),
    array('explicit same link', array('story' => 42), 42, 42, 0),
    array('explicit null follows existing form behavior', array('story' => null), 42, 0, 0),
    array('other module unchanged', array('desc' => 'Updated'), 42, 0, 0, 'bug'),
    array('other version unchanged', array('desc' => 'Updated'), 42, 0, 0, 'task', 'v1'),
    array('other action unchanged', array('desc' => 'Updated'), 42, 0, 0, 'task', 'v2', 'post'),
    array('create unchanged', array('desc' => 'Updated'), 42, 0, 0, 'task', 'v2', 'put', 'create'),
);

function makeTaskStoryTestApi(array $payload): taskStoryTestApi
{
    $app = new taskStoryTestApi();
    $app->body = json_encode($payload);
    $app->apiVersion = 'v2';
    $app->action = 'put';
    $app->methodName = 'edit';
    $app->params = array('taskID' => 123);
    $app->control = new taskStoryTestControl();
    return $app;
}

foreach($cases as $case)
{
    [$name, $payload, $existing, $expected, $reads] = $case;
    $app = makeTaskStoryTestApi($payload);
    $app->control->record->story = $existing;
    $app->control->moduleName = $case[5] ?? 'task';
    $app->apiVersion = $case[6] ?? 'v2';
    $app->action = $case[7] ?? 'put';
    $app->methodName = $app->control->methodName = $case[8] ?? 'edit';
    ob_start();
    try { $app->setFormData(); } finally { ob_end_clean(); }
    if($_POST['story'] !== $expected || $app->control->reads !== $reads || !$app->accessChecked)
        throw new Exception("FAIL: $name");
    if(isset($payload['desc']) && $_POST['desc'] !== $payload['desc']) throw new Exception('Description changed');
    echo "PASS: $name\n";
}

foreach(array('missing task', 'access denied') as $name)
{
    $app = makeTaskStoryTestApi(array('desc' => 'Updated'));
    $app->control->record = false;
    $app->denyAccess = $name === 'access denied';
    $expected = $app->denyAccess ? 'Access denied' : 'Cannot preserve task story: task not found.';
    $message = '';
    ob_start();
    try { $app->setFormData(); } catch(Exception $error) { $message = $error->getMessage(); }
    finally { ob_end_clean(); }
    if($message !== $expected) throw new Exception("FAIL: $name");
    if($app->denyAccess && $app->control->reads !== 0) throw new Exception('Read before permission check');
    echo "PASS: $name\n";
}
