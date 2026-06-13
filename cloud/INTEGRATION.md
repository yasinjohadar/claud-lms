# دليل دمج حزمة cloud في مشروع Laravel

> للشرح التفصيلي للميزات، المسارات، الإعدادات، وقائمة التحقق: راجع **[دليل-النقل-والميزات.md](دليل-النقل-والميزات.md)**.

## 1. متطلبات Composer

```bash
composer require league/flysystem-aws-s3-v3:^3.30
composer require league/flysystem-azure-blob-storage:^3.30
composer require league/flysystem-ftp:^3.29
composer require league/flysystem-sftp-v3:^3.30
composer require masbug/flysystem-google-drive-ext:^2.4
composer require spatie/flysystem-dropbox:^3.0
composer require spatie/laravel-permission:^6.19
```

## 2. نسخ الملفات

انسخ من `cloud/` إلى جذر المشروع الجديد:

| من | إلى |
|----|-----|
| `cloud/app/*` | `app/` |
| `cloud/config/storage.php` | `config/storage.php` |
| `cloud/database/migrations/*` | `database/migrations/` |
| `cloud/database/seeders/CloudPermissionsSeeder.php` | `database/seeders/` |
| `cloud/resources/views/*` | `resources/views/` |
| `cloud/tests/Unit/*` | `tests/Unit/` |

## 3. تسجيل المزود (Provider)

في `bootstrap/providers.php` أضف:

```php
App\Providers\StorageServiceProvider::class,
```

(انظر [`integration/bootstrap.providers.snippet`](integration/bootstrap.providers.snippet))

## 4. المسارات (Routes)

انسخ محتوى [`routes/admin-cloud.routes.php`](routes/admin-cloud.routes.php) داخل مجموعة `admin` في `routes/admin.php`، مع middleware `auth` و `admin` كما في المشروع المصدر.

## 5. قاعدة البيانات

```bash
php artisan migrate
php artisan db:seed --class=CloudPermissionsSeeder
```

**جداول مطلوبة:** `system_settings`, `app_storage_configs`, `app_storage_analytics`, `storage_disk_mappings`, `storage_sync_batches`, `storage_sync_dead_letters`, `backup_storage_configs`, `storage_analytics`, `media*`.

**Model مطلوب:** `App\Models\SystemSetting` (مُضمَّن في الحزمة).

## 6. Helpers

أضف دالة `media_public_url()` من [`integration/helpers.media_public_url.snippet`](integration/helpers.media_public_url.snippet) إلى `app/helpers.php` وسجّل الملف في `composer.json`:

```json
"autoload": {
    "files": ["app/helpers.php"]
}
```

أضف `storage_disk()` من [`integration/AppServiceProvider.storage_disk.snippet`](integration/AppServiceProvider.storage_disk.snippet) داخل `AppServiceProvider::boot()`.

## 7. واجهة الإدارة

- **Sidebar:** ادمج [`integration/sidebar-storage.blade.snippet`](integration/sidebar-storage.blade.snippet) في `main-sidebar.blade.php`.
- **الإعدادات:** ادمج معالجة حقول `storage_*` من [`integration/settings-storage.blade.snippet`](integration/settings-storage.blade.snippet) في صفحة الإعدادات، مع عرض المجموعة `storage`.

## 8. Filesystems

تأكد من وجود قرص `public` في `config/filesystems.php` (انظر [`config/filesystems.snippet.php`](config/filesystems.snippet.php)).

## 9. Queue

في `.env`:

```env
QUEUE_CONNECTION=database
```

شغّل worker:

```bash
php artisan queue:work --queue=storage-sync,default
```

أسماء الطوابير تُقرأ من `system_settings.storage_sync_queue` (افتراضي: `storage-sync`).

## 10. الصلاحيات

الواجهة تستخدم `@can('settings-manage')` لبعض الأقسام وصلاحيات `app-storage-*`, `backup-storage-*`, `storage-disk-mapping-*` للعمليات CRUD. عيّنها للأدوار المناسبة بعد تشغيل `CloudPermissionsSeeder`.

## 11. تبعيات إضافية

| التبعية | السبب |
|---------|--------|
| `User` model | علاقة `uploader` في `Media` |
| Middleware `admin` | على جميع controllers |
| Layout admin | views تفترض `@extends('admin.layouts...')` — عدّل المسارات إن اختلفت |

## 12. تخصيص مسارات الترحيل

في `App\Services\Storage\StorageMigrationService` عدّل الثابت `KNOWN_PATHS` ليطابق مجلدات `storage/app/public` في مشروعك.

## 13. اختبار

```bash
php artisan test --filter=Storage
php artisan app:storage:test
```

## مخطط التبعيات

```
StorageServiceProvider
  → FlysystemDriverRegistrar
  → StorageRuntimeConfig (SystemSetting)
  → StorageDiskMapping + AppStorageConfig → AppStorageFactory

MediaStorageService
  → StorageDriverModeResolver
  → CloudFirstStorageRouter → StorageSyncJob

StorageMigrationController
  → StorageMigrationService → StorageSyncJob
```
