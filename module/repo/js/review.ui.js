window.renderRepobugList = function (result, {col, row, value})
{
    if(col.name == 'entry')
    {
        result[0] = {html: '<span class="label primary mr-1">' + row.data.lines + '</span><a href="' + row.data.link + '" data-app="' + appTab + '">' + row.data.entry + '</a>'};
        return result;
    }

    return result;
}

$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('bugIdList[]', id));

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data:form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
});
