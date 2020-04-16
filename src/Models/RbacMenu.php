<?php

namespace Eachdemo\Rbac\Models;

use Illuminate\Database\Eloquent\Model;
use Eachdemo\Rbac\Models\RbacPermission;
use Auth;
use Illuminate\Support\Facades\DB;

class RbacMenu extends Model
{

    protected $select = [];

    protected $fillable = [
        'id', 'pid', 'name','route','sort','icon','display'
    ];

    protected $hidden = [
        
    ];

    public function permissions(){
    	return $this->hasMany('Eachdemo\Rbac\Models\RbacPermission','menu_id');
    }

    public function addMenu($data){
        $menu = $this->create($data);
        if(isset($data['pid']) && $data['pid'] > 0){
            RbacPermission::where('menu_id',$data['pid'])->update(['menu_id'=>$menu->id]);
        }
    }

    /**
     * 树形菜单列表
     * @Author wuyan（466720682@qq.com）
     * @return [Array]
     */
    public function lists(){
    	$list = $this->with('permissions')->select('id','pid','name','route','icon','display')->orderBy('sort','desc')->get()->toArray();
    	$packData = array_column($list,null,'id');
        $tree = $this->treeArray($packData);
        return $tree;
    }

    /**
     * 菜单下拉选择
     * @Author wuyan（466720682@qq.com）
     * @return [Array]
     */
    public function selecteds(){
        $list = $this->select('id','pid','name','route','display')->orderBy('sort','desc')->get()->toArray();
        $packData = array_column($list,null,'id');
        $tree = $this->treeArray($packData);
        $selected = $this->setSelect($tree);
        return $selected;
    }

    /**
     * 树形菜单处理函数
     * @Author wuyan（466720682@qq.com）
     * @param  [type]                  $packData [原始数组]
     * @return [Array]
     */
    public function treeArray($packData, $parent_id = 'pid', $child_key = 'children'){
        $tree = [];
        foreach ($packData as $key => $val) {
            if ($val[$parent_id] == 0) {
                //代表跟节点, 重点一
                $tree[] = &$packData[$key];
            } else {
                //找到其父类,重点二
                $packData[$val[$parent_id]][$child_key][] = &$packData[$key];
            }
        }
        return $tree;
    }

    /**
     * 下拉数组等级处理
     * @Author wuyan（466720682@qq.com）
     * @param  [type]                  $data   [树形数组]
     * @param  array                   $select [下拉数组]
     * @param  integer                 $level  [当前级别]
     */
    private function setSelect($data,$level=0){
        $prefix = '';
        for ($i=0; $i < $level; $i++) { 
            $prefix .= '------';
        }
        foreach ($data as $k => $v) {
            $v['name'] = $prefix.$v['name'];
            $this->select[] = $v;
            if(isset($v['children'])){
                $childLevle = $level+1;
                $this->setSelect($v['children'],$childLevle);
            }
        }
        return $this->select;
    }

    /**
     * 菜单显示
     * @Author wuyan（466720682@qq.com）
     * @return [Array]
     */
    public function menuShow(){
        $admin = Auth::user();

        $systemMenu = [
            'icon' => 'el-icon-lx-settings',
            'index' => 'system',
            'title' => '系统设置',
            'subs' => [
                [
                    'icon' => 'el-icon-lx-more',
                    'index' => 'menu',
                    'title' => '菜单权限'
                ],
                [
                    'icon' => 'el-icon-lx-friendaddfill',
                    'index' => 'role',
                    'title' => '角色管理'
                ],
                [
                    'icon' => 'el-icon-lx-friendadd',
                    'index' => 'admin',
                    'title' => '用户管理'
                ],
            ]
        ];
        //DB关联查询
        $menu = $this->join('rbac_role_has_menu_permissions','rbac_role_has_menu_permissions.id','=','rbac_menus.id')
            ->join('rbac_roles','rbac_roles.id','=','rbac_role_has_menu_permissions.role_id')
            ->join('rbac_admin_has_roles','rbac_admin_has_roles.role_id','=','rbac_roles.id')
            ->where('rbac_admin_has_roles.admin_id',$admin->id)->where('rbac_role_has_menu_permissions.type',0)
            ->where('rbac_menus.display',1)
            ->select('rbac_menus.id','rbac_menus.pid','rbac_menus.name as title','rbac_menus.icon','rbac_menus.route as index')->get()->toArray();

        $menu = $this->treeArray(array_column($menu,null, 'id'),'pid','subs');
        if($admin->id === 1)
            array_unshift($menu,$systemMenu);
        return $menu;
    }
}
