<?php
declare(strict_types=1);
/**
 * The browse view file of runner module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     runner
 * @link        https://www.zentao.net
 */
namespace zin;
featureBar();

$canCreate  = hasPriv('runner', 'create');
$createLink = $this->createLink('runner', 'create');
$createItem = array('text' => $lang->runner->create, 'url' => $createLink, 'class' => 'primary', 'icon' => 'plus', 'data-toggle' => 'modal');
toolbar
(
    $canCreate ? item(set($createItem)) : null,
);

$cols       = $this->loadModel('datatable')->getSetting('runner');
$runnerList = initTableData($runnerList, $cols);
$urlParams  = array(
    'orderBy'    => '{name}_{sortType}',
    'recPerPage' => $pager->recPerPage,
    'pageID'     => $pager->pageID
);

jsVar('runnerLang', $lang->runner);
dtable
(
    set::customCols(true),
    set::cols($cols),
    set::data($runnerList),
    set::sortLink(createLink('runner', 'browse', $urlParams)),
    set::orderBy($orderBy),
    set::footPager(usePager()),
    set::onRenderCell(jsRaw('window.renderCell'))
);
