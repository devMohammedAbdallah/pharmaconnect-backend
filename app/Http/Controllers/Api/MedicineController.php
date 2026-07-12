<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    // جلب الأدوية مع البحث والفلتر (عام - لكل المرضى)
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

    // جلب أدوية الصيدلاني الحالي فقط
    public function myMedicines(Request $request)
    {
        $pharmacyId = $request->user()->pharmacy_id;

        $medicines = Medicine::whereHas('pharmacies', function ($query) use ($pharmacyId) {
            $query->where('pharmacy_id', $pharmacyId);
        })->with(['pharmacies' => function ($query) use ($pharmacyId) {
            $query->where('pharmacy_id', $pharmacyId)
                  ->withPivot('stock', 'stock_status');
        }])->get();

        return response()->json([
            'data' => $medicines
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

    // تعديل دواء (بس تبع صيدلية الصيدلاني)
    public function update(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);
        $pharmacyId = $request->user()->pharmacy_id;

        if (!$medicine->pharmacies()->where('pharmacy_id', $pharmacyId)->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name'         => 'sometimes|string|unique:medicines,name,' . $id,
            'category'     => 'sometimes|string',
            'description'  => 'nullable|string',
            'stock'        => 'sometimes|integer|min:0',
            'is_available' => 'boolean',
        ]);

        $medicine->update($request->all());

        // حدّث الـ stock بالـ pivot الخاص بهاي الصيدلية كمان
        if ($request->has('stock')) {
            $medicine->pharmacies()->updateExistingPivot($pharmacyId, [
                'stock' => $request->stock,
            ]);
        }

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

    // حذف دواء (بس العلاقة تبع صيدلية الصيدلاني)
    public function destroy(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);
        $pharmacyId = $request->user()->pharmacy_id;

        if (!$medicine->pharmacies()->where('pharmacy_id', $pharmacyId)->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        ActivityLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'deleted',
            'target_type' => 'medicine',
            'target_name' => $medicine->name,
        ]);

        $medicine->pharmacies()->detach($pharmacyId);

        if ($medicine->pharmacies()->count() === 0) {
            $medicine->delete();
        }

        return response()->json([
            'message' => 'Medicine deleted successfully'
        ]);
    }

    // تغيير حالة التوفر (بس تبع صيدلية الصيدلاني)
    public function updateAvailability(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);
        $pharmacyId = $request->user()->pharmacy_id;

        if (!$medicine->pharmacies()->where('pharmacy_id', $pharmacyId)->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'is_available' => 'required|boolean',
        ]);

        $medicine->update([
            'is_available' => $request->is_available
        ]);

        $medicine->pharmacies()->updateExistingPivot($pharmacyId, [
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
};