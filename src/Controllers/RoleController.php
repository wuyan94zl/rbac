<?php

namespace Eachdemo\Rbac\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Eachdemo\Rbac\Models\RbacRole;
use Eachdemo\Rbac\Models\RbacRoleHasMenuPermission;
use Eachdemo\Rbac\Models\RbacAdminHasRole;

use Eachdemo\Rbac\Traits\ApiResponse;

class RoleController extends Controller
{

    use ApiResponse;
 	
 	/**
     * 创建角色
     * @Author wuyan（466720682@qq.com）
     * @param  Request
     * @return [Boolean]
     */   
    public function create(Request $request){
    	$data = $request->validate([
            'name' => 'required|string',
            'remark' => 'required|string',
        ]);
        RbacRole::create($data);
        return $this->responseCreated();
    }

    /**
     * 更新角色
     * @Author wuyan（466720682@qq.com）
     * @param  Request
     * @return [Boolean]
     */
    public function update($id, Request $request){
        $data = $request->validate([
            'name' => 'required|string',
            'remark' => 'required|string',
        ]);
        RbacRole::where('id',$id)->update($data);
        return $this->responseCreated();
    }

    /**
     * 删除角色
     * @Author wuyan（466720682@qq.com）
     * @param  Request
     * @return [Boolean]
     */
    public function delete($id){
    	$role = RbacRole::find($id);
        if(RbacRoleHasMenuPermission::where('role_id',$id)->first()){
            return $this->responseFailed('请先清空角色权限');
        }
        if(RbacAdminHasRole::where('role_id',$id)->first()){
            return $this->responseFailed('请先删除用户该角色');
        }
        RbacRole::where('id',$id)->delete();
        return $this->responseSucceed();
    }

    /**
     * 角色列表
     * @Author wuyan（466720682@qq.com）
     * @return [Array]
     */
    public function lists(){
        $roles = RbacRole::paginate(10);
        return $this->responseSucceed($roles);
    }

    /**
     * 所有角色选择
     * @Author wuyan（466720682@qq.com）
     * @return [Array]
     */
    public function checkBoxList(){
        $roles = RbacRole::select('id','name')->get();
        return $this->responseSucceed($roles);
    }

    /**
     * 角色包含菜单权限
     * @Author wuyan（466720682@qq.com）
     * @return [Array]
     */
    public function hasMenuPermission($id){
        $has_menu_permission = (new RbacRoleHasMenuPermission())->getMenuPermissionForRole($id);
        return $this->responseSucceed($has_menu_permission);
    }

    /**
     * 设置角色菜单权限
     * @Author wuyan（466720682@qq.com）
     * @param  Request
     * @return [Boolean]
     */
    public function setMenuPermission($id, Request $request){
        $data = $request->validate(['permissions'=>'array']);
        $set_menu_permission = (new RbacRoleHasMenuPermission())->setMenuPermissionForRole($id,$data['permissions']);
        return $this->responseSucceed($set_menu_permission);
    }

}
