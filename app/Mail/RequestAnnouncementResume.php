<?php

namespace App\Mail;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestAnnouncementResume extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user_model, Announcement $announcement_model)
    {
        $this->user = $user_model;
        $this->announcement = $announcement_model;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Request Application')
                ->markdown('emails.request_announcement_resume', [
                    'hello_name' => $this->user->first_name,
                    'announcement_title' => $this->announcement->announcement_title,
                    'company_name_th' => $this->announcement->company_name_th,
                    'company_name_en' => $this->announcement->company_name_en,
                    'url' => env('FRONT_END_URL')
                ]);
    }
}
