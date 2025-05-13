## v1.0.5更新内容
* feat(backend/data/backup): 新增数据备份管理模块
* feat: 新增缓存配置和数据库配置文件
* feat: 新增数据备份和恢复命令
* chore(deps): 更新 composer 依赖并调整配置
* feat(数据备份&终端组件): 新增数据备份API、备份下载组件及终端组件功能改进
* feat(Terminal): 支持从系统配置加载自定义终端命令
* chore(config): 移除缓存配置并添加备份事件监听
* refactor(admin): 优化代码结构和功能
* chore: 更新CHANGELOG.md记录变更

**完整的更新日志**: https://gitee.com/rocareer/webman-radmin/compare/v1.0.4...v1.0.5
## v1.0.4更新内容

* refactor: 优化中间件逻辑和配置检查
* feat(member): 新增密码校验方法并优化异常处理
* chore(support/member/user): 完善 UserService 和 UserState 文件
* feat(Jwt): 优化 JWT 刷新逻辑并添加 TTL 方法
* feat: 为Request类添加admin路由适配功能
* chore(config): 更新迁移配置和路由等配置文件
* fix(exception): 修正未授权异常错误消息赋值问题
* chore(多模块): 重命名并迁移账户模块代码
* refactor(控制器): 统一响应类型为Response并调整返回值
* refactor(controller): 修改用户组关联命名及响应类型
* fix(routine/config): 修复配置编辑时的错误处理和语法问题
* fix(controller): 修复远程控制器路径处理逻辑
* refactor: 修改 axios 请求头 token 设置方式 改为标准的 Authentication 头
* fix(admin,api,middleware): 修复后台菜单提示、登录验证及中间件异常处理
* chore(member): 优化认证逻辑与辅助函数
* feat(jwt): 升级JWT组件并优化逻辑
* chore(exception): 统一异常类构造函数参数
* fix(middleware): 修改角色验证异常抛出方式
* feat(cache): 新增ThinkPHP缓存配置支持 支持Think-cache
* feat(routine/config): 新增鉴权管理配置与相关功能 在后台维护,不在配置文件硬编码
* docs: 更新日志链接
* chore: 项目依赖、配置及文档更新

**完整的更新日志**: https://gitee.com/rocareer/webman-radmin/compare/v1.0.3...v1.0.4

## v1.0.0-1.0.3更新内容

* 从Buildadmin 迁移大部分基础功能,适配Webman框架
* 基础工具和公共代码构建
* fix(member): 修正成员初始化服务中的 token 获取方式
* chore(build): 更新前端资源路径和环境变量配置
* refactor(控制器): 统一响应类型和移除冗余注释
* refactor(auth/controller): 为 AdminLog 和 Group 控制器方法添加返回类型声明
* chore(deps): 升级 rocareer 相关依赖版本
* docs: 新增中英文 README 文档及更新.gitignore
* chore(deps): 添加 radmin 仓库源
* test: 新增 PHPUnit 测试框架依赖
* chore(deps): 添加 webman-dev 到开发依赖
* chore: 更新启动脚本及 Windows 相关文件
* refactor: 重构AdminService类结构
* feat(member-service): 增加会员信息扩展及菜单权限管理功能
* fix(member model): 修正未授权用户的异常类型
* feat(member): 新增 extendMemberInfo 方法
* feat(user): 添加用户角色及配置
* feat(Request): 新增 Request controllerName 和 role 属性
* chore: 更新项目配置及依赖信息
* refactor(exception): 修改未授权异常状态码
* chore(config): 优化配置文件和环境变量支持
* refactor: 控制器方法增加返回类型和优化异常处理
* refactor(控制器): 统一控制器方法返回类型并移除测试控制器

**完整的更新日志**: https://gitee.com/rocareer/webman-radmin/compare/v1.0.0...v1.0.3
