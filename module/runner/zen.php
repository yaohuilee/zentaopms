<?php
declare(strict_types=1);
/**
 * The zen file of runner module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     runner
 * @link        https://www.zentao.net
 */
class runnerZen extends runner
{
    /**
     * 构建查询参数。
     * Build params.
     *
     * @param  string    $orderBy
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @access protected
     * @return array
     */
    protected function buildParams(string $orderBy = '', int $recPerPage = 20, int $pageID = 1): array
    {
        if($orderBy)
        {
            list($sort, $order) = explode('_', $orderBy);
            if($sort == 'runnerStatus') $sort = 'online-status';
            if($sort == 'platOrArch')   $sort = 'os-arch';
        }

        $param = array();

        if(!empty($sort))  $param['sort']  = $sort;
        if(!empty($order)) $param['order'] = $order;
        $param['page']  = $pageID;
        $param['limit'] = $recPerPage;

        return $param;
    }

    /**
     * 检查表单提交的合法性。
     * Check formData.
     *
     * @param  object    $formData
     * @access protected
     * @return bool
     */
    protected function checkFormData(object $formData)
    {
        if(mb_strlen($formData->name) > 200)
        {
            dao::$errors['name'] = $this->lang->runner->notice->nameLength;
        }

        if(mb_strlen(strip_tags($formData->desc)) > 500)
        {
            dao::$errors['desc'] = $this->lang->runner->notice->descLength;
        }

        return !dao::isError();
    }
}
