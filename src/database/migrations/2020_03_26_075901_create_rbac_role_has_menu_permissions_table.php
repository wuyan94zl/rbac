<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRbacRoleHasMenuPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rbac_role_has_menu_permissions', function (Blueprint $table) {
            $table->integer('role_id')->comment('角色id');
            $table->tinyInteger('type')->default(0)->comment('类型：0对应菜单，1对应权限');
            $table->integer('id')->comment('对应id，type为0则对应菜单id，type为1则对应权限id');

            $table->index('role_id');
            $table->index('type');
            $table->index('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rbac_role_has_menu_permissions');
    }
}
