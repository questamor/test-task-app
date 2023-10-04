<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;

class AddUserGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:member';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавление пользователя в группу';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->ask('user_id пользователя');
        $groupId = $this->ask('group_id группы');

        try
        {
            $user = User::findOrFail($userId);
            $group = Group::findOrFail($groupId);
        }
        catch (ModelNotFoundException $e)
        {
            $this->info('Пользователь или группа с таким идентификатором не найдена');
            return Command::FAILURE;
        }

        try
        {
            $user->groups()->attach($group);

            if ($user->active == false)
            {
                $user->active = true;
                $user->save();
            }
        }
        catch (UniqueConstraintViolationException $exception)
        {
            $this->info('Пользователь уже есть в этой группе');
            return Command::FAILURE;
        }
    }
}
