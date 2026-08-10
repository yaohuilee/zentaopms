<?php
declare(strict_types=1);
/**
 * The edit view file of runner module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     runner
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::id('runnerEditForm'),
    set::title($title),
    set::submitBtnText($lang->save),
    on::click('.add-item', 'addItem'),
    on::click('.delete-item', 'removeItem'),
    on::input('[name="newLabels[]"]', 'validateNewLabels'),
    formGroup
    (
        set::name('name'),
        set::width('1/2'),
        set::label($lang->runner->name),
        set::required(true),
        set::value(zget($runner, 'name', ''))
    ),
    formRow
    (
        setClass('labelsRow'),
        formGroup
        (
            setClass('labels'),
            set::width('1/2'),
            set::label($lang->runner->labels),
            set::name('labels'),
            set::items($labels),
            set::value(zget($runner, 'labels', '')),
            set::multiple(true)
        ),
        formGroup
        (
            setClass('hidden newLabels'),
            set::label(''),
            set::width('1/2'),
            set::name('newLabels[]')
        ),
        formGroup
        (
            btnGroup
            (
                item(set(array('icon' => 'plus',  'class' => 'add-item ghost'))),
                item(set(array('icon' => 'trash', 'class' => 'delete-item hidden ghost'))),
            )
        )
    ),
    formGroup
    (
        set::name('desc'),
        set::label($lang->runner->desc),
        set::control(array('type' => 'textarea', 'rows' => '4')),
        set::value(zget($runner, 'desc', ''))
    )
);
