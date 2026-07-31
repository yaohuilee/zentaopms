<?php
declare(strict_types=1);
/**
 * The files view file of ppm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     ppm
 * @link        https://www.zentao.net
 */
namespace zin;
$dropMenus = array();
if(common::hasPriv('repo', 'download')) $dropMenus[] = array('text' => $this->lang->repo->downloadDiff, 'icon' => 'download', 'url' => $this->repo->createLink('download', "repoID={$ppm->repoID}&path=$currentEntry&fromRevision=$oldRevision&toRevision=$newRevision&type=path"), 'target' => '_self');

$dropMenus[] = array('text' => $this->lang->repo->viewDiffList['inline'], 'icon' => 'snap-house', 'id' => 'inline', 'class' => 'inline-appose');
$dropMenus[] = array('text' => $this->lang->repo->viewDiffList['appose'], 'icon' => 'col-archive', 'id' => 'appose', 'class' => 'inline-appose');

$domBox = empty($diffs) ? p(setClass('detail-content'), $lang->ppm->noChanges) : div(
    setID('diff-sidebar-left'),
    div
    (
        set::id('fileTabs'),
        tabs
        (
            set::id('monacoTabs'),
            set::className('relative'),
            div(setStyle(array('position' => 'absolute', 'width' => '100%', 'height' => '35px', 'background' => '#efefef', 'top' => '0px'))),
            tabPane
            (
                set::title($fileInfo['basename']),
                set::active(true),
                set::key('tab-' . str_replace('=', '-', $currentEntry)),
                to::suffix
                (
                    icon
                    (
                        'close',
                        set::className('monaco-close')
                    )
                ),
                div(set::id('tab-' . $currentEntry))
            ),
            btn
            (
                setClass('btn ghost square absolute z-10 pull-right text-black'),
                setStyle('right', '40px'),
                setStyle('top', '5px'),
                icon('fullscreen'),
                set::url('javascript:toggleFilesFullscreen();')
            ),
            dropdown
            (
                set::arrow(false),
                set::staticMenu(true),
                btn
                (
                    setClass('ghost text-black pull-right absolute top-0 right-0 z-10 monaco-dropmenu'),
                    set::icon('ellipsis-v rotate-90')
                ),
                set::items
                (
                    $dropMenus
                )
            ),
            div(set::className('absolute top-0 left-0 z-20 arrow-left btn-left'), icon('chevron-left')),
            div(set::className('absolute top-0 right-0 z-20 arrow-right btn-right'), icon('chevron-right'))
        )
    ),
    sidebar
    (
        set::maxWidth(800),
        treeEditor
        (
            set::id('monacoTree'),
            set::items($tree),
            set::canSplit(false),
            set::collapsedIcon('folder'),
            set::expandedIcon('folder-open'),
            set::normalIcon('file-text-alt'),
            set::selected($currentEntry),
            set::defaultNestedShow(true),
            set::onClickItem(jsRaw('window.treeClick'))
        )
    ),
    on::click('.inline-appose')->call('inlineAppose'),
    on::click('#monacoTabs .monaco-close')->call('closeTab', jsRaw('this')),
    on::click('#monacoTabs .menu-item a')->call('changeDiffType', jsRaw('this')),
    a(set::className('iframe'), setData('size', '1200px'), setData('toggle', 'modal'), set::id('linkObject'))
);
