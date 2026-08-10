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
     * 通过API更新Runner信息。
     * update runner info by API.
     *
     * @param  int    $runnerID
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function update(int $runnerID, object $formData): bool
    {
        $this->dao->update(TABLE_RUNNER)->data($formData)
            ->autoCheck()
            ->batchCheck('name,labels', 'notempty')
            ->where('`id`')->eq($runnerID)
            ->exec();

        return !dao::isError();
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
     * 获取Runner标签。
     * Get runner labels.
     *
     * @param  string $status
     * @param  string $online
     * @access public
     * @return array
     */
    public function getLabels(string $status = '', string $online = ''): array
    {
        $runnerLabels = $this->dao->select('`labels`')->from(TABLE_RUNNER)
            ->where('`deleted`')->eq(0)
            ->beginIF($status)->andWhere('`status`')->eq($status)->fi()
            ->beginIF($online)->andWhere('`online`')->eq($online)->fi()
            ->fetchAll();
        if(empty($runnerLabels)) return array();

        $labels = array();
        foreach($runnerLabels as $runnerLabel)
        {
            if(empty($runnerLabel->labels)) continue;

            $runnerLabels = explode(',', $runnerLabel->labels);
            foreach($runnerLabels as $label)
            {
                if(in_array($label, $labels)) continue;
                $labels[$label] = $label;
            }
        }
        return array_filter($labels);
    }
}
