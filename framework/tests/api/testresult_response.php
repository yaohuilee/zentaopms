<?php declare(strict_types=1);
/** Standalone endpoint response regression with stub persistence; no database. */
class entry
{
    public object $requestBody;
    public bool $fail = false;
    public function param($key, $default = 0) { return $default; }
    public function loadController($module, $method)
    {
        return new class { public function runCase($run, $case, $version) {} };
    }
    public function loadModel($module)
    {
        return new class { public function getByID($id) { return (object)array('version' => 1); } };
    }
    public function setPost($key, $value) {}
    public function getData() { return (object)array('result' => $this->fail ? 'fail' : 'success', 'message' => 'validation error'); }
    // Mirror helper::response's empty-data rule to catch the original regression.
    public function send($code, $data) { return !empty($data) ? json_encode($data) : ''; }
    public function sendError($code, $message) { return json_encode(array('error' => $message)); }
}
require dirname(__DIR__, 3) . '/api/v1/entries/testresults.php';
class testresultResponseProbe extends testresultsEntry
{
    public function getStepIDList($task, $case, $version) { return array(0, array(1, 2)); }
}
$probe = new testresultResponseProbe();
$probe->requestBody = (object)array('steps' => array(
    (object)array('result' => 'pass', 'real' => 'First'),
    (object)array('result' => 'fail', 'real' => 'Second'),
));
$response = $probe->post(123);
if(json_decode($response, true) !== array('status' => 'success')) throw new Exception('Success must return valid JSON');
echo "PASS successful save response\n";
$probe->fail = true;
$response = $probe->post(123);
if(json_decode($response, true) !== array('error' => 'validation error')) throw new Exception('Failure must not report success');
echo "PASS validation failure response\n";
