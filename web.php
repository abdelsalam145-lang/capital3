<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Building;
use App\Models\Elevator;
use App\Models\MaintenanceRequest;
use App\Models\Part;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * بيانات تجريبية لتشغيل النظام واختباره فوراً.
 * شغّلها بـ: php artisan db:seed
 *
 * حسابات الدخول بعد التشغيل (كلمة المرور للجميع: password):
 *   مدير:   admin@asimat.com
 *   مشرف:   super1@asimat.com
 *   فني:    tech1@asimat.com
 *   عميل:   customer@asimat.com
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== المستخدمون =====
        $admin = User::create([
            'name' => 'مدير النظام', 'email' => 'admin@asimat.com',
            'phone' => '0500000001', 'password' => Hash::make('password'),
            'role' => 'admin', 'is_active' => true,
        ]);

        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => "مشرف $i", 'email' => "super$i@asimat.com",
                'phone' => "05000001$i", 'password' => Hash::make('password'),
                'role' => 'supervisor', 'is_active' => true,
            ]);
        }

        $technicians = [];
        $techNames = ['خالد العتيبي', 'ماجد الدوسري', 'سعد القحطاني', 'نواف الشمري'];
        foreach ($techNames as $i => $name) {
            $technicians[] = User::create([
                'name' => $name, 'email' => 'tech'.($i+1).'@asimat.com',
                'phone' => '05000002'.($i+1), 'password' => Hash::make('password'),
                'role' => 'technician', 'is_active' => true,
            ]);
        }

        // ===== العملاء والمباني والمصاعد =====
        $customer = Customer::create([
            'type' => 'company', 'name' => 'مجموعة واحة المسك',
            'phone' => '0551234567', 'email' => 'info@masgroup.com',
            'latitude' => 24.7136, 'longitude' => 46.6753,
            'address' => 'الرياض - حي العليا', 'tax_number' => '300000000000003',
            'national_id' => '1010101010', 'is_active' => true,
        ]);

        // حساب دخول العميل
        User::create([
            'name' => 'مسؤول واحة المسك', 'email' => 'customer@asimat.com',
            'phone' => '0551234567', 'password' => Hash::make('password'),
            'role' => 'customer', 'is_active' => true, 'customer_id' => $customer->id,
        ]);

        $building = Building::create([
            'customer_id' => $customer->id, 'name' => 'فندق واحة المسك',
            'manager_name' => 'أحمد الغامدي', 'manager_phone' => '0509998877',
            'latitude' => 24.7140, 'longitude' => 46.6760, 'address' => 'طريق الملك فهد',
        ]);

        $elevators = [];
        for ($i = 1; $i <= 5; $i++) {
            $elevators[] = Elevator::create([
                'building_id' => $building->id, 'label' => "مصعد رقم $i",
                'brand' => 'FUJI', 'model' => 'FJ-2000', 'serial_number' => "FJ-MASK-00$i",
                'capacity_kg' => 1000, 'floors' => 12, 'status' => 'operational',
            ]);
        }

        // ===== المستودعات والقطع =====
        $mainWarehouse = Warehouse::create([
            'name' => 'المستودع الرئيسي', 'type' => 'main',
            'location' => 'الرياض', 'is_active' => true,
        ]);

        foreach ($technicians as $tech) {
            Warehouse::create([
                'name' => "مخزون {$tech->name}", 'type' => 'technician',
                'technician_id' => $tech->id, 'is_active' => true,
            ]);
        }

        $parts = [
            ['sku' => 'FUJI-RLR-08', 'name' => 'بكرة توجيه', 'cost_price' => 80, 'sale_price' => 140, 'reorder_level' => 5],
            ['sku' => 'DR-SNS-22', 'name' => 'حساس باب', 'cost_price' => 120, 'sale_price' => 220, 'reorder_level' => 6],
            ['sku' => 'BTN-LED-05', 'name' => 'زر طابق مضيء', 'cost_price' => 25, 'sale_price' => 60, 'reorder_level' => 10],
            ['sku' => 'BLT-V-14', 'name' => 'سير محرك', 'cost_price' => 200, 'sale_price' => 380, 'reorder_level' => 5],
        ];
        foreach ($parts as $p) {
            Part::create(array_merge($p, ['unit' => 'piece', 'is_active' => true]));
        }

        // ===== طلبات تجريبية =====
        $types = ['maintenance', 'fault', 'inspection'];
        $statuses = ['new', 'assigned', 'in_progress', 'completed', 'closed'];
        foreach ($elevators as $idx => $elevator) {
            MaintenanceRequest::create([
                'type' => $types[$idx % 3], 'elevator_id' => $elevator->id,
                'customer_id' => $customer->id, 'created_by' => $admin->id,
                'assigned_technician_id' => $idx % 2 === 0 ? $technicians[0]->id : null,
                'title' => 'طلب تجريبي رقم '.($idx + 1),
                'description' => 'وصف الطلب التجريبي لاختبار النظام',
                'priority' => ['low', 'normal', 'high', 'urgent'][$idx % 4],
                'status' => $idx % 2 === 0 ? 'assigned' : 'new',
            ]);
        }

        $this->command->info('تم إنشاء البيانات التجريبية بنجاح.');
        $this->command->info('دخول المدير: admin@asimat.com / password');
    }
}
