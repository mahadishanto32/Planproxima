<?php 
namespace App\Http\Controllers\API;
use App\Http\Controllers\AppBaseController;
use App\Models\MenuManage;
use Illuminate\Http\Request;
use App\Http\Resources\MenuResource;
use App\Http\Resources\MenuPermissionResource;
class MenuManageController extends AppBaseController
{
    // Display a listing of the resource
    public function index()
    {
        $menus = MenuManage::where('parent_id',0)->orderBy('sort','asc')->get(); 
        $data_return  =   MenuResource::collection($menus);
        return $this->sendResponse($data_return, 'KRA retrieved successfully');
    }

    public function mainMenu(){
        $menus = MenuManage::where('parent_id',0)->orderBy('sort','asc')->get(); 
 
        return $this->sendResponse($menus, 'KRA retrieved successfully');
       
    }

    
    // Show the form for creating a new resource
    public function create()
    {
        //
    }
 
    public function store(Request $request)
    {
       
        $validatedData = $request->validate([ 
            'menu_name' => 'required|string|max:100',
            'menu_url' => 'required|string|max:155',
            'sort' => 'nullable|integer', 
            'menu_hints' => 'nullable|string|max:100' 
        ]);

        $menu = MenuManage::create($request->all());
     
        return $this->sendResponse($menu, 'Menu Entry saved successfully',200);
    }

    // Display the specified resource
    public function show($id)
    {
        $menu = MenuManage::find($id); 
        return $this->sendResponse($menu, 'Menu data return successfully');
    }

    // Show the form for editing the specified resource
    public function edit($id)
    {
        // Find the menu item by its ID
        $menu = MenuManage::find($id);
    
        // Check if the menu item exists
        if (!$menu) {
            return response()->json(['message' => 'Menu item not found'], 404);
        }
    
        // Return the menu item as a JSON response
        return response()->json($menu);
    }
    

    // Update the specified resource in storage
    public function update(Request $request, $id)
    {
        $menu = MenuManage::find($id);
        $menu->update($request->all());
        return $this->sendResponse($menu, 'Menu updated successfully');
    }

    // Remove the specified resource from storage
    public function destroy($id)
    {
        MenuManage::destroy($id);
        return response()->json(null, 204);
    }
}
