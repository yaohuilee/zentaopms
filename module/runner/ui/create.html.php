<?php
declare(strict_types=1);
/**
 * The create view file of runner module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     runner
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title($title),
    set::titleClass('panel-title text-lg')
);
h::css('.codeBody {background: #eee; border: 1px solid #aaa; padding: 0.4em 0.8em; font-size: 12px; overflow: auto; color: #000; white-space: pre-wrap; word-wrap: break-word;}');
jsVar('runnerConfig', $config->runner);
jsVar('token', $token);

form
(
    set::actions(''),
    on::change('#arch')->call('initCmd'),
    on::change('#plat')->call('initCmd'),
    on::init('#arch')->do('$(function() {setTimeout(initCmd, 50); });'),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->runner->plat),
        set::id('plat'),
        set::name('plat'),
        set::required(true),
        set::items($lang->runner->osList),
        set::value('linux')
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->runner->arch),
        set::id('arch'),
        set::name('arch'),
        set::required(true),
        set::items($lang->runner->archList),
        set::value('amd64')
    ),
    formGroup
    (
        setID('cmd'),
        set::label($lang->runner->cmd),
        h::pre
        (
            setClass('codeBody'),
            h::code()
        )
    )
);
