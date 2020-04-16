<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRbacMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rbac_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->default(0)->comment('上级菜单id');
            $table->string('name')->default('')->comment('菜单名称');
            $table->string('route')->default('')->comment('菜单路由');
            $table->integer('sort')->default(0)->comment('排序，越大越靠前');
            $table->string('icon')->default('')->comment('菜单icon图标');
            $table->tinyInteger('display')->default(0)->comment('菜单是否显示');
            $table->timestamps();

            $table->index('pid');
            $table->index('sort');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rbac_menus');
    }
}
