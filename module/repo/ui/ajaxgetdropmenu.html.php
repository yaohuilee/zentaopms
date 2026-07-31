<?php
declare(strict_types=1);
/**
 * The head switcher view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

$data = array('product' => array_values($repoGroup['product']), 'space' => array_values($repoGroup['space']));

$tabs = array();
$tabs[] = array('name' => 'space', 'text' => $lang->space->common);
$tabs[] = array('name' => 'product', 'text' => $lang->product->common);

$json = array();
$json['data']       = $data;
$json['tabs']       = $tabs;
$json['searchHint'] = $lang->searchAB;
$json['labelMap']   = array('product' => $lang->product->common, 'space' => $lang->space->common);
$json['link']       = array('repo' => sprintf($link, '{id}'));
$json['itemType']   = 'repo';

renderJson($json);
