#Requires -Version 5.1
<#
.SYNOPSIS
  Copies exam and gamification modules into export/ without modifying source files.
#>
param(
    [string]$ProjectRoot = (Split-Path -Parent $PSScriptRoot)
)

$ErrorActionPreference = 'Stop'
$ExportRoot = Join-Path $ProjectRoot 'export'
$ExamRoot = Join-Path $ExportRoot 'exam'
$GamifRoot = Join-Path $ExportRoot 'gamification'

function Ensure-Dir([string]$Path) {
    if (-not (Test-Path $Path)) {
        New-Item -ItemType Directory -Path $Path -Force | Out-Null
    }
}

function Copy-ProjectPath {
    param(
        [string]$RelativePath,
        [string]$TargetRoot
    )

    $source = Join-Path $ProjectRoot $RelativePath
    if (-not (Test-Path $source)) {
        Write-Warning "Missing source: $RelativePath"
        return $false
    }

    $dest = Join-Path $TargetRoot $RelativePath
    $destDir = Split-Path $dest -Parent
    Ensure-Dir $destDir

    if ((Get-Item $source).PSIsContainer) {
        Copy-Item -Path $source -Destination $dest -Recurse -Force
    } else {
        Copy-Item -Path $source -Destination $dest -Force
    }

    return $true
}

