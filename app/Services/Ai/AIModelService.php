<?php

namespace App\Services\Ai;

use Illuminate\Support\Collection;

class AIModelService
{
    public function getAvailableModels(string $capability = ''): Collection
    {
        return collect();
    }
}
