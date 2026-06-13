<?php

/**
 * مسارات التخزين السحابي + Media + backup-storage
 *
 * انسخ محتوى هذا الملف داخل مجموعة admin في routes/admin.php
 * (داخل middleware auth + admin، مع بادئة admin. للأسماء).
 */

// Backup Storage (وجهات تخزين النسخ الاحتياطي)
Route::resource('backup-storage', \App\Http\Controllers\Admin\BackupStorageController::class, ['except' => ['show']])->parameters(['backup-storage' => 'config']);
Route::post('backup-storage/{config}/test', [\App\Http\Controllers\Admin\BackupStorageController::class, 'test'])->name('backup-storage.test');
Route::post('backup-storage/test-connection', [\App\Http\Controllers\Admin\BackupStorageController::class, 'testConnection'])->name('backup-storage.test-connection');
Route::get('backup-storage/analytics', [\App\Http\Controllers\Admin\BackupStorageAnalyticsController::class, 'index'])->name('backup-storage.analytics');

// App Storage
Route::prefix('app-storage')->name('app-storage.')->group(function () {
    Route::resource('configs', \App\Http\Controllers\Admin\AppStorageController::class);
    Route::post('configs/{config}/test', [\App\Http\Controllers\Admin\AppStorageController::class, 'test'])->name('configs.test');
    Route::get('analytics', [\App\Http\Controllers\Admin\AppStorageAnalyticsController::class, 'index'])->name('analytics');
});

// Storage Migration
Route::prefix('storage-migration')->name('storage-migration.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\StorageMigrationController::class, 'index'])->name('index');
    Route::get('analyze/{disk?}', [\App\Http\Controllers\Admin\StorageMigrationController::class, 'analyze'])->name('analyze');
    Route::post('migrate', [\App\Http\Controllers\Admin\StorageMigrationController::class, 'startMigration'])->name('migrate');
    Route::post('migrate-all', [\App\Http\Controllers\Admin\StorageMigrationController::class, 'startAllMigration'])->name('migrate-all');
    Route::get('batch/{batchId}', [\App\Http\Controllers\Admin\StorageMigrationController::class, 'batchStatus'])->name('batch-status');
    Route::post('batch/{batchId}/cancel', [\App\Http\Controllers\Admin\StorageMigrationController::class, 'cancelBatch'])->name('batch-cancel');
    Route::get('verify/{diskName}', [\App\Http\Controllers\Admin\StorageMigrationController::class, 'verify'])->name('verify');
    Route::post('cleanup/{diskName}', [\App\Http\Controllers\Admin\StorageMigrationController::class, 'cleanup'])->name('cleanup');
    Route::get('batches', [\App\Http\Controllers\Admin\StorageMigrationController::class, 'batches'])->name('batches');
});

// Media Monitoring
Route::prefix('media-monitoring')->name('media-monitoring.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\MediaMonitoringController::class, 'index'])->name('index');
    Route::post('retry-conversion/{conversion}', [\App\Http\Controllers\Admin\MediaMonitoringController::class, 'retryConversion'])->name('retry-conversion');
    Route::post('retry-dead-letter/{deadLetter}', [\App\Http\Controllers\Admin\MediaMonitoringController::class, 'retryDeadLetter'])->name('retry-dead-letter');
    Route::post('cleanup-orphans', [\App\Http\Controllers\Admin\MediaMonitoringController::class, 'cleanupOrphans'])->name('cleanup-orphans');
    Route::post('cleanup-soft-deleted', [\App\Http\Controllers\Admin\MediaMonitoringController::class, 'cleanupSoftDeleted'])->name('cleanup-soft-deleted');
});

// Media Management
Route::prefix('media')->name('media.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\MediaController::class, 'index'])->name('index');
    Route::get('/dead-letters', [\App\Http\Controllers\Admin\MediaController::class, 'deadLetters'])->name('dead-letters');
    Route::post('/dead-letters/{deadLetter}/retry', [\App\Http\Controllers\Admin\MediaController::class, 'retryDeadLetter'])->name('dead-letters.retry');
    Route::delete('/dead-letters/{deadLetter}', [\App\Http\Controllers\Admin\MediaController::class, 'deleteDeadLetter'])->name('dead-letters.delete');
    Route::post('/dead-letters/resolve-all', [\App\Http\Controllers\Admin\MediaController::class, 'resolveAllDeadLetters'])->name('dead-letters.resolve-all');
    Route::get('/conversions', [\App\Http\Controllers\Admin\MediaController::class, 'conversions'])->name('conversions');
    Route::post('/conversions/{conversion}/retry', [\App\Http\Controllers\Admin\MediaController::class, 'retryConversion'])->name('retry-conversion');
    Route::delete('/conversions/{conversion}', [\App\Http\Controllers\Admin\MediaController::class, 'deleteConversion'])->name('delete-conversion');
    Route::get('/orphans', [\App\Http\Controllers\Admin\MediaController::class, 'orphans'])->name('orphans');
    Route::post('/orphans/delete', [\App\Http\Controllers\Admin\MediaController::class, 'deleteOrphans'])->name('delete-orphans');
    Route::get('/{medium}', [\App\Http\Controllers\Admin\MediaController::class, 'show'])->name('show');
    Route::delete('/{medium}', [\App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('destroy');
    Route::delete('/{medium}/soft', [\App\Http\Controllers\Admin\MediaController::class, 'softDelete'])->name('soft-delete');
    Route::post('/{medium}/restore', [\App\Http\Controllers\Admin\MediaController::class, 'restore'])->name('restore');
    Route::post('/{medium}/sync', [\App\Http\Controllers\Admin\MediaController::class, 'syncNow'])->name('sync');
});

Route::resource('storage-disk-mappings', \App\Http\Controllers\Admin\StorageDiskMappingController::class);
