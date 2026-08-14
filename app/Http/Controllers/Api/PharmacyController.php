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
    $request->validate([
        'name'          => 'sometimes|string',
        'location'      => 'sometimes|string',
        'phone'         => 'sometimes|string',
        'working_hours' => 'nullable|string',
    ]);

    $user = $request->user();

    if (!$user->pharmacy_id) {
        // أنشئ صيدلية جديدة له لو ما كان عنده
        $pharmacy = Pharmacy::create([
            'name'          => $request->name ?? 'صيدلية جديدة',
            'location'      => $request->location ?? '',
            'phone'         => $request->phone ?? '',
            'working_hours' => $request->working_hours ?? null,
        ]);
        $user->update(['pharmacy_id' => $pharmacy->id]);
    } else {
        $pharmacy = Pharmacy::findOrFail($user->pharmacy_id);
        $pharmacy->update($request->all());
    }

    ActivityLog::create([
        'user_id'     => $user->id,
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
