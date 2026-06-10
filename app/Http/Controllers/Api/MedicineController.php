<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    // جلب الأدوية مع البحث والفلتر
    public function index(Request $request)
    {
        $query = Medicine::with('pharmacies');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('available')) {
            $query->where('is_available', $request->available);
        }

        return response()->json([
            'data' => $query->get()
        ]);
    }

    // إضافة دواء
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|unique:medicines,name',
            'category'     => 'required|string',
            'description'  => 'nullable|string',
            'stock'        => 'required|integer|min:0',
            'is_available' => 'boolean',
        ]);

        $medicine = Medicine::create($request->all());

        $medicine->pharmacies()->attach($request->user()->pharmacy_id, [
            'stock'        => $request->stock,
            'stock_status' => $request->is_available ?? true,
        ]);

        ActivityLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'added',
            'target_type' => 'medicine',
            'target_name' => $medicine->name,
        ]);

        return response()->json([
            'message' => 'Medicine added successfully',
            'data'    => $medicine
        ], 201);
    }

    // تعديل دواء
    public function update(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $request->validate([
            'name'         => 'sometimes|string|unique:medicines,name,' . $id,
            'category'     => 'sometimes|string',
            'description'  => 'nullable|string',
            'stock'        => 'sometimes|integer|min:0',
            'is_available' => 'boolean',
        ]);

        $medicine->update($request->all());

        ActivityLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'updated',
            'target_type' => 'medicine',
            'target_name' => $medicine->name,
        ]);

        return response()->json([
            'message' => 'Medicine updated successfully',
            'data'    => $medicine
        ]);
    }

    // حذف دواء
    public function destroy(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        ActivityLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'deleted',
            'target_type' => 'medicine',
            'target_name' => $medicine->name,
        ]);

        $medicine->delete();

        return response()->json([
            'message' => 'Medicine deleted successfully'
        ]);
    }

    // تغيير حالة التوفر
    public function updateAvailability(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $request->validate([
            'is_available' => 'required|boolean',
        ]);

        $medicine->update([
            'is_available' => $request->is_available
        ]);

        $medicine->pharmacies()->updateExistingPivot($request->user()->pharmacy_id, [
            'stock_status' => $request->is_available
        ]);

        ActivityLog::create([
            'user_id'     => $request->user()->id,
            'action'      => $request->is_available ? 'marked available' : 'marked unavailable',
            'target_type' => 'medicine',
            'target_name' => $medicine->name,
        ]);

        return response()->json([
            'message' => 'Availability updated successfully',
            'data'    => $medicine
        ]);
    }
}
