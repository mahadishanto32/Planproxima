<?php 
namespace App\Http\Controllers\API;
use App\Http\Controllers\AppBaseController;
use App\Models\UserGroup;
use Illuminate\Http\Request; 
class UserGroupController extends AppBaseController
{
    // Display a listing of the resource
    public function index()
    {
        $userGroup = UserGroup::all();  
        return $this->sendResponse($userGroup, 'User Group retrieved successfully');
    }

    // Show the form for creating a new resource
    public function create()
    {
        //
    }
 
    public function store(Request $request)
    {
        $validatedData = $request->validate([ 
            'name' => 'required|string|max:100',
            'title' => 'required|string|max:155' 
        ]); 
        $group = UserGroup::create($request->all()); 
        return $this->sendResponse($group, 'User Group created successfully',200);
    }

    // Display the specified resource
    public function show($id)
    {
        $usergroup = UserGroup::find($id); 
        return $this->sendResponse($usergroup, 'User Group data return successfully',200);
    }

    // Show the form for editing the specified resource
    public function edit($id)
    {
        // Find the menu item by its ID
        $menu = UserGroup::find($id);
    
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
        $userGroup = UserGroup::find($id);
        $userGroup->update($request->all());
        return $this->sendResponse($userGroup, 'User Group updated successfully',200);
    }

    // Remove the specified resource from storage
    public function destroy($id)
    {
        UserGroup::destroy($id);
        return $this->sendResponse(1, 'User Group deleted successfully',200);
    }
}
