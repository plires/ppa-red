<?php

namespace App\Jobs;

use App\Mail\PartnerWelcomeMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class SendPartnerWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $partner;

    public function __construct(User $partner)
    {
        $this->partner = $partner;
    }

    public function handle(): void
    {
        $token = Password::broker()->createToken($this->partner);

        $setPasswordUrl = route('password.reset', [
            'token' => $token,
            'email' => $this->partner->email,
        ]);

        Mail::to($this->partner->email)->send(new PartnerWelcomeMail($this->partner, $setPasswordUrl));
    }
}
