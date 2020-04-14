<?php

namespace Eachdemo\Rbac\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

// use Eachdemo\Rbac\Models\RbacRole;
use Eachdemo\Rbac\Models\RbacAdminHasRole;


class RbacAdmin extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['role'=>'admin'];
    }

    public function roles(){
        return $this->belongsToMany('Eachdemo\Rbac\Models\RbacRole','rbac_admin_has_roles','admin_id','role_id');
    }

    /**
     * 创建管理员
     * @Author wuyan（466720682@qq.com）
     * @return [Boolean]
     */
    public function createAdmin($data){
        $has_role = [];
        if(isset($data['has_role'])){
            $has_role = $data['has_role'];
            unset($data['has_role']);
        }
        $admin = $this->create($data);
        if(!empty($has_role))
            $this->updateHasRole($admin->id,$has_role);
        return true;
    }

    public function updateAdmin($id, $data){
        $has_role = [];
        if(isset($data['has_role'])){
            $has_role = $data['has_role'];
            unset($data['has_role']);
        }
        $this->where('id',$id)->update($data);
        $this->updateHasRole($id,$has_role);
        return true;
    }

    private function updateHasRole($id, $hasRole){
        RbacAdminHasRole::where('admin_id',$id)->delete();
        $has_role_item = ['admin_id'=>$id];
        $create_data = [];
        foreach ($hasRole as $k => $v) {
            $has_role_item['role_id'] = $v;
            $create_data[] = $has_role_item;
        }
        RbacAdminHasRole::insert($create_data);
    }

}
