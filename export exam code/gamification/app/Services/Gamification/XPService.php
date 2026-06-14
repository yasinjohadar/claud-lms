<?php

namespace App\Services\Gamification;

/**
 * Stub included in export bundle â€” implement or replace in target project.
 * Registered by GamificationServiceProvider.
 */
class XPService
{
    public function award(int $userId, int $amount, string $source = 'manual', ?string $description = null): void
    {
        // Delegate to PointsService or implement XP ledger in target project.
    }
}
