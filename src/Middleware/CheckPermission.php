<?php
namespace Eachdemo\Rbac\Middleware;
use Closure;
use Auth;
use Illuminate\Support\Facades\DB;
class CheckPermission
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $admin = Auth::user();
        if($admin->id === 1) return $next($request);
        
        $route = $request->route()->getAction();
        // $action = substr($route['controller'],21);
        $action = $route['controller'];
        $where['rbac_permissions.action'] = $action;
        $check_result = DB::table('rbac_permissions')
            ->join('rbac_role_has_menu_permissions','rbac_role_has_menu_permissions.id','=','rbac_permissions.id')
            ->join('rbac_roles','rbac_roles.id','=','rbac_role_has_menu_permissions.role_id')
            ->join('rbac_admin_has_roles','rbac_admin_has_roles.role_id','=','rbac_roles.id')
            ->join('rbac_admins','rbac_admins.id','=','rbac_admin_has_roles.admin_id')
            ->where('rbac_permissions.action',$action)
            ->where('rbac_role_has_menu_permissions.type',1)
            ->where('rbac_admins.id',$admin->id)
            ->first();
        if(empty($check_result)) {
            return response()->json([
                'message' => '没有权限'
            ],403);
        }
        return $next($request);
    }
}