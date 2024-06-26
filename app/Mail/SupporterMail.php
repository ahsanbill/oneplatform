<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\UserSupporter;
use App\Models\User;

class SupporterMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $supporter;
    public $mode;

    public function __construct(UserSupporter $userSupporter, $mode)
    {
        $this->supporter = $userSupporter;
        $this->mode = $mode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->mode == 'verify-email'){

            return $this->view('pages.email.user-supporter-verify-email')
            ->subject($this->supporter->supporter_name.", verify your email")
            ->with([
                'supporter' => $this->supporter,
            ]);
        } else if($this->mode == 'request-approval'){

            return $this->view('pages.email.user-supporter-request-approval')
            ->subject($this->supporter->supporter_name.", want to become your supporter")
            ->with([
                'supporter' => $this->supporter,
            ]);
        } else if($this->mode == 'request-approved'){

            $owner = User::find($this->supporter->owner_user_id);
            return $this->view('pages.email.user-supporter-request-approved')
            ->subject($owner->name.", has approved your request to become a supporter")
            ->with([
                'supporter' => $this->supporter,
            ]);
        }
    }
}
