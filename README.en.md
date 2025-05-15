# About Webman-Radmin (Preview 1.0.6)
* An efficient and elegant front-end and back-end separation management platform

## Project Background

As a representative of high-performance PHP frameworks, the Webman ecosystem has long lacked a professional front-end and back-end separation solution. Existing back-end management systems mostly use traditional template engines or simple Layui front ends, which are difficult to meet modern development needs. Therefore, we have integrated:

- **Webman**: A high-performance PHP back-end framework
- **Buildadmin**: The best front-end and back-end separation back-end system in the current PHP ecosystem

## Preview
<img alt="Dashboard Preview" src="https://v10.rocareer.com/static/images/preview/s_dashboard_1.png" title="Dashboard Preview"/>

## Version and Release Notes
- **Current Version**: v1.0.6 Preview
- **Release Date**: May 16, 2025
- **Changelog**: [Changelog](https://gitee.com/rocareer/webman-radmin/blob/master/CHANGELOG.md)
- **Incompatible Updates**: This is the start version, and subsequent releases will be incompatible until version 2.0 is released.

## Technical Architecture

### Front-end
- **Fully adopts** the Buildadmin front-end project (unaltered)
- Modern UI based on Vue3 + ElementPlus
- Built with TypeScript + Vite
- Fully retains all excellent features of Buildadmin

### Back-end
- Reconstructed based on the Webman framework
- Draws on excellent design concepts from Buildadmin
- Adapts to Webman's high-performance features
- Retains ThinkPHP compatibility layer

## Project Features

### 1. Front-end and Back-end Separation
- One of the few professional front-end and back-end separation solutions in the Webman ecosystem
- Provides an out-of-the-box management platform solution

- **Front-end**: Fully retains the excellent implementations of Buildadmin
  - Visual CRUD generation
  - Beautiful ElementPlus components
  - Comprehensive permission management
- **Back-end**: Enhanced by Webman's high performance
  - Resident memory architecture
  - Supports ultra-high concurrency
  - Millisecond-level response

### 2. Convenient Development
- Front-end can be used directly with zero modifications
- Familiar Buildadmin development model
- Efficient debugging support from Webman
- Complete development documentation

## Special Notes

This project particularly thanks the outstanding contributions of the Buildadmin project:

- The front-end part **completely uses** the original code of Buildadmin
- The back-end design **heavily references** the Buildadmin architecture
- The permission system **fully migrated** the Buildadmin implementation

**Buildadmin** is currently:

✅ A complete front-end and back-end separation solution  
✅ An elegant Vue3 implementation  
✅ A professional back-end management system

## Technical Comparison

| Feature       | Radmin    | Traditional Webman Solution |
|---------------|-----------|------------------------------|
| Front-end Framework | Vue3+TS  | Layui/jQuery                |
| Development Model | Front-end and Back-end Separation | Server-side Rendering      |


## Quick Start

### Front-end Usage
```bash
# Directly use the Buildadmin front end
cd web
npm install
npm run dev
```

### Back-end Configuration
```php
  // Create-project Webman
  composer create-project workerman/webman "YourProjectName" 
  // Install Radmin 
  composer require rocareer/webman-radmin

```

## Tech Stack (Continuously Updated)

### Front-end Tech Stack
- **Core Framework**: Vue 3.3+ (Composition API)
- **UI Component Library**: Element Plus 2.3+
- **Build Tool**: Vite 4.0+
- **Package Management**: pnpm 8.0+, npm 8.0+
- **State Management**: Pinia 2.0+
- **Routing**: Vue Router 4.0+
- **HTTP Client**: Axios 1.0+
- **Toolchain**:
  - TypeScript 5.0+
  - ESLint + Prettier
  - Stylelint
  - Commitlint
- **Testing Tools**:
  - Vitest
  - Cypress component testing
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

### Back-end Tech Stack
- **Core Framework**: Webman 2.1+
- **PHP Version**: 8.1+
- **Database**:
  - MySQL 8.0+ (default Think-ORM)
  - PostgreSQL 15+ (optional)
- **Caching System**:
  - Redis 7.0+
  - Webman/Cache (default)
  - APCu (local cache)
- **Message Queue**:
  - Webman/Redis-Queue (support in the future)
  - AMQP (RabbitMQ compatible) (support in the future)
- **API Documentation**:
  None (to be updated later)
- **Security Components**:
  - Token Authentication (supports JWT, MySQL, Redis, Webman/Cache)
  - CORS Middleware
  - CSRF Protection (in development)
  - Request Rate Limiting (in development)
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

## Development Toolchain
- **Recommended IDE**: PHPStorm or VSCode & WebStorm
- **Debugging Tools**:
  - Xdebug 3.0+
  - Chrome Vue Devtools

## Copyright Statement

Licensed under the Apache 2.0 License: https://gitee.com/rocareer/radmin/blob/master/LICENSE

## Acknowledgments

Special thanks to the authors and contributors of the Buildadmin project:  
🔹 For providing such an excellent front-end implementation  
🔹 For pioneering a new paradigm of front-end and back-end separation in PHP  
🔹 For the selfless spirit of open source that deserves respect

## Resource Links

- [Buildadmin Official Website](https://www.buildadmin.com)
- [Webman Documentation](https://www.workerman.net/doc/webman)
- [Project Repository](https://gitee.com/rocareer/radmin)