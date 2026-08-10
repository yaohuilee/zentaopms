<?php
declare(strict_types=1);
/**
 * The control file of runner module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     runner
 * @link        https://www.zentao.net
 */
class runner extends control
{
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('space')->setMenu();
    }

    /**
     * 浏览Runner列表。
     * browse Runner.
     *
     * @param  string $orderBy
     * @param  int $recPerPage
     * @param  int $pageID
     * @access public
     * @return void
     */
    public function browse(string $orderBy = '', int $recPerPage = 20, int $pageID = 1)
    {
        $this->app->loadClass('pager', true);
        $pager = new pager(0, $recPerPage, $pageID);

        $runnerList = $this->runner->getList($orderBy, $pager);

        foreach($runnerList as $runner)
        {
            $runner->runnerStatus = $runner->online == 'online' && $runner->status == 'disable' ? 'suspend' : $runner->online;
            $runner->platOrArch   = $this->lang->runner->osList[$runner->os] . '_' . $this->lang->runner->archList[$runner->arch];
        }

        $this->view->title      = $this->lang->runner->browse;
        $this->view->runnerList = $runnerList;
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->display();
    }

    /**
     * 创建Runner引导页
     * create Guide.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        $token = $this->loadModel('gitfox')->request('/runners/token');
        if(dao::isError()) $this->sendError(zget(dao::getError(), 'apiMessage', 'fail'));

        $this->view->title = $this->lang->runner->createGuide;
        $this->view->token = $token;
        $this->display();
    }

    /**
     * 编辑Runner。
     * Edit Runner.
     *
     * @param  int $runnerID
     * @access public
     * @return void
     */
    public function edit(int $runnerID)
    {
        $runner = $this->runner->fetchByID($runnerID);

        if($_POST)
        {
            $formData = form::data($this->config->runner->form->edit)
                ->setDefault('updatedBy', $this->app->user->account)
                ->get();

            if($formData->name == $runner->name && $formData->desc == $runner->desc)
            {
                return $this->sendSuccess(array('load' => true));
            }

            $this->runnerZen->checkFormData($formData);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->runner->update($runnerID, $formData);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->loadModel('action')->create('runner', $runnerID, 'edit');
            $this->sendSuccess(array('load' => true));
        }
        $this->view->title  = $this->lang->runner->edit;
        $this->view->runner = $runner;
        $this->display();
    }

    /**
     * 禁用/启用Runner。
     * change State.
     *
     * @param  int $runnerID
     * @param  string $state
     * @access public
     * @return void
     */
    public function changeState(int $runnerID, string $state)
    {
        $status = new stdClass();
        $status->status = $state;

        $this->runner->update($this->serverID, $runnerID, $status);
        if(dao::isError()) return $this->sendError(dao::getError());

        $this->loadModel('action')->create('runner', $runnerID, $state == 'enable' ? 'enabledRunner' : 'disabledRunner');
        $this->sendSuccess(array('load' => true));
    }

    /**
     * 删除Runner。
     * Delete Runner.
     *
     * @param  int $runnerID
     * @access public
     * @return void
     */
    public function delete(int $runnerID)
    {
        $this->runner->deleteRunner($this->serverID, $runnerID);
        if(dao::isError()) return $this->sendError(dao::getError());

        $this->loadModel('action')->create('runner', $runnerID, 'deleted');
        $this->sendSuccess(array('load' => true));
    }
}
