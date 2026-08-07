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

    return result;
};
