## v1.0.7更新内容
* chore(plugin/radmin): 更新配置文件、移除token配置并添加Helper函数
* refactor(src/Install.php): 移除安装配置文件引用
* feat(radmin): 新增响应头 If-Modified-Since 检查功能
* feat(radmin/request): 新增请求处理功能
* feat(radmin/log): 添加适配器 radmin应用的日志类
* feat(radmin/helper): 新增类名解析函数parseClass
* feat(radmin): 新增容器适配器 适配 Webman容器
* chore(plugin/radmin): 移除ThinkLang和ThinkOrm类
* chore(radmin): 修改模型和ORM的依赖引入及初始化逻辑
* refactor(plugin/radmin): 重命名并迁移多语言管理类
* feat(radmin-cache): 新增基于 think-cache 的适配器
* fix(member): 使用Http类获取登录IP和User-Agent
* refactor: 优化会员服务类实现
* chore(plugin/radmin): 修改获取IP和User-Agent的方式
* feat(plugin/radmin/support/member): 更新成员系统Facade方法及注释
* refactor(radmin): 修改认证器中获取IP的方式
* feat(admin): 使用Http类获取请求成员ID
* fix(plugin/radmin): 修正JwtService缓存类引用路径
* refactor: 简化请求中间件逻辑
* refactor(radmin): 修改RequestContextMiddleWare依赖注入
* refactor: 移除RadminXSS过滤中间件
* refactor: 优化RadminAuthMiddleware中间件逻辑
* feat(radmin/middleware): 新增中间件接口定义
* fix(radmin/middleware): 修正控制器名称获取方式
* feat(radmin): 引入Request和Response支持
* refactor(plugin/radmin): 修正中间件类名拼写错误
* fix(plugin/radmin/extend/ba): 修正 ClickCaptcha 的命名空间引用
* fix(plugin/radmin/exception): 修正异常处理中的Response引用
* chore(radmin): 修改缓存路径配置
* chore(plugin/radmin/config): 更新终端配置占位符取值方式
* chore(plugin/radmin): 删除 server.php 配置文件
* fix(plugin/radmin): 修正中间件类名拼写错误
* chore(radmin): 修改日志文件存储路径
* refactor(plugin/radmin): 重构容器配置并迁移依赖
* chore: 优化代码结构及依赖管理
* chore(migration): 添加数据管理相关表及菜单规则迁移
* chore(config): 移除容器配置文件
* feat(plugin/radmin): 新增orm安装检查及调整请求类结构
* chore(token): 修改Token驱动命名空间
* feat(plugin/radmin): 支持查询缓存标签和构造函数条件初始化
* refactor: 优化成员服务架构及依赖注入
* chore(public): 更新资源引用路径并添加assets忽略规则

**完整的更新日志**: https://gitee.com/rocareer/webman-radmin/compare/v1.0.6...v1.0.7

## v1.0.6更新内容

* plugin(radmin): 替换数据库连接类并优化安装流程
* chore: 替换 Db 类为统一 ORM 封装类 Rdb
* refactor: 统一配置路径至插件命名空间
* chore(database): 迁移文件命名调整及数据库操作类替换
* feat: 添加容器配置及相关依赖注入
* chore(radmin-config): 调整终端配置、数据库配置及视图组件
* chore(radmin): 清理无用配置并调整部分参数
* refactor: 调整radmin插件配置及依赖
* chore(plugin/radmin): 更新异常处理配置路径及静态资源引用
* chore(radmin-middleware): 清理无用中间件并调整配置引用
* chore: 移除401、404等错误页面及相关组件
* feat(radmin): 新增日志和响应支持类
* chore(radmin): 修改语言包和ORM配置来源
* feat(radmin-orm): 新增ThinkORM支持插件
* refactor: 重构会员系统架构与依赖管理
* chore(plugin-radmin): 替换数据库操作类及添加上下文管理器
* fix(plugin/radmin): 修正头像配置路径引用
* refactor: 重构AdminModel及服务类数据库操作
* chore(plugin/radmin): 新增 backup 目录 .gitignore 文件
* chore(.env-example): 调整环境变量格式并修正字段名
* feat(安装模块): 新增文件安装并添加备份机制
* chore(composer): 更新依赖及项目元信息
* chore(plugin): 新增 webman-radmin 插件配置及相关中间件命名空间调整
* chore(namespace): 统一命名空间为 plugin\radmin\app\admin
* chore(radmin): 修正异常类的命名空间
* refactor: 将控制层和模型层的依赖从app/common迁移至plugin/radmin
* refactor: 更新缓存组件引用路径
* refactor: 替换 Log 引用路径统一使用 support\Log
* refactor: 统一异常类引入路径 为新 plugin 路径
* chore(plugin/radmin): 更新控制器和服务支持路径
* chore(src): 改为 webman plugin 方式
* chore(env): 移除环境变量示例文件
* refactor: 移除admin插件
* chore: 删除 rocareer 插件相关配置文件
* chore: 移除 radmin 插件配置及相关文件，调整自动加载和引导文件
* config: 新增环境变量示例配置文件

**完整的更新日志**: https://gitee.com/rocareer/webman-radmin/compare/v1.0.5...v1.0.6

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
