<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use App\Models\Company;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user_model, $code)
    {
        $this->user = $user_model;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to SIT Career Center')
                ->markdown('emails.verify_email', [
                    'email' => $this->user->email,
                    'company_name_th' => null,
                    'company_name_en' => null,
                    'code_verify' => $this->code,
                    'url' => env('FRONT_END_URL')
                ]);
    }
}
