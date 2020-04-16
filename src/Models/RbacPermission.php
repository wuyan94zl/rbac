<?php

namespace Eachdemo\Rbac\Models;

use Illuminate\Database\Eloquent\Model;

class RbacPermission extends Model
{
    protected $fillable = [
        'id', 'menu_id', 'name','action',
    ];

    protected $hidden = [
        
    ];

    public function generateAllPermission(){
    	$app = app();
        $routes = $app->routes->getRoutes();
        $actions = [];
        foreach ($routes as $k=>$value){
            $action = $value->getAction();
            if(isset($action['controller']) && isset($action['middleware']) && is_array($action['middleware'])) {
                if(in_array('eachdemo.rbac.permission', $action['middleware']) && !strstr($action['controller'], 'Eachdemo\Rbac')){
                    $actions[$value->uri] = $action['controller'];
                }
            }
        }
        if(empty($actions))
            return $actions;
        $has = RbacPermission::whereIn('action',$actions)->get()->toArray();
        $has = array_column($has,null, 'action');

        $create = [];
        $menu = RbacMenu::where('name','临时菜单')->first();
        foreach ($actions as $k => $v) {
            if(!isset($has[$v])){
                if(empty($menu)){
                    $menu = RbacMenu::create(['pid' => 0,'name' => '临时菜单','route' => '','sort'=>9999,'display' => 0]);
                }
                $create[] = [
                    'menu_id'=>$menu->id,
                    'name'=>$k,
                    'action'=>$v,
                ];
            }
        }

        if(!empty($create)){
            RbacPermission::insert($create);
        }
        return $create;
    }

}
