<?php

/**
 * Merge into App\Models\User — gamification relationships.
 * Add use statements for models at top of User.php.
 */

// use App\Models\UserStat;
// use App\Models\Badge;
// use App\Models\UserBadge;
// use App\Models\Achievement;
// use App\Models\UserAchievement;
// use App\Models\PointsTransaction;
// use App\Models\DailyStreak;
// use App\Models\Challenge;
// use App\Models\UserChallenge;
// use App\Models\RewardCatalog;
// use App\Models\UserReward;
// use App\Models\LeaderboardEntry;
// use App\Models\ExperienceLevel;
// use App\Models\GamificationNotification;

public function stats()
{
    return $this->hasOne(UserStat::class);
}

public function badges()
{
    return $this->belongsToMany(Badge::class, 'user_badges')
        ->withPivot(['awarded_at', 'reason', 'related_type', 'related_id', 'progress', 'is_seen', 'is_featured', 'points_awarded', 'metadata'])
        ->withTimestamps();
}

public function userBadges()
{
    return $this->hasMany(UserBadge::class);
}

public function achievements()
{
    return $this->belongsToMany(Achievement::class, 'user_achievements')
        ->withPivot(['current_value', 'target_value', 'progress_percentage', 'status', 'started_at', 'completed_at', 'claimed_at', 'related_type', 'related_id', 'progress_data', 'points_claimed', 'xp_claimed', 'is_notified'])
        ->withTimestamps();
}

public function userAchievements()
{
    return $this->hasMany(UserAchievement::class);
}

public function pointsTransactions()
{
    return $this->hasMany(PointsTransaction::class);
}

public function dailyStreak()
{
    return $this->hasOne(DailyStreak::class);
}

public function challenges()
{
    return $this->belongsToMany(Challenge::class, 'user_challenges')
        ->withPivot(['current_value', 'target_value', 'progress_percentage', 'status', 'joined_at', 'started_at', 'completed_at', 'expires_at', 'points_earned', 'xp_earned', 'rewards_claimed', 'progress_data', 'team_id', 'is_team_leader', 'is_notified', 'attempts_count'])
        ->withTimestamps();
}

public function userChallenges()
{
    return $this->hasMany(UserChallenge::class);
}

public function rewards()
{
    return $this->belongsToMany(RewardCatalog::class, 'user_rewards', 'user_id', 'reward_id')
        ->withPivot(['purchased_at', 'points_spent', 'status', 'delivery_code', 'delivery_details', 'delivered_at', 'claimed_at', 'expires_at', 'is_expired', 'transaction_id', 'approved_by', 'approved_at', 'admin_notes', 'metadata'])
        ->withTimestamps();
}

public function userRewards()
{
    return $this->hasMany(UserReward::class);
}

public function leaderboardEntries()
{
    return $this->hasMany(LeaderboardEntry::class);
}

public function experienceLevel()
{
    return $this->belongsTo(ExperienceLevel::class, 'current_level', 'level');
}

public function gamificationNotifications()
{
    return $this->hasMany(GamificationNotification::class);
}
