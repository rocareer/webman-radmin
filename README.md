# 关于 Webman-Radmin 
 * 高效华丽的前后端分离管理平台
 * 
## 项目背景

Webman生态作为高性能PHP框架的代表，长期以来缺乏专业的前后端分离解决方案。现有后台管理系统多采用传统模板引擎或简单的Layui前端，难以满足现代化开发需求。为此，我们整合了：

- **Webman**：高性能PHP后端框架
- **Buildadmin**：目前PHP生态最优秀的前后端分离后台系统
## 预览
<img alt="控制面板预览" src="https://v10.rocareer.com/static/images/preview/s_dashboard_1.png" title="控制面板预览"/>

## 版本和版本说明
- **当前版本**：v1.0.6 预览
- **发布时间**：2025年05月16日
- **更新日志**：[更新日志](https://gitee.com/rocareer/webman-radmin/blob/master/CHANGELOG.md)
- **不兼容更新**：目前是早期版本，后续发布的版本将不兼容更新,直到2.0版本发布

## 技术架构

### 前端部分
- **完整采用** Buildadmin 前端项目（未经修改）
- 基于 Vue3 + ElementPlus 的现代化UI
- TypeScript + Vite 构建
- 完整保留Buildadmin所有优秀特性

### 后端部分
- 基于Webman框架重构
- 借鉴Buildadmin优秀设计理念
- 适配Webman高性能特性
- 保留ThinkPHP兼容层

## 项目特点
**这是一个 Webman 插件 现版本不支持独立运行**
### 1. 前后端分离
- Webman生态中为数不多的专业级前后端分离方案
- 提供开箱即用的管理平台解决方案
- 
- **前端**：完整保留Buildadmin优秀实现
    - 可视化CRUD生成
    - 精美ElementPlus组件
    - 完善的权限管理
- **后端**：Webman高性能加持
    - 常驻内存架构
    - 支持超高并发
    - 毫秒级响应

### 2. 开发便捷
- 前端零改造直接使用
- 熟悉的Buildadmin开发模式
- Webman的高效调试支持
- 完整的开发文档

## 特别说明

本项目特别感谢Buildadmin项目的杰出贡献：

- 前端部分**完全使用**Buildadmin原始代码
- 后端设计**大量借鉴**Buildadmin架构
- 权限系统**完整迁移**Buildadmin实现


**Buildadmin** 是目前PHP生态中：

✅ 完善的前后端分离方案  
✅ 优雅的Vue3实现  
✅ 专业的后台管理系统

## 技术对比

| 特性        | Radmin  | 传统Webman方案 |
|------------|---------|----------------|
| 前端框架    | Vue3+TS | Layui/jQuery   |
| 开发模式    | 前后端分离   | 服务端渲染     |


## 快速开始

### 前端使用
```bash
# 直接使用Buildadmin前端
cd web
npm install
npm run dev
```

### 后端配置
```php
  // 安装 Webman
  composer create-project workerman/webman "YourProjectName" 
  // 安装 Radmin 插件
  composer require rocareer/webman-radmin

```


## 技术栈（持续更新）

### 前端技术栈
- **核心框架**: Vue 3.3+ (Composition API)
- **UI组件库**: Element Plus 2.3+
- **构建工具**: Vite 4.0+
- **包管理**: pnpm 8.0+ , npm 8.0+
- **状态管理**: Pinia 2.0+
- **路由**: Vue Router 4.0+
- **HTTP客户端**: Axios 1.0+
- **工具链**:
    - TypeScript 5.0+
    - ESLint + Prettier
    - Stylelint
    - Commitlint
- **测试工具**:
    - Vitest
    - Cypress组件测试
- **Package**:
```json
{
  "dependencies": {
    "@element-plus/icons-vue": "2.3.1",
    "@vueuse/core": "12.0.0",
    "axios": "1.7.9",
    "echarts": "5.5.1",
    "element-plus": "2.9.1",
    "font-awesome": "4.7.0",
    "lodash-es": "4.17.21",
    "mitt": "3.0.1",
    "nprogress": "0.2.0",
    "pinia": "2.3.0",
    "pinia-plugin-persistedstate": "4.2.0",
    "qrcode.vue": "3.6.0",
    "screenfull": "6.0.2",
    "sortablejs": "1.15.6",
    "v-code-diff": "1.13.1",
    "vue": "3.5.13",
    "vue-i18n": "11.1.3",
    "vue-router": "4.5.0"
  }
}
```

### 后端技术栈
- **核心框架**: Webman 2.1+
- **PHP版本**: 8.1+
- **数据库**:
    - MySQL 8.0+ (默认Think-ORM)
    - PostgreSQL 15+ (可选)
- **缓存系统**:
    - Redis 7.0+
    - Webman/Cache (默认)
    - APCu (本地缓存)
- **消息队列**:
    - Webman/Redis-Queue (后续支持)
    - AMQP (RabbitMQ兼容) (后续支持)
- **API文档**:
    暂无 (后续更新)
- **安全组件**:
    - Token认证 (支持JWT,MySQL,Redis,Webman/Cache)
    - CORS中间件
    - CSRF防护 (开发中)
    - 请求速率限制 (开发中)
- **Composer**
```json
{
  "require": {
    "php": ">=8.1",
    "workerman/webman-framework": "^2.1",
    "webman/event": "^1.0",
    "webman/cache": "^2.1",
    "webman/console": "^2.1",
    "monolog/monolog": "^2.0",
    "rocareer/webman-migration": "^v1.0.0",
    "rocareer/webman-status-code": "^v1.0.0",
    "firebase/php-jwt": "^6.11",
    "webman/think-orm": "^2.1",
    "vlucas/phpdotenv": "^5.6",
    "nelexa/zip": "^4.0",
    "ext-bcmath": "*",
    "ext-iconv": "*",
    "ext-pdo": "*",
    "ext-gd": "*",
    "phpmailer/phpmailer": "^v6.8.1",
    "webman/think-cache": "^2.1"
  }
}
```

## 开发工具链
- **IDE推荐**: PHPStorm or VSCode & WebStorm
- **调试工具**:
    - Xdebug 3.0+
    - Chrome Vue Devtools



## 版权声明

Rocareer Team 商业及内部专有项目，不适用开源许可证。版权所有 © Rocareer Team，作者：albert@rocareer.com。


## 致谢

特别感谢Buildadmin项目作者及贡献者：  
🔹 提供了如此优秀的前端实现  
🔹 开创了PHP前后端分离的新范式  
🔹 无私的开源精神值得尊敬

## 资源链接

- [Buildadmin官网](https://www.buildadmin.com)
- [Webman文档](https://www.workerman.net/doc/webman)
- [仓库Github](https://github.com/rocareer/webman-radmin)
- [仓库Gitee](https://gitee.com/rocareer/webman-radmin)
