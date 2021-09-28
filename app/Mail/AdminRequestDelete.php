<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use App\Models\Company;

class AdminRequestDelete extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user_model, User $user_request, Company $company)
    {
        $this->user = $user_model;
        $this->user_req = $user_request;
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Delete Information of Company')
                ->markdown('emails.admin_request_delete', [
                    'hello_name' => $this->user->first_name,
                    'user_req' => $this->user_req->first_name,
                    'company_name_th' => $this->company->company_name_th,
                    'company_name_en' => $this->company->company_name_en,
                    'company_id' => $this->company->company_id,
                    'url' => env('FRONT_END_URL')
                ]);
    }
}
