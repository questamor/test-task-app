<?php

namespace App\Console\Commands;

use App\Jobs\DeactivateOrphanUser;
use App\Jobs\SendGroupExpiredEmail;
use App\Models\GroupUser;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;

class UserCheckExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check_expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Исключить из групп всех пользователей, у которых expired_at меньше текущего момента времени';
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // RAW выборка, так как whereDate не учитывает время
        $expiredGroupUsers = GroupUser::whereRaw("expired_at <= STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')" , Carbon::now()->format('Y-m-d H:i'))->get();        

        if (!$expiredGroupUsers->isEmpty())
        {
            $expiredGroupUsers->each(function(GroupUser $groupUser) {
                try
                {
                    $groupUser->user->groups()->detach($groupUser->group_id);
                
                    $emailJob = (new SendGroupExpiredEmail($groupUser->user->email, [
                        'subject' => 'Исключение из группы',
                        'body' => "Здравствуйте, {$groupUser->user->name}! Истекло время вашего участия в группе {$groupUser->group->name}"
                    ]))->delay(now()->addSeconds(2)); 

                    $deactivateUserJob = (new DeactivateOrphanUser($groupUser->user))->delay(now()->addSeconds(2)); 

                    dispatch($emailJob)->onQueue('emails');
                    dispatch($deactivateUserJob)->onQueue('default');
                }
                catch (Exception $ex)
                {
                    $this->info("Команда не выполнена: {$ex->getMessage()}");
                    return false;
                }
            });
        }
    }
}
