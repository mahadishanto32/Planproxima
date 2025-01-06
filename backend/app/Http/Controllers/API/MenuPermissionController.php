<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\MenuManage;
use App\Models\MenuPermission;
use DB;
use Illuminate\Http\Request;
use App\Http\Resources\MenuResource;
use App\Http\Resources\MenuPermissionResource;
use Auth;
use App\Models\User;

class MenuPermissionController extends AppBaseController
{
    // Display a listing of the resource

    public function get_menu_permission($id, Request $request)
    {
        $user_type = $request->get('type');

        if ($user_type ==  'user') {
            if (!DB::table('menu_permission')->where('user_id', $id)->exists()) {
                $user =  User::where('id', $id)->first();
                $id =  $user->role_id;
                $user_type  =  'role';
            }
        }

        $subQuery = DB::table('menu_permission')
            ->select('view')
            ->whereColumn('menu_permission.menu_id', 'menu_manage.id');

        if ($user_type == 'user') {
            $subQuery->where('user_id', $id);
        } else {
            $subQuery->where('role_id', $id);
        }

        $subQuery->limit(1);




        // Construct the main query with the subquery as a raw SQL
        $menus = MenuManage::select(
            'menu_manage.id',
            'menu_manage.parent_id',
            'menu_manage.menu_name',
            'menu_manage.menu_url',
            DB::raw('COALESCE((' . $subQuery->toSql() . '), 0) as view')
        )
            ->mergeBindings($subQuery) // Merge bindings from the subquery into the main query
            ->where('menu_manage.parent_id', 0)
            ->get();

        $data_return = MenuPermissionResource::collection($menus);
        return $this->sendResponse($data_return, 'KRA retrieved successfully');
    }


    public function getUserMenu()
    {


 
        $userData = Auth::user(); 
        $active = DB::table('users')->where('id', $userData->id)->where('status', 1)->exists();

        if (!$active) {
            return response()->json([
                'status' => 3,
                'message' => 'Your account is disabled',
            ]);
        }

        $mainMenusQ = DB::table('menu_permission')
            ->select('menu_manage.id', 'menu_manage.menu_name', 'menu_manage.menu_url', 'menu_manage.parent_id', 'menu_manage.sort')
            ->join('menu_manage', 'menu_manage.id', '=', 'menu_permission.menu_id');

        // Apply the new condition based on `menu_permission_type`
        if ($userData->menu_permission_type == 2) {
            $mainMenusQ->where('menu_permission.user_id', $userData->id);
        } else {
            $mainMenusQ->where('menu_permission.role_id', $userData->role_id);
        }

        // Common conditions
        $mainMenusQ->where('menu_permission.view', 1)
            ->orderBy('menu_manage.parent_id', 'asc')
            ->orderBy('menu_manage.sort', 'asc');

        $mainMenus = $mainMenusQ->get();

        $menuTree = [];

        // Build the menu tree
        foreach ($mainMenus as $menu) {
            if (!isset($menu->parent_id)) {
                $menu->parent_id = 0;
            }

            if ($menu->parent_id == 0) {
                $menu->sub_menu = [];
                $menuTree[$menu->id] = $menu;
            } else {
                if (isset($menuTree[$menu->parent_id])) {
                    $menuTree[$menu->parent_id]->sub_menu[] = $menu;
                } else {
                    if (!isset($menuTree[$menu->parent_id])) {
                        $menuTree[$menu->parent_id] = (object) ['id' => $menu->parent_id, 'sub_menu' => []];
                    }
                    $menuTree[$menu->parent_id]->sub_menu[] = $menu;
                }
            }
        }

        // Filter and structure the main menu tree
        $mainMenus = array_values(array_filter($menuTree, function ($menu) {
            return isset($menu->parent_id) && $menu->parent_id == 0;
        }));


        return response()->json([
            'status' => 1,
            'menu_permission' => $mainMenus,
        ]);
    }


    public function update_menu_permission(Request $request, $id)
    {
        $items =  $request['items'];
        foreach ($items as $key => $menu) {

            $menuQuer = MenuPermission::where('menu_id', $menu['id']);
            if ($request->get('type') == 'role') {
                $menuQuer->where('role_id', $id);
            } else {
                $menuQuer->where('user_id', $id);
            }


            $permittedMenu  = $menuQuer->first();
            if ($permittedMenu) {
                MenuPermission::where('id', $permittedMenu->id)
                    ->update([
                        'view' => $menu['view'],
                    ]);
            } else {

                MenuPermission::insert([
                    'role_id' => $request->get('type') == 'role' ?  $id : '0',
                    'user_id' => $request->get('type') == 'user' ?  $id : '0',
                    'menu_id' => $menu['id'],
                    'view' => $menu['view'],
                    'add' => 1,
                    'update' => 1,
                    'delete' => 1,
                ]);
            }

            foreach ($menu['sub_menu'] as $key2 => $sub_menu) {


                $permittedsubMenuQ  = MenuPermission::where('menu_id', $sub_menu['id']);
                if ($request->get('type') == 'role') {
                    $permittedsubMenuQ->where('role_id', $id);
                } else {
                    $permittedsubMenuQ->where('user_id', $id);
                }

                $permittedsubMenu  = $permittedsubMenuQ->first();


                if ($permittedsubMenu) {
                    MenuPermission::where('id', $permittedsubMenu->id)
                        ->update([
                            'view' => $sub_menu['view'],
                        ]);
                } else {

                    MenuPermission::insert([
                        'role_id' => $request->get('type') == 'role' ?  $id : '0',
                        'user_id' => $request->get('type') == 'user' ?  $id : '0',
                        'menu_id' => $sub_menu['id'],
                        'view' => $sub_menu['view'],
                        'add' => 1,
                        'update' => 1,
                        'delete' => 1,
                    ]);
                }
            }
        }

        if ($request->get('type') == 'user') {
            User::where('id', $id)
                ->update([
                    'menu_permission_type' => 2,
                ]);
        }
        return $this->sendResponse(1, 'Updated Successfully');
    }
}
