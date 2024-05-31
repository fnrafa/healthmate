<?php

namespace App\Providers;

use App\Models\Referral;
use App\Models\User;

class AppProvider
{
    protected ?User $user;

    public function __construct()
    {
        $this->user = auth();
    }

    public static function provider(): AppProvider
    {
        return new self();
    }

    public function newReferralTotal(): int
    {
        if (!$this->user) {
            return 0;
        }

        return Referral::where('hospital_id', $this->user->id)
            ->where('status', 'pending')
            ->count();
    }
}
