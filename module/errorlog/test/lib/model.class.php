<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class errorlogModelTest extends baseTest
{
    protected $moduleName = 'errorlog';
    protected $className  = 'model';

    /**
     * 测试获取错误日志列表。
     * Test get error log list.
     *
     * @param  string $query
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return array
     */
    public function getListTest(string $query = '', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1): array
    {
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $logs = $this->instance->getList($query, $orderBy, $pager);
        if(dao::isError()) return dao::getError();
        return $logs;
    }

    /**
     * 测试根据ID获取错误日志。
     * Test get error log by id.
     *
     * @param  int $id
     * @access public
     * @return object|array
     */
    public function getByIDTest(int $id): object|array
    {
        $log = $this->instance->getByID($id);
        if(dao::isError()) return dao::getError();
        return $log;
    }

    /**
     * 测试根据请求ID获取错误日志。
     * Test get error log by request id.
     *
     * @param  string $requestID
     * @access public
     * @return object|array
     */
    public function getByRequestIDTest(string $requestID): object|array
    {
        $log = $this->instance->getByRequestID($requestID);
        if(dao::isError()) return dao::getError();
        return $log;
    }

    /**
     * 测试删除过期错误日志。
     * Test delete overdue error logs.
     *
     * @param  string $date
     * @access public
     * @return bool|array
     */
    public function deleteByDateTest(string $date): bool|array
    {
        $result = $this->instance->deleteByDate($date);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
