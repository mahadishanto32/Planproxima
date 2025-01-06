<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\OrganizationRevenue;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrganizationRevenueController extends AppBaseController
{
    /**
     * Save or update the organization revenue for a specific year.
     */
    public function saveChanges(Request $request)
    {


        $validatedData = $request->validate([
            'revenue' => 'required|numeric|min:0',
            'revenue_year' => 'required|integer|min:2000|max:2100', // Define year range as appropriate
        ]);

        // Find or create revenue entry for the specified year
        $organizationRevenue = OrganizationRevenue::firstOrNew(['revenue_year' => $validatedData['revenue_year']]);

        // Update the revenue
        $organizationRevenue->revenue = $validatedData['revenue'];
        $organizationRevenue->save();

        return response()->json([
            'message' => 'Organization revenue updated successfully.',
            'data' => $organizationRevenue
        ], 200);
    }

    public function getRevenue(Request $request)
{
    // Optional: Get revenue for a specific year if provided, otherwise get the latest entry
    $year = $request->input('revenue_year');
    $query = OrganizationRevenue::query();
    
    if ($year) {
        $query->where('revenue_year', $year);
    } else {
        $query->orderBy('revenue_year', 'desc');
    }

    $organizationRevenue = $query->first();

    if (!$organizationRevenue) {
        return response()->json([
            'message' => 'No organization revenue found.',
        ], 404);
    }

    return response()->json([
        'data' => $organizationRevenue
    ], 200);
}

}
