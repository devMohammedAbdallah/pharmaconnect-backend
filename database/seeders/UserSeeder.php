<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pharmacy;
use App\Models\Medicine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء صيدلية
        $pharmacy = Pharmacy::create([
            'name'          => 'Al Noor Pharmacy',
            'location'      => 'Gaza City, Al-Rimal',
            'phone'         => '059-111-1111',
            'working_hours' => 'Open 24h',
        ]);

        // إنشاء صيدلاني
        User::create([
            'name'        => 'Pharmacist',
            'email'       => 'pharmacist@gmail.com',
            'password'    => Hash::make('password123'),
            'role'        => 'pharmacist',
            'pharmacy_id' => $pharmacy->id,
        ]);

        // إنشاء أدمن
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // إنشاء مريض
        User::create([
            'name'     => 'Mohammed',
            'email'    => 'mohammed@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'patient',
        ]);

        // إنشاء أدوية
        $medicines = [
            ['name' => 'Panadol',     'category' => 'Pain Relief',  'stock' => 120, 'is_available' => true],
            ['name' => 'Amoxicillin', 'category' => 'Antibiotic',   'stock' => 45,  'is_available' => true],
            ['name' => 'Vitamin C',   'category' => 'Supplements',  'stock' => 200, 'is_available' => true],
            ['name' => 'Ibuprofen',   'category' => 'Pain Relief',  'stock' => 0,   'is_available' => false],
            ['name' => 'Paracetamol', 'category' => 'Fever Relief', 'stock' => 95,  'is_available' => true],
            ['name' => 'Insulin',     'category' => 'Diabetes',     'stock' => 18,  'is_available' => true],
        ];

        foreach ($medicines as $med) {
            $medicine = Medicine::create($med);
            $medicine->pharmacies()->attach($pharmacy->id, [
                'stock'        => $med['stock'],
                'stock_status' => $med['is_available'],
            ]);
        }
    }
}