<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/eachdemo/rbac',function(){
	echo "eachdemo-rbac";
});

Route::group([
	'namespace'=>'Eachdemo\Rbac\Controllers',
],function(){
	Route::post('/eachdemo/rbac/admin/login','AdminController@login');
	Route::get('/eachdemo/rbac/admin/me','AdminController@me');
	Route::post('/eachdemo/rbac/admin/lists','AdminController@lists');
	Route::post('/eachdemo/rbac/admin/create','AdminController@create');
	Route::post('/eachdemo/rbac/admin/update/{id}','AdminController@update');
	Route::post('/eachdemo/rbac/admin/delete/{id}','AdminController@delete');
});

Route::group([
	'namespace'=>'Eachdemo\Rbac\Controllers',
	'middleware' => 'auth:admin',
],function(){

	// 菜单相关路由
	Route::post('/eachdemo/rbac/menu/create','MenuController@create');
	Route::post('/eachdemo/rbac/menu/edit/{id}','MenuController@update');
	Route::get('/eachdemo/rbac/menu/delete/{id}','MenuController@delete');
	Route::get('/eachdemo/rbac/menus','MenuController@lists');
	Route::get('/eachdemo/rbac/menus/show','MenuController@menuShow');
	Route::get('/eachdemo/rbac/menus/select','MenuController@menuSelect');


	// 权限相关路由
	Route::post('/eachdemo/rbac/permission/create','PermissionController@create');
	Route::post('/eachdemo/rbac/permission/edit/{id}','PermissionController@update');
	Route::get('/eachdemo/rbac/permission/delete/{id}','PermissionController@delete');
	Route::get('/eachdemo/rbac/permission/generate','PermissionController@generateAllPermission');

	// 角色相关路由
	Route::post('/eachdemo/rbac/role/create','RoleController@create');
	Route::post('/eachdemo/rbac/role/edit/{id}','RoleController@update');
	Route::get('/eachdemo/rbac/role/delete/{id}','RoleController@delete');
	Route::get('/eachdemo/rbac/roles','RoleController@lists');
	Route::get('/eachdemo/rbac/roles/all','RoleController@checkBoxList');
	Route::get('/eachdemo/rbac/role/{role_id}/menus/permissions','RoleController@hasMenuPermission');
	Route::post('/eachdemo/rbac/role/{role_id}/menus/permissions','RoleController@setMenuPermission');

});