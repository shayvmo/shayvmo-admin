## shayvmo-admin

#### 仓库地址

gitee: [https://gitee.com/shayvmo/shayvmo-admin](https://gitee.com/shayvmo/shayvmo-admin)

github: [https://github.com/shayvmo/shayvmo-admin](https://github.com/shayvmo/shayvmo-admin)

#### 介绍
基于laravel6.x的后台基础管理系统，采用MVC方式搭建。

非常感谢「 [Pear-Admin-Layui](https://gitee.com/pear-admin/Pear-Admin-Layui) 」，以及 「 [Pear-Admin-Laravel](https://gitee.com/pear-admin/Pear-Admin-Laravel) 」 提供的开源项目

本项目基于这两个项目进行修改，但实际上，本项目仅使用了上述两个项目的整体框架，内嵌页采用引入 `Vue` 和` ElementUI `。

这一架构的起初是因为作者想要使用 `Vue` 和` ElementUI `，但苦于单兵作战，完全前后端分离比较消耗精力，于是就有了现在这个项目的架构。


#### 软件架构
laravel + layui + elementui + vue


#### 安装教程

【配置伪静态】
```
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

1、安装依赖的composer包
```shell script
composer install
```

2、访问` http://域名/install `，即可设置数据库地址。

设置完成后，运行上述2个步骤即可，安装成功后会自动跳转到后台登录页面。

> 后台登录地址：http://域名/admin/user/login

> 账号密码：admin 123456

> 注意：开源demo每天会定时运行脚本重置数据库。

demo地址：[https://demo.shayvmo.cn](https://demo.shayvmo.cn) 

【注】

1、本地开发部署时，安装成功后，可以直接登录访问后台，默认 admin 123456

2、清除安装锁命令: ` php artisan install:clean `

3、生成注释串，无需带前缀: ` php artisan gma admin`

4、可视化日志管理路由  `http://域名/log-viewer`

5、手动清除 permission 缓存

```php
php artisan cache:forget spatie.permission.cache
```

6、备份

```php
# 备份
php artisan backup:run

# 只备份数据库
php artisan backup:run --only-db

# 只备份文件
php artisan backup:run --only-files

# 清理备份
php artisan backup:clean
```

7、执行任务调度
```
* * * * * cd /your-project-path && php artisan schedule:run >> /dev/null 2>&1
```

#### 使用说明

暂无

#### 规划功能

2022年2月15日
- [ ] !!!重写版本
    - [ ] 权限放到数组文件
    - [ ] 配置项抽离到数据库表json
    - [ ] 富文本
  
延后：
- [ ] 上传设置优化，上传方式：本地，七牛云，阿里云
- [ ] 引入[spatie/laravel-activitylog](https://spatie.be/docs/laravel-activitylog/v4/introduction)

---

- [x] 上传组件demo
  
- [x] 富文本demo

- [x] 邮件demo

- [x] 启动同步数据库配置



#### 参与贡献

代码提交备注说明

- `!` 修复BUG

- `+` 新增功能

- `*` 普通修改

例子：

`[!] 修复BUG`

`[+] 新增功能`

`[*] 修改xxx`

#### 交流

QQ群： 634619533

微信群：

![qrcode.png](qrcode.png)


#### 其他

资源控制器
```php
php artisan make:controller PostController --resource
```
