# حزمة التخزين السحابي (cloud)

نسخة مستقلة من نظام التخزين السحابي لمشروع **quantum_lms**، جاهزة للدمج في مشروع Laravel آخر.

> **الدليل الكامل للنقل والميزات:** [دليل-النقل-والميزات.md](دليل-النقل-والميزات.md) — يُنصح بقراءته قبل الدمج في مشروع جديد.

## المحتويات

| الوحدة | الوصف |
|--------|--------|
| **App Storage** | إعدادات أماكن التخزين (S3, Google Drive, …)، Disk Mappings، الترحيل، التحليلات |
| **Backup Storage** | وجهات تخزين النسخ الاحتياطي + 10 سائقين |
| **Media** | إدارة الوسائط، المراقبة، المزامنة، dead letters |
| **Runtime** | إعدادات التخزين من `system_settings` (مجموعة `storage`) |

## التثبيت السريع

1. انسخ مجلدات `app/`, `config/`, `database/`, `resources/`, `routes/`, `tests/` إلى مشروعك الجديد (دمج مع الموجود، لا استبدال كامل).
2. ثبّت الحزم (انظر [INTEGRATION.md](INTEGRATION.md)).
3. سجّل `StorageServiceProvider` في `bootstrap/providers.php`.
4. ادمج المسارات من [`routes/admin-cloud.routes.php`](routes/admin-cloud.routes.php).
5. شغّل `php artisan migrate`.
6. شغّل `php artisan db:seed --class=CloudPermissionsSeeder`.
7. ادمج مقتطفات [`integration/`](integration/) (sidebar، helpers، AppServiceProvider).
8. شغّل queue worker للطابور `storage-sync` (وطوابير Media إن لزم).

## أوامر Artisan

| الأمر | الوظيفة |
|-------|---------|
| `php artisan storage:migrate` | ترحيل ملفات للسحابة |
| `php artisan storage:analyze` | تحليل الملفات المحلية |
| `php artisan storage:migrate-cleanup` | تنظيف بعد الترحيل |
| `php artisan app:storage:test` | اختبار تخزين التطبيق |
| `php artisan backup:storage:test` | اختبار تخزين النسخ الاحتياطي |

## الاستخدام في الكود

```php
use App\Services\Storage\MediaStorageService;
use App\Helpers\StorageHelper;

$result = MediaStorageService::uploadImage($file, 'users/photos');
$url = media_public_url($result['path']);
$disk = storage_disk('images');
```

## ملفات مرجعية

- [INTEGRATION.md](INTEGRATION.md) — دليل الدمج التفصيلي
- [MANIFEST.md](MANIFEST.md) — قائمة كل الملفات المنسوخة

## ملاحظات

- **لا تُعدّل** المشروع الأصلي — هذا مجلد نسخ فقط.
- مسارات الترحيل في `StorageMigrationService::KNOWN_PATHS` مخصّصة لـ LMS — عدّلها لمشروعك.
- بيانات الاعتماد المشفّرة في `AppStorageConfig` تعتمد على `APP_KEY` — أعد إدخالها عند نقل قاعدة بيانات مختلفة.