function Copy-ProjectGlob {
    param(
        [string]$Pattern,
        [string]$TargetRoot,
        [string]$BaseRelative = ''
    )

    $searchRoot = if ($BaseRelative) { Join-Path $ProjectRoot $BaseRelative } else { $ProjectRoot }
    Get-ChildItem -Path $searchRoot -Filter $Pattern -Recurse -File -ErrorAction SilentlyContinue | ForEach-Object {
        $relative = $_.FullName.Substring($ProjectRoot.Length).TrimStart('\', '/')
        Copy-ProjectPath -RelativePath $relative -TargetRoot $TargetRoot | Out-Null
    }
}

function Write-Manifest {
    param(
        [string]$ModuleRoot,
        [string]$ModuleName
    )

    $files = Get-ChildItem -Path $ModuleRoot -Recurse -File |
        Where-Object { $_.Name -ne 'MANIFEST.json' } |
        Sort-Object FullName

    $entries = @()
    foreach ($file in $files) {
        $relative = $file.FullName.Substring($ModuleRoot.Length).TrimStart('\', '/').Replace('\', '/')
        $hash = (Get-FileHash -Path $file.FullName -Algorithm SHA256).Hash.ToLower()
        $entries += [ordered]@{
            path = $relative
            size = $file.Length
            sha256 = $hash
        }
    }

    $manifest = [ordered]@{
        module = $ModuleName
        generated_at = (Get-Date).ToUniversalTime().ToString('o')
        file_count = $entries.Count
        files = $entries
    }

    $manifestPath = Join-Path $ModuleRoot 'MANIFEST.json'
    $manifest | ConvertTo-Json -Depth 6 | Set-Content -Path $manifestPath -Encoding UTF8
    Write-Host "MANIFEST: $ModuleName => $($entries.Count) files"
}

Write-Host "Project root: $ProjectRoot"
Ensure-Dir $ExamRoot
Ensure-Dir $GamifRoot

# ─── EXAM: app ───────────────────────────────────────────────────────────────
$examAppPaths = @(
    'app/Models/Quiz.php',
    'app/Models/QuizAttempt.php',
    'app/Models/QuizResponse.php',
    'app/Models/QuizQuestion.php',
    'app/Models/QuizSettings.php',
    'app/Models/QuizAnalytics.php',
    'app/Models/QuestionBank.php',
    'app/Models/QuestionType.php',
    'app/Models/QuestionOption.php',
    'app/Models/QuestionPool.php',
    'app/Models/QuestionPoolItem.php',
    'app/Models/QuestionModule.php',
    'app/Models/QuestionModuleAttempt.php',
    'app/Models/QuestionModuleResponse.php',
    'app/Models/CourseSectionQuestion.php',
    'app/Models/AIQuestionGeneration.php',
    'app/Models/AIQuestionSolution.php',
    'app/Http/Controllers/Admin/QuizController.php',
    'app/Http/Controllers/Admin/QuizGradingController.php',
    'app/Http/Controllers/Admin/QuizAnalyticsController.php',
    'app/Http/Controllers/Admin/QuestionBankController.php',
    'app/Http/Controllers/Admin/QuestionBankTypeImportController.php',
    'app/Http/Controllers/Admin/QuestionPoolController.php',
    'app/Http/Controllers/Admin/QuestionModuleController.php',
    'app/Http/Controllers/Admin/QuestionModuleGradingController.php',
    'app/Http/Controllers/Admin/AIQuestionCreationController.php',
    'app/Http/Controllers/Admin/AIQuestionGenerationController.php',
    'app/Http/Controllers/Admin/AIQuestionSolvingController.php',
    'app/Http/Controllers/Student/QuizAttemptController.php',
    'app/Http/Controllers/Student/QuizReviewController.php',
    'app/Http/Controllers/Student/QuestionModuleAttemptController.php',
    'app/Http/Controllers/Student/QuestionModuleStatsController.php',
    'app/Http/Controllers/Api/Student/QuizApiController.php',
    'app/Http/Controllers/Api/Student/QuestionModuleStatsApiController.php',
    'app/Services/QuestionBankExcelImportService.php',
    'app/Services/Api/StudentQuizApiService.php',
    'app/Services/Ai/AIQuestionCreationService.php',
    'app/Services/Ai/AIQuestionGenerationService.php',
    'app/Services/Ai/AIQuestionSolvingService.php',
    'app/Services/Ai/AIEssayGradingService.php',
    'app/Support/QuizGradingAnswerPresenter.php',
    'app/Events/QuizCompleted.php',
    'app/Events/QuizStarted.php',
    'app/Console/Commands/RegradeOldQuizAttempts.php',
    'app/Http/Middleware/DebugQuestionModuleRoute.php',
    'app/Ai/Agents/QuestionGenerationPlainAgent.php'
)

foreach ($p in $examAppPaths) { Copy-ProjectPath $p $ExamRoot | Out-Null }
Copy-ProjectPath 'app/Services/QuestionBank' $ExamRoot | Out-Null

# ─── EXAM: views, prompts, tests, seeders, config snippets ───────────────────
$examDirs = @(
    'resources/views/admin/pages/quizzes',
    'resources/views/admin/pages/question-bank',
    'resources/views/admin/pages/question-pools',
    'resources/views/admin/pages/question-modules',
    'resources/views/admin/pages/question-module-grading',
    'resources/views/admin/pages/grading',
    'resources/views/admin/pages/analytics',
    'resources/views/admin/ai/question-creation',
    'resources/views/admin/ai/question-generations',
    'resources/views/admin/ai/question-solutions',
    'resources/views/student/pages/quizzes',
    'resources/views/student/question-modules',
    'tests/Unit/QuestionBank'
)

foreach ($d in $examDirs) { Copy-ProjectPath $d $ExamRoot | Out-Null }
Copy-ProjectPath 'resources/views/student/pages/learn/partials/quiz.blade.php' $ExamRoot | Out-Null
Copy-ProjectPath 'resources/views/admin/pages/analytics/quiz.blade.php' $ExamRoot | Out-Null

$examFiles = @(
    'resources/prompts/quiz_generation.txt',
    'resources/prompts/question_generation.txt',
    'resources/prompts/essay_grading.txt',
    'tests/Feature/Admin/QuestionBankTypeImportTest.php',
    'tests/Feature/Admin/QuestionBankTypeImportMysqlTestCase.php',
    'database/seeders/QuestionTypeSeeder.php',
    'database/seeders/QuestionBankSeeder.php',
    'database/seeders/HtmlCssQuestionBankSeeder.php',
    'database/seeders/LaravelQuestionBankSeeder.php',
    'database/seeders/JavaScriptQuestionBankSeeder.php',
    'database/seeders/ComprehensiveQuestionBankSeeder.php',
    'database/seeders/QuizSeeder.php',
    'config/ai.php'
)

foreach ($f in $examFiles) { Copy-ProjectPath $f $ExamRoot | Out-Null }

$examMigrationPatterns = @(
    '*quiz*',
    '*question_types*',
    '*question_bank*',
    '*question_options*',
    '*question_pools*',
    '*question_pool_items*',
    '*question_modules*',
    '*question_module*',
    '*course_section_questions*',
    '*ai_question*',
    '*question_programming_language*'
)

$migrationDir = Join-Path $ProjectRoot 'database/migrations'
Get-ChildItem $migrationDir -File | Where-Object {
    $name = $_.Name
    $examMigrationPatterns | Where-Object { $name -like $_ }
} | ForEach-Object {
    Copy-ProjectPath ("database/migrations/" + $_.Name) $ExamRoot | Out-Null
}

# ─── GAMIFICATION: app ───────────────────────────────────────────────────────
Copy-ProjectPath 'app/Models/Gamification' $GamifRoot | Out-Null
Copy-ProjectPath 'app/Services/Gamification' $GamifRoot | Out-Null
Copy-ProjectPath 'app/Support/Gamification' $GamifRoot | Out-Null
Copy-ProjectPath 'app/Events/Gamification' $GamifRoot | Out-Null
Copy-ProjectPath 'app/Listeners/Gamification' $GamifRoot | Out-Null
Copy-ProjectPath 'app/Http/Controllers/Admin/Gamification' $GamifRoot | Out-Null
Copy-ProjectPath 'app/Http/Controllers/Student/Gamification' $GamifRoot | Out-Null
Copy-ProjectPath 'app/Http/Controllers/Api/Student/Gamification' $GamifRoot | Out-Null
Copy-ProjectPath 'app/Providers/GamificationServiceProvider.php' $GamifRoot | Out-Null

$gamifModels = @(
    'app/Models/Achievement.php',
    'app/Models/Badge.php',
    'app/Models/Challenge.php',
    'app/Models/DailyStreak.php',
    'app/Models/ExperienceLevel.php',
    'app/Models/GamificationNotification.php',
    'app/Models/Leaderboard.php',
    'app/Models/LeaderboardEntry.php',
    'app/Models/PointsTransaction.php',
    'app/Models/RewardCatalog.php',
    'app/Models/UserAchievement.php',
    'app/Models/UserBadge.php',
    'app/Models/UserChallenge.php',
    'app/Models/UserReward.php',
    'app/Models/UserStat.php',
    'app/Models/Models/Gamification/ShopCategory.php',
    'app/Http/Controllers/Student/GamificationController.php',
    'app/Mail/AchievementUnlockedEmail.php',
    'app/Mail/BadgeEarnedEmail.php',
    'app/Mail/LevelUpEmail.php'
)

foreach ($p in $gamifModels) { Copy-ProjectPath $p $GamifRoot | Out-Null }

$gamifCommands = @(
    'AssignDailyChallenges.php',
    'CheckAllBadges.php',
    'CheckExpiredChallenges.php',
    'GamificationDailyTasks.php',
    'RecalcGamificationBadges.php',
    'SendWeeklySummary.php',
    'UpdateGamificationStats.php',
    'UpdateLeaderboards.php'
)

foreach ($c in $gamifCommands) {
    Copy-ProjectPath ("app/Console/Commands/" + $c) $GamifRoot | Out-Null
}

$gamifViewDirs = @(
    'resources/views/admin/pages/gamification',
    'resources/views/student/pages/gamification',
    'resources/views/student/gamification',
    'tests/Feature/Gamification',
    'tests/Feature/Leaderboards'
)

foreach ($d in $gamifViewDirs) { Copy-ProjectPath $d $GamifRoot | Out-Null }

$gamifFiles = @(
    'resources/views/emails/achievement-unlocked.blade.php',
    'resources/views/emails/badge-earned.blade.php',
    'resources/views/emails/level-up.blade.php',
    'config/gamification.php',
    'database/seeders/AchievementSeeder.php',
    'database/seeders/BadgeSeeder.php',
    'database/seeders/ChallengeSeeder.php',
    'database/seeders/CompetitionSeeder.php',
    'database/seeders/LeaderboardSeeder.php',
    'database/seeders/LevelSeeder.php',
    'database/seeders/ShopCategorySeeder.php',
    'database/seeders/ShopItemSeeder.php',
    'database/seeders/NotificationTypeSeeder.php'
)

foreach ($f in $gamifFiles) { Copy-ProjectPath $f $GamifRoot | Out-Null }

$gamifMigrationPatterns = @(
    '*gamification*',
    '*badges*',
    '*achievements*',
    '*points_transactions*',
    '*user_stats*',
    '*leaderboards*',
    '*leaderboard_entries*',
    '*challenges*',
    '*daily_streaks*',
    '*user_challenges*',
    '*rewards_catalog*',
    '*user_rewards*',
    '*experience_levels*',
    '*user_badges*',
    '*user_achievements*'
)

Get-ChildItem $migrationDir -File | Where-Object {
    $name = $_.Name
    $gamifMigrationPatterns | Where-Object { $name -like $_ }
} | ForEach-Object {
    Copy-ProjectPath ("database/migrations/" + $_.Name) $GamifRoot | Out-Null
}

# XPService stub (missing in source)
$xpStubPath = Join-Path $GamifRoot 'app/Services/Gamification/XPService.php'
Ensure-Dir (Split-Path $xpStubPath -Parent)
@'
<?php

namespace App\Services\Gamification;

/**
 * Stub included in export bundle — implement or replace in target project.
 * Registered by GamificationServiceProvider.
 */
class XPService
{
    public function award(int $userId, int $amount, string $source = 'manual', ?string $description = null): void
    {
        // Delegate to PointsService or implement XP ledger in target project.
    }
}
'@ | Set-Content -Path $xpStubPath -Encoding UTF8

Write-Host 'Export copy completed. Extracting routes...'

function Extract-LinesToRouteFile {
    param(
        [string]$SourceFile,
        [int]$StartLine,
        [int]$EndLine,
        [string]$DestFile,
        [string]$HeaderComment,
        [string[]]$UseStatements = @()
    )

    $lines = Get-Content (Join-Path $ProjectRoot $SourceFile) -Encoding UTF8
    $chunk = $lines[($StartLine - 1)..($EndLine - 1)] -join "`n"

    $uses = ($UseStatements | ForEach-Object { "use $_;" }) -join "`n"
    $content = @"
<?php

/**
 * $HeaderComment
 *
 * Merge inside your admin/student/api route group in the target project.
 * See INSTALL.md for integration steps.
 */

$uses

$chunk
"@

    Ensure-Dir (Split-Path $DestFile -Parent)
    Set-Content -Path $DestFile -Value $content -Encoding UTF8
}

# Exam admin routes (quizzes through question-module-grading)
Extract-LinesToRouteFile `
    -SourceFile 'routes/admin.php' `
    -StartLine 323 -EndLine 416 `
    -DestFile (Join-Path $ExamRoot 'routes/admin.php') `
    -HeaderComment 'Exam module — admin routes (quizzes, question bank, pools, modules, grading, analytics)'

# Exam AI question routes (subset of admin/ai group)
Extract-LinesToRouteFile `
    -SourceFile 'routes/admin.php' `
    -StartLine 912 -EndLine 933 `
    -DestFile (Join-Path $ExamRoot 'routes/admin-ai-questions.php') `
    -HeaderComment 'Exam module — AI question routes (merge inside Route::prefix(ai) group)'

# Exam student routes
Extract-LinesToRouteFile `
    -SourceFile 'routes/student.php' `
    -StartLine 143 -EndLine 185 `
    -DestFile (Join-Path $ExamRoot 'routes/student.php') `
    -HeaderComment 'Exam module — student routes (quizzes + question modules)'

# Exam API routes
$apiLines = Get-Content (Join-Path $ProjectRoot 'routes/api.php') -Encoding UTF8
$apiChunk = ($apiLines[74..75] + $apiLines[173..180]) -join "`n"
@"

<?php

/**
 * Exam module — API routes (question-modules stats + quizzes)
 * Merge inside authenticated student API group.
 */

$apiChunk
"@ | Set-Content (Join-Path $ExamRoot 'routes/api.php') -Encoding UTF8

# Gamification admin routes
$adminGamifUses = @(
    'App\Http\Controllers\Admin\Gamification\DashboardController as GamificationDashboardController',
    'App\Http\Controllers\Admin\Gamification\PointsController as AdminPointsController',
    'App\Http\Controllers\Admin\Gamification\LevelController as AdminLevelController',
    'App\Http\Controllers\Admin\Gamification\BadgeController as AdminBadgeController',
    'App\Http\Controllers\Admin\Gamification\AchievementController as AdminAchievementController',
    'App\Http\Controllers\Admin\Gamification\LeaderboardController as AdminLeaderboardController',
    'App\Http\Controllers\Admin\Gamification\ChallengeController as AdminChallengeController',
    'App\Http\Controllers\Admin\Gamification\ShopCategoryController as AdminShopCategoryController',
    'App\Http\Controllers\Admin\Gamification\ShopItemController as AdminShopItemController',
    'App\Http\Controllers\Admin\Gamification\PurchaseController as AdminPurchaseController',
    'App\Http\Controllers\Admin\Gamification\SocialActivityController as AdminSocialActivityController',
    'App\Http\Controllers\Admin\Gamification\CompetitionController as AdminCompetitionController',
    'App\Http\Controllers\Admin\Gamification\AnalyticsController as AdminAnalyticsController'
)
Extract-LinesToRouteFile `
    -SourceFile 'routes/admin.php' `
    -StartLine 418 -EndLine 580 `
    -DestFile (Join-Path $GamifRoot 'routes/admin.php') `
    -HeaderComment 'Gamification module — admin routes' `
    -UseStatements $adminGamifUses

# Gamification student routes
$studentGamifUses = @(
    'App\Http\Controllers\Student\Gamification\AchievementController as StudentAchievementController',
    'App\Http\Controllers\Student\Gamification\ChallengeController as StudentChallengeController',
    'App\Http\Controllers\Student\Gamification\CompetitionController as StudentCompetitionController',
    'App\Http\Controllers\Student\Gamification\DashboardController as GamificationDashboardController',
    'App\Http\Controllers\Student\Gamification\FriendshipController as StudentFriendshipController',
    'App\Http\Controllers\Student\Gamification\InventoryController as StudentInventoryController',
    'App\Http\Controllers\Student\Gamification\LeaderboardController as StudentLeaderboardController',
    'App\Http\Controllers\Student\Gamification\LevelController as StudentLevelController',
    'App\Http\Controllers\Student\Gamification\NotificationController as StudentNotificationController',
    'App\Http\Controllers\Student\Gamification\PointsController as StudentPointsController',
    'App\Http\Controllers\Student\Gamification\ShopController as StudentShopController',
    'App\Http\Controllers\Student\Gamification\SocialActivityController as StudentSocialActivityController',
    'App\Http\Controllers\Student\Gamification\StreakController as StudentStreakController'
)
Extract-LinesToRouteFile `
    -SourceFile 'routes/student.php' `
    -StartLine 187 -EndLine 340 `
    -DestFile (Join-Path $GamifRoot 'routes/student.php') `
    -HeaderComment 'Gamification module — student routes' `
    -UseStatements $studentGamifUses

# Gamification API routes
$apiGamifUses = @(
    'App\Http\Controllers\Api\Student\Gamification\AchievementApiController as StudentAchievementApiController',
    'App\Http\Controllers\Api\Student\Gamification\BadgeApiController as StudentBadgeApiController',
    'App\Http\Controllers\Api\Student\Gamification\ChallengeApiController as StudentChallengeApiController',
    'App\Http\Controllers\Api\Student\Gamification\LeaderboardApiController as StudentLeaderboardApiController',
    'App\Http\Controllers\Api\Student\Gamification\PointsApiController as StudentPointsApiController',
    'App\Http\Controllers\Api\Student\Gamification\ShopApiController as StudentShopApiController',
    'App\Http\Controllers\Api\Student\Gamification\StreakApiController as StudentStreakApiController'
)
Extract-LinesToRouteFile `
    -SourceFile 'routes/api.php' `
    -StartLine 151 -EndLine 172 `
    -DestFile (Join-Path $GamifRoot 'routes/api.php') `
    -HeaderComment 'Gamification module — student API routes' `
    -UseStatements $apiGamifUses

# Gamification console schedule
@'

/*
|--------------------------------------------------------------------------
| Gamification Scheduled Tasks — copy into routes/console.php
|--------------------------------------------------------------------------
*/

Schedule::command('gamification:daily-tasks')
    ->dailyAt('00:00')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('gamification:update-stats')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('gamification:weekly-summary')
    ->weeklyOn(0, '09:00')
    ->withoutOverlapping()
    ->runInBackground();
'@ | Set-Content (Join-Path $GamifRoot 'routes/console-schedule.php') -Encoding UTF8

Write-Host 'Generating manifests...'
Write-Manifest -ModuleRoot $ExamRoot -ModuleName 'exam'
Write-Manifest -ModuleRoot $GamifRoot -ModuleName 'gamification'
Write-Host 'Done.'
