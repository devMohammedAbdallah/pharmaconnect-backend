<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    // جلب كل الصيدليات
    public function index(Request $request)
    {
        $query = Pharmacy::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        return response()->json([
            'data' => $query->get()
        ]);
    }

    // جلب تفاصيل صيدلية مع أدويتها
    public function show($id)
    {
        $pharmacy = Pharmacy::with(['medicines' => function ($query) {
            $query->select('medicines.id', 'medicines.name', 'medicines.category')
                  ->withPivot('stock', 'stock_status');
        }])->findOrFail($id);

        return response()->json([
            'data' => $pharmacy
        ]);
    }

    // تعديل بروفايل الصيدلية
    public function update(Request $request)
    {
        $pharmacy = Pharmacy::findOrFail($request->user()->pharmacy_id);

        $request->validate([
            'name'          => 'sometimes|string',
            'location'      => 'sometimes|string',
            'phone'         => 'sometimes|string',
            'working_hours' => 'nullable|string',
        ]);

        $pharmacy->update($request->all());

        ActivityLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'updated',
            'target_type' => 'pharmacy',
            'target_name' => $pharmacy->name,
        ]);

        return response()->json([
            'message' => 'Pharmacy updated successfully',
            'data'    => $pharmacy
        ]);
    }
}