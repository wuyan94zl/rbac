<?php

namespace Eachdemo\Rbac\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Eachdemo\Rbac\Models\RbacMenu;
use Eachdemo\Rbac\Models\RbacPermission;
use Eachdemo\Rbac\Traits\ApiResponse;

class MenuController extends Controller
{

    use ApiResponse;

    /**
     * 创建菜单
     * @Author wuyan（466720682@qq.com）
     * @param  Request
     * @return [Boolean]
     */
    public function create(Request $request){
        $data = $request->validate([
            'name' => 'required|string',
            'route' => 'required|string',
            'display' => 'integer',
            'sort' => 'integer',
            'icon' => 'string',
            'pid' => 'integer',
        ]);
        (new RbacMenu())->addMenu($data);
        return $this->responseCreated();
    }

    /**
     * 更新菜单
     * @Author wuyan（466720682@qq.com）
     * @param  Request
     * @return [Boolean]
     */
    public function update($id, Request $request){
        $data = $request->validate([
            'name' => 'required|string',
            'route' => 'required|string',
            'display' => 'integer',
            'sort' => 'integer',
            'icon' => 'string',
            'pid' => 'integer',
        ]);
        RbacMenu::where('id',$id)->update($data);
        return $this->responseSucceed();
    }

    /**
     * 删除菜单
     * @Author wuyan（466720682@qq.com）
     * @param  Request
     * @return [Boolean]
     */
    public function delete($id){
        if(RbacPermission::where('menu_id',$id)->first()){
            return $this->responseFailed('请先删除菜单下面的权限');
        }
        if(RbacMenu::where('pid',$id)->first()){
            return $this->responseFailed('请先删除下级菜单');
        }
        RbacMenu::where('id',$id)->delete();
        return $this->responseSucceed();
    }

    /**
     * 菜单列表
     * @Author wuyan（466720682@qq.com）
     * @return [Array]
     */
    public function lists(){
        $menuModel = new RbacMenu();
        $lists = $menuModel->lists();
        return $this->responseSucceed($lists);
    }

    /**
     * 获取菜单下拉
     * @Author wuyan（466720682@qq.com）
     * @return [Array]
     */
    public function menuSelect(){
        $menuModel = new RbacMenu();
        $lists = $menuModel->selecteds();
        return $this->responseSucceed($lists);
    }

    /**
     * 菜单显示列表
     * @Author wuyan（466720682@qq.com）
     * @return [Array]
     */
    public function menuShow(){
        $menuModel = new RbacMenu();
        $lists = $menuModel->menuShow();
        return $this->responseSucceed($lists);
    }

}
