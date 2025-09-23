<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HasAlertResponses;

class ExampleController extends Controller
{
    use HasAlertResponses;

    /**
     * Example of CREATE operation with alerts
     */
    public function create(Request $request)
    {
        try {
            // Your create logic here
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
            ]);

            // Simulate creating a record
            // $record = YourModel::create($data);

            return $this->successResponse('Record created successfully!', 'records.index');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create record: ' . $e->getMessage());
        }
    }

    /**
     * Example of UPDATE operation with alerts
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
            ]);

            // Simulate updating a record
            // $record = YourModel::findOrFail($id);
            // $record->update($data);

            return $this->successResponse('Record updated successfully!');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update record: ' . $e->getMessage());
        }
    }

    /**
     * Example of DELETE operation with alerts
     */
    public function destroy($id)
    {
        try {
            // Simulate deleting a record
            // $record = YourModel::findOrFail($id);
            // $record->delete();

            return $this->successResponse('Record deleted successfully!');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete record: ' . $e->getMessage());
        }
    }

    /**
     * Example using the CRUD helper method
     */
    public function bulkOperation(Request $request)
    {
        return $this->handleCrudResponse(
            function() use ($request) {
                // Your operation logic here
                // Return true for success, false for failure
                return true; // or false
            },
            'YourModel',
            'Bulk operation completed successfully!',
            'Bulk operation failed!',
            'records.index'
        );
    }
}
