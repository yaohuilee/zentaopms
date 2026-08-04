<?php
/**
 * The control file of zai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      tenghuaian <tenghuaian@chandao.com>
 * @link        https://www.zentao.net
 */
class zai extends control
{
    /**
     * 配置ZAI。
     * Configure ZAI.
     *
     * @param string $mode
     * @access public
     * @return void
     */
    public function setting($mode = 'view')
    {
        if(!empty($_POST))
        {
            $setting = new stdClass();
            $setting->appID      = trim($_POST['appID']);
            $setting->host       = trim($_POST['host']);
            $setting->port       = trim($_POST['port']);
            $setting->token      = trim($_POST['token']);
            $setting->adminToken = trim($_POST['adminToken']);

            if(empty($setting->host)) $setting = null;
            $this->zai->setSetting($setting);

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('zai', 'setting')));
        }

        $setting = $this->zai->getSetting(true);
        if($mode == 'view')
        {
            if(!empty($setting->token)) $setting->token = str_repeat('*', strlen($setting->token));
            if(!empty($setting->adminToken)) $setting->adminToken = str_repeat('*', strlen($setting->adminToken));
        }

        $this->view->title   = $this->lang->zai->setting;
        $this->view->setting = $setting;
        $this->view->mode    = $mode;
        $this->display();
    }

    /**
     * Ajax: 获取当前用户的ZAI Authorization token。
     * Ajax: Get ZAI Authorization Token of current user.
     *
     * @access public
     * @return void
     */
    public function ajaxGetToken()
    {
        return $this->send($this->zai->getToken());
    }

    /**
     * 禅道数据向量化。
     * Vectorized data of ZenTao.
     *
     * @access public
     * @return void
     */
    public function vectorized()
    {
        if(!empty($_POST))
        {
            $result = $this->zai->enableVectorization();
            if($result['result'] !== 'success') return $this->send(array('result' => 'fail', 'message' => $result['message']));
            return $this->send(array('result' => 'success', 'message' => $result['message'], 'load' => true));
        }

        $info       = $this->zai->getVectorizedInfo();
        $zaiSetting = $this->zai->getSetting();
        $syncTypes  = $this->zai->getSyncTypes();

        $status        = empty($zaiSetting) ? 'unavailable' : $info->status;
        $syncFailed    = ($status === 'synced' && empty($info->syncedCount) && !empty($info->syncFailedCount));
        $displayStatus = $syncFailed ? 'failed' : $status;

        $this->view->title         = $this->lang->zai->vectorized;
        $this->view->info          = $info;
        $this->view->zaiSetting    = $zaiSetting;
        $this->view->syncTypes     = $syncTypes;
        $this->view->status        = $status;
        $this->view->displayStatus = $displayStatus;
        $this->view->syncFailed    = $syncFailed;
        $this->view->progressList  = $this->zai->buildProgressList($info, $syncTypes);
        $this->view->lastSyncTime  = $info->syncTime ? date('Y-m-d H:i:s', (int)$info->syncTime) : '';
        $this->display();
    }

    /**
     * Ajax: 启用数据向量化。
     * Ajax: Enable data vectorization.
     *
     * @access public
     * @return void
     */
    public function ajaxEnableVectorization()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            return $this->send(array('result' => 'fail', 'message' => $this->lang->zai->onlyPostRequest));
        }

        $force  = isset($_POST['force']) && $_POST['force'] === 'true';
        $result = $this->zai->enableVectorization($force);
        return $this->send($result);
    }

    /**
     * 计划任务：自动同步向量化数据。
     * Cron: Auto sync vectorization data.
     *
     * @access public
     * @return void
     */
    public function syncVectorization()
    {
        $result = $this->zai->syncVectorization();
        if($result['result'] === 'skip')
        {
            echo "VECTORIZATION DISABLED\n";
            return;
        }

        echo "OK enqueued={$result['enqueued']} processed={$result['processed']}\n";
    }

    /**
     * Ajax: 搜索知识库。
     * Ajax: Search knowledge base.
     *
     * @param string $type 'chunk'（块） | 'content'（内容）
     * @param int    $limit
     * @access public
     * @return void
     */
    public function ajaxSearchKnowledges(string $type = 'chunk', int $limit = 5)
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            return $this->send(array('result' => 'fail', 'message' => $this->lang->zai->onlyPostRequest));
        }

        $userPrompt = zget($_POST, 'userPrompt', '');
        $filters    = json_decode(zget($_POST, 'filters', '{}'), true);

        if(empty($userPrompt) || empty($filters)) return $this->send(array('result' => 'fail', 'message' => $this->lang->fail));

        $knowledges = $this->zai->searchKnowledgesInCollections($userPrompt, $filters, $type, $limit, 0.5);
        $results    = [];
        foreach($knowledges as $knowledge)
        {
            if($type === 'chunk')
            {
                $results[] = ['key' => $knowledge['content_key'], 'similarity' => $knowledge['similarity'], 'id' => $knowledge['chunk_id'], 'content' => $knowledge['chunk_content'], 'attrs' => $knowledge['content_attrs']];
            }
            else
            {
                $results[] = ['key' => $knowledge['key'], 'similarity' => $knowledge['similarity'], 'id' => $knowledge['id'], 'content' => $knowledge['content'], 'attrs' => $knowledge['attrs']];
            }
            if(count($results) >= $limit) break;
        }

        return $this->send(array('result' => 'success', 'data' => $results));
    }

    /**
     * Ajax: 获取当前用户的ZAI agent。
     * Ajax: Get ZAI agent of current user.
     *
     * @access public
     * @return void
     */
    public function ajaxGetUserAgent()
    {
        $userAgent = $this->zai->getUserAgent();
        if(!$userAgent) $userAgent = $this->zai->createUserAgent($this->app->user->account);

        return $this->send(array('result' => 'success', 'data' => $userAgent));
    }
}
