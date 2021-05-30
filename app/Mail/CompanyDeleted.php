<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use App\Models\Company;

class CompanyDeleted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user_model, Company $company)
    {
        $this->user = $user_model;
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Notification: Information of Company already has deleted.')
                ->view('emails.company_deleted', [
                    'hello_name' => $this->user->first_name,
                    'company_name_th' => $this->company->company_name_th,
                    'company_name_en' => $this->company->company_name_en,
                    'url' => env('FRONT_END_URL')
                ]);
    }
}
