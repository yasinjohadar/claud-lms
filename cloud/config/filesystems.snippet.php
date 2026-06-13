<?php

/**
 * أجزاء من config/filesystems.php المطلوبة لنظام التخزين السحابي.
 * ادمجها في مشروعك الجديد — لا تستبدل الملف بالكامل.
 */

// قرص public للملفات المحلية (يُستخدم كـ fallback_disk)
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'visibility' => 'public',
    'serve' => true,
    'throw' => false,
    'report' => false,
],

// أقراص السحابة الديناميكية تُحقَن عند التشغيل عبر StorageServiceProvider
// من جداول app_storage_configs + storage_disk_mappings

// متغيرات .env الشائعة:
// FILESYSTEM_DISK=local
// AWS_ACCESS_KEY_ID=...
// AWS_SECRET_ACCESS_KEY=...
// AWS_DEFAULT_REGION=...
// AWS_BUCKET=...
