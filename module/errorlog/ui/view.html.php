<?php
declare(strict_types=1);
/**
 * The view view file of errorlog module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      ZenTao Team
 * @package     errorlog
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($this->lang->errorlog->view . ' #' . $log->id));

modalBody
(
    div
    (
        setClass('space-y-2'),
        div(setClass('flex gap-2'), div(setClass('w-24 flex-none text-muted'), $lang->errorlog->requestID), div(setClass('flex-auto break-all'), $log->requestID)),
        div(setClass('flex gap-2'), div(setClass('w-24 flex-none text-muted'), $lang->errorlog->module),   div(setClass('flex-auto break-all'), $log->module . ' / ' . $log->method)),
        div(setClass('flex gap-2'), div(setClass('w-24 flex-none text-muted'), $lang->errorlog->level),    div(setClass('flex-auto'), $log->levelName)),
        div(setClass('flex gap-2'), div(setClass('w-24 flex-none text-muted'), $lang->errorlog->account),  div(setClass('flex-auto break-all'), $log->account)),
        div(setClass('flex gap-2'), div(setClass('w-24 flex-none text-muted'), $lang->errorlog->url),      div(setClass('flex-auto break-all'), $log->url)),
        div(setClass('flex gap-2'), div(setClass('w-24 flex-none text-muted'), $lang->errorlog->createdDate), div(setClass('flex-auto'), $log->createdDate)),
        div(setClass('flex gap-2'), div(setClass('w-24 flex-none text-muted'), $lang->errorlog->message),  div(setClass('flex-auto break-all whitespace-pre-wrap'), $log->message)),
        div(setClass('flex gap-2'), div(setClass('w-24 flex-none text-muted'), $lang->errorlog->file),     div(setClass('flex-auto break-all'), $log->file . ':' . $log->line)),
        div(setClass('flex gap-2'), div(setClass('w-24 flex-none text-muted'), $lang->errorlog->trace),    div(setClass('flex-auto break-all whitespace-pre-wrap font-mono'), $log->trace))
    )
);

render();
