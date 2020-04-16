<?php

namespace Eachdemo\Rbac\Models;

use Illuminate\Database\Eloquent\Model;
use Eachdemo\Rbac\Models\RbacMenu;
use Eachdemo\Rbac\Models\RbacPermission;

class RbacRoleHasMenuPermission extends Model
{
    protected $fillable = [
        'role_id', 'type', 'id'
    ];

    protected $hidden = [
        
    ];

    /**
     * 获取角色菜单及权限
     * @Author wuyan（466720682@qq.com）
     * @return [Array]
     */
    public function getMenuPermissionForRole($id){
    	$rbacMenuModel = new RbacMenu();
    	$all_menu_permission = $rbacMenuModel->with('permissions:id,menu_id,name')->select('id','pid','name')->get()->toArray();
    	$has_all = $this->where('role_id',$id)->get()->toArray();
    	$has_permission = [];
    	foreach ($has_all as $k => $v) {
    		if($v['type'] > 0){
    			$has_permission[] = $v['id']*1000000;
    		}
    	}
    	$merge = $this->formatRoleMenuPermission($all_menu_permission);
    	$packData = array_column($merge,null,'id');
    	$tree_data = $rbacMenuModel->treeArray($packData);
    	return ['tree_data'=>$tree_data,'tree_has'=>$has_permission];
    }

    /**
     * 获取角色菜单及权限
     * @Author wuyan（466720682@qq.com）
     * @return [Array]
     */
    public function setMenuPermissionForRole($id, $permissions){
    	$ids = [];
    	foreach ($permissions as $key => $value) {
    		$ids[] = $value/1000000;
    	}
    	$permissions = RbacPermission::find($ids)->toArray();
    	
    	$permission_id = [];
    	$menu_id = [];
    	$add_has_menu_permission = [];
    	$add_item = ['role_id' => $id];
    	foreach ($permissions as $k => $v) {
    		$permission_id[] = $v['id'];
    		$add_item['type'] = 1;
    		$add_item['id'] = $v['id'];
    		$add_has_menu_permission[] = $add_item;

    		if(!in_array($v['menu_id'],$menu_id)){
    			$menu_id[] = $v['menu_id'];
    		}
    	}
    	$menu_id = $this->getAllParentMenu($menu_id);
    	foreach ($menu_id as $k => $v) {
    		$add_item['type'] = 0;
    		$add_item['id'] = $v;
    		$add_has_menu_permission[] = $add_item;
    	}
    	RbacRoleHasMenuPermission::where('role_id',$id)->delete();
    	return RbacRoleHasMenuPermission::insert($add_has_menu_permission);
    }

    private function getAllParentMenu($menu_id){
    	$roles = RbacMenu::get()->toArray();
    	$all_id = [];
    	rep:
    	$pid = [];
    	foreach ($roles as $k => $v) {
    		if(in_array($v['id'], $menu_id)){
    			$pid[] = $v['pid'];
    		}
    	}
    	$all_id = array_merge($all_id,$menu_id);
    	if(!empty($pid)) {
    		$menu_id = $pid;
    		goto rep;
    	}
    	return $all_id;
    }

    private function formatRoleMenuPermission($all_menu_permission){
    	foreach ($all_menu_permission as $k => $v) {
            $all_menu_permission[$k]['type'] = 'menu';
    		$all_menu_permission[$k]['name'] .= '（菜单）';
    		if(isset($v['permissions'])){
    			foreach ($v['permissions'] as $pk => $pv) {
    				$v['permissions'][$pk]['id'] = $pv['id']*1000000;
                    $v['permissions'][$pk]['type'] = 'permission';
    				$v['permissions'][$pk]['name'] .= '（权限）';
    			}
    			$all_menu_permission[$k]['children'] = $v['permissions'];
    			unset($all_menu_permission[$k]['permissions']);
    		}
    	}
    	return $all_menu_permission;
    }
}
