<?php

namespace App\Http\Controllers\Admin\Concerns;

trait UsesLaravelAiSdkForWizards
{
    protected function wizardUsesLaravelAiSdk(string $engineKey): bool
    {
        $global = config('ai.application.engine', 'legacy');
        if ($global === 'laravel_ai') {
            return true;
        }

        $specific = config("ai.application.{$engineKey}");

        if ($specific === 'laravel_ai') {
            return true;
        }

        if ($specific === 'legacy') {
            return false;
        }

        return false;
    }
}
