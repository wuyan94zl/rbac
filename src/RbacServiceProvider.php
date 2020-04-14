<?php

namespace Eachdemo\Rbac;

use Illuminate\Support\ServiceProvider;

class RbacServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    	// 注册路由文件
        $this->loadRoutesFrom(__DIR__.'/route/rbac.php');
        // 注册数据库迁移文件
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        // 模型工厂
        $this->loadFactoriesFrom(__DIR__.'/database/factories');
    }
}
