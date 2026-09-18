<?php
/** 执行 php module/program/test/branchfilter.php；独立测试，无数据库写入。 */
class helper {public static $api = true; public static function isApiRequest() {return self::$api;}}
class program {}
require dirname(__DIR__) . '/zen.php';
class branchFixture
{
    public $rows;
    public function __construct()
    {
        foreach(array(array(1,0,'doing',',1,'),array(2,1,'closed',',1,2,'),array(3,0,'closed',',3,'),array(4,3,'doing',',3,4,'),array(5,0,'closed',',5,')) as $r)
            $this->rows[$r[0]] = (object)array('id'=>$r[0],'parent'=>$r[1],'status'=>$r[2],'path'=>$r[3],'type'=>$r[1] ? 'project' : 'program');
    }
    public function getTopByPath($path) {return (int)explode(',',trim($path,','))[0];}
    public function getList($status, $order, $type='', $ids=array(), $pager=null)
    {
        $rows = array_filter($this->rows, function($r) use($status,$type,$ids)
        {
            if($status != 'all' && !($status == 'unclosed' ? $r->status != 'closed' : in_array($r->status,explode(',',$status)))) return false;
            if($type == 'top' && ($r->parent || ($ids && !in_array($r->id,$ids)))) return false;
            if($type == 'child' && !in_array($this->getTopByPath($r->path),$ids)) return false;
            return true;
        });
        if($pager) {$pager->recTotal=count($rows);$pager->pageTotal=(int)ceil(count($rows)/$pager->recPerPage);$rows=array_slice($rows,($pager->pageID-1)*$pager->recPerPage,$pager->recPerPage,true);}
        return $rows;
    }
    public function getListBySearch($order,$param,$hasProject,$pager) {return array($this->rows[1]);}
}
class branchTestZen extends programZen
{
    public $view; public $program; public $lang;
    public function __construct() {$this->view=new stdClass();$this->program=new branchFixture();$this->lang=(object)array('program'=>(object)array('summary'=>'%s / %s'));}
    public function run($status,$page=1,$size=100)
    {
        $pager=(object)array('recPerPage'=>$size,'pageID'=>$page);
        $rows=$this->getProgramsByType($status,'id_asc',0,$pager);
        return array(array_keys($rows),$pager);
    }
}
function checkBranch($condition,$message) {if(!$condition) throw new RuntimeException($message);echo "通过：$message\n";}
$app=new branchTestZen();
list($ids,$pager)=$app->run('closed');checkBranch($ids===array(2,3,5),'关闭子节点不受进行中父节点遮蔽');checkBranch($pager->recTotal===3,'统计含匹配节点的顶层分支');
list($ids,$pager)=$app->run('doing');checkBranch($ids===array(1,4),'进行中子节点不受关闭父节点遮蔽');
list($ids,$pager)=$app->run('all');checkBranch($ids===array(1,2,3,4,5) && $pager->recTotal===3,'全部节点保留树形分页');
list($ids,$pager)=$app->run('closed',1,1);checkBranch($ids===array(2) && $pager->pageTotal===3,'第一页允许仅返回匹配子节点');
list($ids)=$app->run('closed',2,1);checkBranch($ids===array(3),'第二页无重复');
list($ids)=$app->run('closed',3,1);checkBranch($ids===array(5),'末页无遗漏');
list($ids)=$app->run('closed',4,1);checkBranch($ids===array(),'越界页不扩展为全量');
list($ids,$pager)=$app->run('wait');checkBranch($ids===array() && $pager->recTotal===0,'空匹配不扩展为全量');
list($ids)=$app->run('unclosed');checkBranch($ids===array(1,4),'未关闭筛选覆盖所有分支');
helper::$api=false;list($ids)=$app->run('closed');checkBranch($ids===array(3,5),'网页端沿用原顶层筛选');
helper::$api=true;list($ids)=$app->run('bysearch');checkBranch($ids===array(0),'保存搜索沿用原流程');
