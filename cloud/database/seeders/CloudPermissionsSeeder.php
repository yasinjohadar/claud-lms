<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

/**
 * صلاحيات نظام التخزين السحابي (مستخرجة من PermissionSeeder)
 *
 * التشغيل: php artisan db:seed --class=CloudPermissionsSeeder
 */
class CloudPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'backup-storage-list', 'description' => 'عرض قائمة تخزين النسخ الاحتياطي'],
            ['name' => 'backup-storage-create', 'description' => 'إنشاء تخزين نسخ احتياطي'],
            ['name' => 'backup-storage-edit', 'description' => 'تعديل تخزين النسخ الاحتياطي'],
            ['name' => 'backup-storage-delete', 'description' => 'حذف تخزين النسخ الاحتياطي'],
            ['name' => 'backup-storage-test', 'description' => 'اختبار تخزين النسخ الاحتياطي'],
            ['name' => 'backup-storage-test-connection', 'description' => 'اختبار اتصال تخزين النسخ الاحتياطي'],
            ['name' => 'app-storage-list', 'description' => 'عرض قائمة تخزين التطبيق'],
            ['name' => 'app-storage-create', 'description' => 'إنشاء تخزين تطبيق'],
            ['name' => 'app-storage-edit', 'description' => 'تعديل تخزين التطبيق'],
            ['name' => 'app-storage-delete', 'description' => 'حذف تخزين التطبيق'],
            ['name' => 'storage-disk-mapping-list', 'description' => 'عرض قائمة تعيينات أقراص التخزين'],
            ['name' => 'storage-disk-mapping-create', 'description' => 'إنشاء تعيين قرص تخزين'],
            ['name' => 'storage-disk-mapping-edit', 'description' => 'تعديل تعيين قرص التخزين'],
            ['name' => 'storage-disk-mapping-delete', 'description' => 'حذف تعيين قرص التخزين'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                ['description' => $permission['description'] ?? null]
            );
        }
    }
}
