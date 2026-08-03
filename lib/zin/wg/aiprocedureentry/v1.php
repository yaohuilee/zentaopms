<?php
declare(strict_types=1);
namespace zin;

class aiprocedureEntry extends wg
{
    protected static array $defineProps = array(
        'objectType:string',
        'objectID:int',
    );

    protected function build(): ?node
    {
        global $app, $lang;

        $objectType = $this->prop('objectType');
        $objectID   = (int)$this->prop('objectID');
        if(empty($objectType) || empty($objectID)) return null;

        $app->loadLang('aiprocedure');
        $app->control->loadModel('aiprocedure');

        $modules    = $this->getModules($objectType);
        $procedures = $app->control->aiprocedure->getActiveProceduresByModule($modules);
        if(empty($procedures)) return null;

        $procedures = array_values($procedures);
        $procedures = array_map(function($procedure)
        {
            return array(
                'id'    => (int)$procedure->id,
                'name'  => $procedure->name,
                'steps' => array_map(function($step)
                {
                    return array(
                        'id'    => (int)$step->id,
                        'name'  => $step->name,
                        'agent' => (int)$step->agent,
                        'order' => (int)$step->order,
                    );
                }, $procedure->steps),
            );
        }, $procedures);

        $run         = $app->control->aiprocedure->getRunningProcedureRun($objectType, $objectID);
        $runningData = $run ? array((int)$run->procedure, (int)$run->currentStep) : null;

        return div
        (
            setClass('detail-sections canvas shadow rounded px-6 py-4'),
            zui::aiProcedureBlock
            (
                set::_id('aiProcedureEntry'),
                set::objectType($objectType),
                set::objectID($objectID),
                set::procedures($procedures),
                set::runningData($runningData),
                set::readonly(false),
                set::toolbar(true),
                set::labels(array(
                    'procedure'              => $lang->aiprocedure->common,
                    'view-procedure-history' => $lang->aiprocedure->viewHistory,
                )),
                set::urls(array(
                    'history' => helper::createLink('aiprocedure', 'history', "objectType={$objectType}&objectID={$objectID}"),
                )),
            )
        );
    }

    /**
     * 获取工序显示位置匹配列表。
     * Get matched procedure modules.
     *
     * @param  string $objectType
     * @return array
     */
    protected function getModules(string $objectType): array
    {
        $storyModules = array('story', 'requirement', 'epic');
        if(in_array($objectType, $storyModules)) return $storyModules;

        return array($objectType);
    }
}
