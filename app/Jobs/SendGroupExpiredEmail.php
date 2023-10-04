<?php

namespace App\Jobs;

use App\Mail\UserMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendGroupExpiredEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailAddress;
    protected $details;
    public $timeout = 3600;

    public function __construct(string $emailAddress, array $details)
    {
        $this->emailAddress = $emailAddress;
        $this->details = $details;
    }

    public function handle() :void
    {
        Mail::to($this->emailAddress)->send(new UserMail($this->details));
    }
}
