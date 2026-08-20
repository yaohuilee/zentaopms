window.renderCell = function(result, {col, row})
{
    if(col.name === 'actions')
    {
        const actions = row.data.actions;
        actions.forEach((action) => {
            if(action.name === 'delete' && action.disabled == true) action.hint = runnerLang.notice.disableDelete;
        });

        if(typeof result[0].props.items != 'undefined' && result[0].props.items[0].icon == 'active')
        {
            delete result[0].props.items[0]['data-confirm'];
        }
    }

    if(col.name === 'runnerStatus')
    {
        const runnerStatus = row.data.runnerStatus;
        if(runnerStatus == 'offline')
        {
            result[0].props.class = result[0].props.class + ' text-danger';
        }
        else if(runnerStatus == 'online')
        {
            result[0].props.class = result[0].props.class + ' text-success';
        }
    }

    return result;
};
