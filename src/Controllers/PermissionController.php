<?php

namespace Eachdemo\Rbac\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Eachdemo\Rbac\Models\RbacPermission;
use Eachdemo\Rbac\Models\RbacMenu;
use Eachdemo\Rbac\Traits\ApiResponse;
use Illuminate\Support\Facades\Route;
use Log;

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
        return true;
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
        return $this->responseSucceed();
    }

    /**
     * 一件生成所有路由权限
     * @Author wuyan（466720682@qq.com）
     * @return [Boolean]
     */
    public function generateAllPermission(){
        $app = app();
        $routes = $app->routes->getRoutes();
        $actions = [];
        foreach ($routes as $k=>$value){
            if(isset($value->getAction()['controller'])) {
                $action = $value->getAction()['controller'];
                if(strstr($action, '@') && !strstr($action, 'Eachdemo\Rbac'))
                    $actions[] = $action;
            }
        }

        $has = RbacPermission::whereIn('action',$actions)->get()->toArray();
        $has = array_column($has,null, 'action');

        $create = [];
        $menu = RbacMenu::where('name','临时菜单')->first();
        foreach ($actions as $k => $v) {
            if(!isset($has[$v])){
                if(empty($menu)){
                    $menu = RbacMenu::create(['pid' => 0,'name' => '临时菜单','route' => '','display' => 0]);
                }
                $create[] = [
                    'menu_id'=>$menu->id,
                    'name'=>$v,
                    'action'=>$v,
                ];
            }
        }

        if(!empty($create)){
            RbacPermission::insert($create);
        }
        return $this->responseSucceed();
    }

}
