window.addItem = function(event)
{
    const obj     = $(event.target);
    const newLine = $('.labelsRow').eq(0).clone();

    newLine.addClass('newLine');
    newLine.find('.labels').remove();
    newLine.find('.newLabels').removeClass('hidden').val('');
    newLine.find('.delete-item').removeClass('hidden');

    obj.closest('.labelsRow').after(newLine);
}

window.removeItem = function(event)
{
    const obj = $(event.target);
    obj.closest('.labelsRow').remove();
    initCmd();
}

window.validateNewLabels = function(event)
{
    const input = $(event.target);
    const value = input.val();
    const invalidPattern = /[^a-zA-Z0-9_.\-一-龥]/g;

    input.next('.newLabels-error').remove();

    if(invalidPattern.test(value))
    {
        input.after('<span class="newLabels-error text-danger">' + newLabelsInvalidMsg + '</span>');
        $('button[type="submit"]').prop('disabled', true);
    }
    else
    {
        $('button[type="submit"]').prop('disabled', false);
    }
}

window.initCmd = function(event)
{
    if(event && event.type === 'input') validateNewLabels(event);
    const plat = $("[name='plat']").val();
    const arch = $("[name='arch']").val();
    if(!plat || !arch) return;

    /* Get selected labels from the multi-select picker. */
    const labelsVal = $('[name^="labels"]').val();
    const runtime   = runnerConfig.runtimeList[plat];
    const selectedLabels = labelsVal ? (Array.isArray(labelsVal) ? labelsVal : [labelsVal]) : [];

    /* Collect all non-empty and valid values from newLabels text inputs. */
    const invalidPattern = /[^a-zA-Z0-9_.\-一-龥]/;
    const newLabelValues = [];
    $('[name="newLabels[]"]').each(function()
    {
        const val = $(this).val();
        if(val && val.trim() && !invalidPattern.test(val.trim())) newLabelValues.push(val.trim());
    });

    /* Merge selected labels and new labels, deduplicate, and join as comma-separated string. */
    const allLabels = [...new Set([...selectedLabels, ...newLabelValues])];
    let labelsStr  = allLabels.join(',');
    if(plat != 'windows' && labelsStr != '')
    {
        labelsStr = '--labels=' + labelsStr;
    }

    const packageURL = `${runnerConfig.packageURL}${plat}_${arch}.tar.gz?t=${new Date().getTime()}`;
    let cmd = runnerConfig.cmdList[plat];
    cmd = cmd.replace('%PACKAGE_URL%', packageURL)
        .replace('%GITFOX_URL%', token.url)
        .replace('%GITFOX_TOKEN%', token.token)
        .replace('%RUNNER_RUNTIME%', runtime)
        .replace('%RUNNER_LABELS%', labelsStr);

    $('#cmd code').empty();
    $('#cmd code').text(cmd);
};
