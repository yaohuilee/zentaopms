<?php
$lang->runner->manageRunner = '执行节点管理';
$lang->runner->browse       = '执行节点列表';
$lang->runner->create       = '添加执行节点';
$lang->runner->createGuide  = '添加执行节点引导页';
$lang->runner->edit         = '编辑执行节点';
$lang->runner->enable       = '启用';
$lang->runner->disable      = '停用';
$lang->runner->delete       = '删除执行节点';
$lang->runner->changeState  = '启用/停用执行节点';

$lang->runner->name       = '名称';
$lang->runner->status     = '状态';
$lang->runner->platOrArch = '平台/架构';
$lang->runner->plat       = '平台';
$lang->runner->arch       = '架构';
$lang->runner->version    = '版本';
$lang->runner->ip         = 'IP地址';
$lang->runner->package    = '安装包';
$lang->runner->cmd        = '部署命令';
$lang->runner->desc       = '描述';

$lang->runner->statusList = array();
$lang->runner->statusList['online']  = '在线';
$lang->runner->statusList['offline'] = '离线';
$lang->runner->statusList['suspend'] = '停用';

$lang->runner->osList = array();
$lang->runner->osList['linux']   = 'Linux';
$lang->runner->osList['windows'] = 'Windows';

$lang->runner->typeList = array();
$lang->runner->typeList['docker'] = 'Docker';
$lang->runner->typeList['k8s']    = 'Kubernetes';

$lang->runner->archList = array();
$lang->runner->archList['amd64'] = 'amd64';
$lang->runner->archList['arm64'] = 'arm64';

$lang->runner->cmdList = array();
$lang->runner->cmdList['windows'] = <<<EOF
# 1. 下载runner可执行文件
curl  --output "gitfox-runner.tar.gz" "%PACKAGE_URL%"

# 2. 解压缩可执行文件包

# 3. 安装服务
.\install.bat %GITFOX_URL% %GITFOX_TOKEN%
EOF;
$lang->runner->cmdList['linux'] = <<<EOF
# 1. 下载runner可执行文件到指定的路径
sudo curl --output "gitfox-runner.tar.gz" "%PACKAGE_URL%"

# 2. 解压缩可执行文件包到/usr/local/bin目录下
sudo tar -zxvf gitfox-runner.tar.gz -C /usr/local/bin

# 3. 安装服务
sudo gitfox-runner install --url=%GITFOX_URL% --token=%GITFOX_TOKEN%

# 4. 启动服务
sudo gitfox-runner start
EOF;
$lang->runner->cmdList['docker'] = <<<'EOF'
docker run -d --name gitfox-runner --restart always
  -v /srv/gitfox-runner/config:/etc/gitfox-runner
  -v /var/run/docker.sock:/var/run/docker.sock
  gitfox/gitfox-runner:latest
EOF;
$lang->runner->cmdList['k8s'] = <<<EOF
# 1. 添加仓库源
helm repo add gitfox https://hub.qucheng.com/chartrepo/stable

# 2. 安装
helm install --namespace <NAMESPACE> --name gitfox-runner -f <CONFIG_VALUES_FILE> gitfox/gitfox-runner
EOF;

$lang->runner->notice = new stdclass();
$lang->runner->notice->confirmDelete  = '您确定要删除该执行节点吗？';
$lang->runner->notice->confirmDisable = '您确定要停用该执行节点吗？';
$lang->runner->notice->disableDelete  = '在线状态的执行节点不可删除';
$lang->runner->notice->nameLength     = '名称不能超过200个字符。';
$lang->runner->notice->descLength     = '描述不能超过500个字符。';

$lang->runner->apiError = array();
