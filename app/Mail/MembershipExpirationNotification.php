<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\UserMembership;

class MembershipExpirationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public UserMembership $membership;
    public Carbon $today;

    public function __construct(UserMembership $membership, Carbon $today)
    {
        $this->membership = $membership;
        $this->today = $today;
    }

    public function build()
    {
        return $this->subject('Your Membership is About to Expire')
            ->view('mail.membership_expiration');
    }
}
