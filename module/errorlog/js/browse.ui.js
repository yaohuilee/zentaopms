/**
 * 根据勾选状态显示或隐藏批量删除按钮。
 * Toggle the batch delete button by checked state.
 */
window.toggleErrorlogBatchDelete = function()
{
    const checkedList = this && typeof this.getChecks === 'function' ? this.getChecks() : [];
    $('.errorlog-batch-delete').toggleClass('hidden', checkedList.length === 0);
};

/**
 * 批量删除错误日志。
 * Batch delete error logs.
 *
 * @param  object $event
 * @access public
 * @return void
 */
window.batchDeleteErrorlog = function(event)
{
    const dtable = zui.DTable.query(event.target);
    if(!dtable || !dtable.$) return;

    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    let $button = $(event.target);
    if(!$button.hasClass('batch-btn')) $button = $button.closest('.batch-btn');

    const postData = new FormData();
    checkedList.forEach(function(id) {postData.append('idList[]', id);});

    zui.Modal.confirm({message: $button.data('confirm'), icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then(function(res)
    {
        if(res) $.ajaxSubmit({url: $button.data('url'), data: postData});
    });
};
