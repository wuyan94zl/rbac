<?php

namespace Eachdemo\Rbac\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Eachdemo\Rbac\Models\RbacAdmin;
use Eachdemo\Rbac\Models\RbacAdminHasRole;
use Eachdemo\Rbac\Traits\ApiResponse;
use \Tymon\JWTAuth\Facades\JWTAuth;
use Auth;

class AdminController extends Controller
{
 	
    use ApiResponse;

    public function __construct()
    {
        $this->middleware(['auth:admin','eachdemo.rbac.permission'], ['except' => ['login','me']]);
    }

    /**
     * 管理员登录
     * @Author wuyan（466720682@qq.com）
     * @return [Array]
     */
 	public function login(Request $request){
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:5'
        ]);
        $admin = RbacAdmin::where(['email'=>$data['email']])->first();
        if(empty($admin)){
            if($data['email'] !== 'admin@wuyan.com'){
                return $this->responseFailed('用户不存在');
            }else{
                factory(RbacAdmin::class)->create();
            }
        }
        $token = auth('admin')->attempt($data);
        if($token){
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60
            ]);
        }
        return $this->responseUnauthorized();
    }

    /**
     * 当前登录用户
     * @Author wuyan（466720682@qq.com）
     * @return [Array]
     */
    public function me(){
        return $this->responseSucceed(Auth::user());
    }

    /**
     * 管理员列表
     * @Author wuyan（466720682@qq.com）
     * @return [Array]
     */
    public function lists(){
        $admin = RbacAdmin::with('roles')->paginate(10);
        return $this->responseSucceed($admin);
    }

    /**
     * 管理员创建
     * @Author wuyan（466720682@qq.com）
     * @return [Boolean]
     */
    public function create(Request $request){
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'has_role' => 'array'
        ]);
        $data['password'] = bcrypt($data['password']);
        (new RbacAdmin())->createAdmin($data);
        return $this->responseCreated();
    }

    /**
     * 管理员更新
     * @Author wuyan（466720682@qq.com）
     * @return [Boolean]
     */
    public function update($id, Request $request){
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'string|min:6',
            'has_role' => 'array'
        ]);
        if(isset($data['password']))
            $data['password'] = bcrypt($data['password']);
        (new RbacAdmin())->updateAdmin($id, $data);
        return $this->responseSucceed();
    }

    /**
     * 管理员删除
     * @Author wuyan（466720682@qq.com）
     * @return [Boolean]
     */
    public function delete($id){
        RbacAdminHasRole::where('admin_id',$id)->delete();
        RbacAdmin::where('id',$id)->delete();
        return $this->responseSucceed();
    }

}
