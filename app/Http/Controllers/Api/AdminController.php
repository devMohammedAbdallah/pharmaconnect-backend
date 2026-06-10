<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // إحصائيات النظام
    public function stats()
    {
        return response()->json([
            'data' => [
                'total_pharmacies'      => Pharmacy::count(),
                'total_medicines'       => Medicine::count(),
                'total_users'           => User::where('role', '=', 'patient')->count(),
                'available_medicines'   => Medicine::where('is_available', '=', true)->count(),
                'unavailable_medicines' => Medicine::where('is_available', '=', false)->count(),
            ]
        ]);
    }

    // تقارير وشارتات
    public function reports()
    {
        $medicinesPerCategory = Medicine::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->get();

        $availabilityRatio = [
            'available'   => Medicine::where('is_available', true)->count(),
            'unavailable' => Medicine::where('is_available', false)->count(),
        ];

        return response()->json([
            'data' => [
                'medicines_per_category' => $medicinesPerCategory,
                'availability_ratio'     => $availabilityRatio,
            ]
        ]);
    }

    // سجل النشاطات
    public function activityLog()
    {
        $logs = ActivityLog::with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->map(function ($log) {
                return [
                    'id'          => $log->id,
                    'name'        => $log->user->name,
                    'action'      => $log->action,
                    'target_type' => $log->target_type,
                    'target_name' => $log->target_name,
                    'timestamp'   => $log->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'data' => $logs
        ]);
    }

// إضافة صيدلية وصيدلاني جديد
public function addPharmacist(Request $request)
{
    $request->validate([
        'pharmacy_name'  => 'required|string',
        'location'       => 'required|string',
        'phone'          => 'required|string',
        'working_hours'  => 'nullable|string',
        'name'           => 'required|string',
        'email'          => 'required|email|unique:users,email',
        'password'       => 'required|string|min:6',
    ]);

    // إنشاء الصيدلية
    $pharmacy = Pharmacy::create([
        'name'          => $request->pharmacy_name,
        'location'      => $request->location,
        'phone'         => $request->phone,
        'working_hours' => $request->working_hours,
    ]);

    // إنشاء الصيدلاني
    $user = User::create([
        'name'        => $request->name,
        'email'       => $request->email,
        'password'    => Hash::make($request->password),
        'role'        => 'pharmacist',
        'pharmacy_id' => $pharmacy->id,
    ]);

    return response()->json([
        'message'  => 'Pharmacist added successfully',
        'pharmacy' => $pharmacy,
        'user'     => $user,
    ], 201);
}
}