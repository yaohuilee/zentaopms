# 禅道22.6原生测试执行 API / MCP

用户授权范围：补齐测试单原生用例关联及结果登记，部署本人云服务器。禅道任务65277、缺陷22130、复测用例33523、测试单853。原问题是工程登记探索发现，不把后补用例当作原发现来源。

## 修复原因

旧 `/testcases/{id}/results` POST 仅从查询字符串读取testtask/version，JSON中的同名字段被忽略，导致独立结果run=0。MCP的119项工具没有原生关联及执行结果入口。

## 接口

API v1/v2都提供：

| 路径 | 方法 | 用途 |
|---|---|---|
| `/testtasks/{id}/cases` | GET | 分页读取原生run，page/limit，limit最大100 |
| `/testtasks/{id}/cases` | POST | `cases:[{case,version}]`，1—100项，显式版本 |
| `/testtasks/{id}/cases/{caseID}/results` | GET | 只读取该任务run的历史结果 |
| `/testtasks/{id}/cases/{caseID}/results` | POST | `version,steps:[{result,real}]`完整步骤结果 |
| `/testcases/{id}/results` | GET/POST | 保留独立结果；JSON testtask/version实际生效 |

MCP对应六项 `get/post_testtasks_id_cases`、`get/post_testtasks_id_cases_caseID_results`、`get/post_testcases_id_results`。原119项保留，总125项。编号是顶层参数，业务数据放payload。返回新增resultID、runID、testtask、case、version，写后回读，超时先查询再决定是否重发。

## 约束

个人Token与原生方法权限、产品/项目/执行对象可见权限保持。未关联用例、不一致版本、冲突参数、关闭任务拒绝。关联批次先完整校验，同版本重复调用保留run ID和状态；不同已关联版本返回409，不隐式升级。任务行锁和事务序列化关联；结果写入锁用例行，返回本次真实ID，不直接改历史结果。组标题不算可执行步骤；完整、合法、等长的步骤才允许执行，不静默截断。不提供结果写入的自动重放。

业务写入仍调用22.6原生testtask模型/控制器，保留项目关联、动作历史、最后执行状态。该幂等约束针对新增API；旧网页关联操作未重新设计。执行证据payload不进入MCP日志。没有数据库迁移，不更改账户/权限或读取浏览器Cookie。

## 门禁与部署

PHP8.1目标运行时语法与24项实际入口边界断言通过；原项目集身份、响应契约及项目/Bug筛选回归通过。MCP fork及精确运行基线分别 `go test ./...`通过；新输入/输出Schema、六项工具定义及证据脱敏测试通过。独立应用/MCP预演容器和正式服务实际API/MCP闭环通过。额外真实负例：root-array/null、错误产品、v1/v2非法方法均明确拒绝。

原服务有PHP8.1兼容转换及已验证修复，不能用fork整个router文件覆盖。实际只套用[路由增量补丁](native-test-execution/22.6-php81-routing.patch)，[部署身份](native-test-execution/deployment.json)区分fork与运行叠加。MCP在已部署、此前可重现的源码上仅叠加payload日志保护与候选Schema，健康监听、个人Token转发、低权限及无公网端口保持；[制品身份](native-test-execution/runtime-manifest.json)保留SHA。

云端344MiB gzip数据库一致性备份通过完整性检查，并保存原范围代码/Schema/Compose；备份及会话仅在服务器私有目录，不进入Git。只重启PHP应用和重建MCP，20个其他容器身份保持。失败可恢复范围文件、旧Schema和旧MCP镜像；本次没有业务数据迁移，不自动回滚数据库覆盖后续用户写入。回退材料位于服务器 `/data/migration/zentao-native-runs-20261014/rollback`。

本批[实际验证摘要](native-test-execution/qualification.json)仅覆盖对应接口，不声称125项工具全部业务验收；第二个真实受限身份未单独执行，对象403由实际入口边界测试验证，无效Token线上401验证。预演/正式结构探针结果保留，正文注明protocol probe，不能替代报告审核专业验收；最终综合结果另登记。

## 最终补充复测

执行详情原工具回归发现三项汇总工时误标整数，缺陷22131；失败结果32759保持。Schema totalEstimate/totalConsumed/totalLeft改number，实际101.8小时及小数输入输出验证通过。最终综合用例结果32765、原生run27909、八项公网回归通过。

模型记录本次真正插入的结果ID，API据此精确查询，避免并发情况下按最新记录猜ID；不改变原createResult返回的业务结果。运行PHP8.1继续只叠加[模型增量补丁](native-test-execution/22.6-php81-result-id.patch)，不覆盖历史兼容转换。
