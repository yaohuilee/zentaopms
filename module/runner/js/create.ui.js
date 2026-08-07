window.initCmd = function()
{
    const plat = $("[name='plat']").val();
    const arch = $("[name='arch']").val();
    if(!plat || !arch) return;

    const packageURL = `${runnerConfig.packageURL}${plat}_${arch}.tar.gz?t=${new Date().getTime()}`;
    let cmd = runnerConfig.cmdList[plat];
    cmd = cmd.replace('%PACKAGE_URL%', packageURL).replace('%GITFOX_URL%', token.url).replace('%GITFOX_TOKEN%', token.token);

    $('#cmd code').empty();
    $('#cmd code').text(cmd);
};
