<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRbacPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rbac_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->default(0)->comment('权限对应的菜单id');
            $table->string('name')->default('')->comment('权限名称');
            $table->string('action')->default('')->comment('权限action（控制器和函数名如：RbacController@lists）');
            $table->timestamps();

            $table->index('menu_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rbac_permissions');
    }
}
