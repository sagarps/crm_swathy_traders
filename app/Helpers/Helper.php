<?php
namespace App\Helpers;

use Carbon\Carbon;

class Helper
{
    public static function getTrialStatus($user)
    {
        if(!$user){
            return ['status' => 'no_trial', 'message' => 'No trial period set.'];
        }
        if ($user->trial_ends_at == null) {
            return ['status' => 'no_trial', 'message' => 'No trial period set.'];
        }

        $now = Carbon::now();
        
        $trialEndsAt = Carbon::parse($user->trial_ends_at);

        if ($now->lt($trialEndsAt)) {
            // Trial still active
            $remainingDays = $now->diffInDays($trialEndsAt);
            return ['status' => 'active', 'remaining_days' => $remainingDays];
        } else {
            // Trial expired
            $expiredDays = $trialEndsAt->diffInDays($now);
            return ['status' => 'expired', 'expired_days' => $expiredDays];
        }
    }
}
