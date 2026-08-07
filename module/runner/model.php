<?php
declare(strict_types=1);
/**
 * The model file of runner module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     runner
 * @link        https://www.zentao.net
 */
class runnerModel extends model
{
    /**
     * 通过API获取Runner列表。
     * get runner list by API.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(string $orderBy = 'id_desc', ?object $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_RUNNER)
            ->where('`deleted`')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 判断按钮是否可点击。
     * Judge an action is clickable or not.
     *
     * @param  object $runner
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $runner, string $action): bool
    {
        $action = strtolower($action);

        if($action == 'edit')    return $runner->online == 'online';
        if($action == 'delete')  return $runner->online == 'offline';
        if($action == 'enable')  return $runner->online == 'online' && $runner->status == 'disable';
        if($action == 'disable') return $runner->online == 'online' && $runner->status == 'enable';

        return true;
    }

    /**
     * 通过API获取Runner信息。
     * get runner info by API.
     *
     * @param  int $gitfoxID
     * @param  int $runnerID
     * @access public
     * @return array|object
     */
    public function getRunner(int $gitfoxID, int $runnerID): array|object
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot($gitfoxID, false);
        $url = sprintf($apiRoot->url, '/runner/' . $runnerID);

        $result = json_decode(common::http($url, array(), array(), $apiRoot->header, 'json', 'GET'));
        if(empty($result) || isset($result->message)) return array();
        return $result;
    }

    /**
     * 通过API更新Runner信息。
     * update runner info by API.
     *
     * @param  int    $gitfoxID
     * @param  int    $runnerID
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function update(int $gitfoxID, int $runnerID, object $formData): bool
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot($gitfoxID, false);
        $url = sprintf($apiRoot->url, '/runner/' . $runnerID);
        if(isset($formData->status)) $url .= '/status';

        $result = json_decode(common::http($url, $formData, array(CURLOPT_CUSTOMREQUEST => 'PATCH'), $apiRoot->header, 'json', 'PATCH'));
        if(empty($result) || !isset($result->code) || $result->code != 0)
        {
            dao::$errors['message'] = $this->parseApiError(zget($result, 'message', ''));
            return false;
        }
        return true;
    }

    /**
     * 通过API删除Runner。
     * delete runner by API.
     *
     * @param  int $gitfoxID
     * @param  int $runnerID
     * @access public
     * @return bool
     */
    public function deleteRunner(int $gitfoxID, int $runnerID): bool
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot($gitfoxID, false);
        $url = sprintf($apiRoot->url, '/runner/' . $runnerID);

        $result  = json_decode(common::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $apiRoot->header, 'json', 'DELETE'));
        if($result && isset($result->message)) return false;
        return true;
    }

    /**
     * 解析API错误。
     * Parse API error.
     *
     * @param  string $error
     * @access public
     * @return string
     */
    public function parseApiError(string $error): string
    {
        if(!$error) $error = $this->lang->error->httpServerError;
        return $this->loadModel('pipeline')->convertApiError($error, $this->config->runner->apiError, $this->lang->runner->apiError);
    }
}
