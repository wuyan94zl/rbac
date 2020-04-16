<?php

namespace Eachdemo\Rbac\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Eachdemo\Rbac\Models\RbacPermission;
use Eachdemo\Rbac\Models\RbacMenu;
use Eachdemo\Rbac\Models\RbacRoleHasMenuPermission;

use Eachdemo\Rbac\Traits\ApiResponse;
use Illuminate\Support\Facades\Route;

class PermissionController extends Controller
{

    use ApiResponse;

    /**
     * 创建权限
     * @Author wuyan（466720682@qq.com）
     * @param  Request
     * @return [Boolean]
     */
    public function create(Request $request){
    	$data = $request->validate([
            'name' => 'required|string',
            'action' => 'required|string',
            'menu_id' => 'required|integer',
        ]);
        RbacPermission::create($data);
        return $this->responseSucceed();
    }

    /**
     * 更新权限
     * @Author wuyan（466720682@qq.com）
     * @param  Request
     * @return [Boolean]
     */
    public function update($id, Request $request){
        $data = $request->validate([
            'name' => 'required|string',
            'action' => 'required|string',
            'menu_id' => 'required|integer',
        ]);
        RbacPermission::where('id',$id)->update($data);
        return $this->responseSucceed();
    }

    /**
     * 删除权限
     * @Author wuyan（466720682@qq.com）
     * @param  Request
     * @return [Boolean]
     */
    public function delete($id){
        RbacPermission::where('id',$id)->delete();
    	RbacRoleHasMenuPermission::where('id',$id)->where('type',1)->delete();
        return $this->responseSucceed();
    }

    /**
     * 一件生成所有路由权限
     * @Author wuyan（466720682@qq.com）
     * @return [Boolean]
     */
    public function generateAllPermission(){
        $actions = (new RbacPermission())->generateAllPermission();
        if(empty($actions))
            return $this->responseFailed('未找到权限路由');
        else
            return $this->responseSucceed();
    }

}
