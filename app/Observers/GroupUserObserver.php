<?php

namespace App\Observers;

use App\Models\GroupUser;
use Carbon\Carbon;

class GroupUserObserver
{
    /**
     * Handle the GroupUser "creating" event.
     */
    public function creating(GroupUser $groupUser): void
    {
        $groupUser->expired_at = Carbon::now()->addHours($groupUser->group->expire_hours)->toDateTimeString();        
    }

    public function created(GroupUser $groupUser): void
    {
        info('Group was attached to user', ['data' => $groupUser]);
    }
}
