<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PersonalAccessToken;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email; // Token reset password

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pac = PersonalAccessToken::where('name',$this->email)->orderBy('id','desc')->first();

        $resetPasswordUrl = route('reset-password', ['token' => $pac->token]);

        return $this->from('product@bandungchoral.com', 'Bandung Choral Society') 
                    ->view('emails.resetPassword')
                    ->with([
                        'resetPasswordUrl' => $resetPasswordUrl,
                        'email' => $pac->name,
                    ])
                    ->subject('Reset Password');
    }
}
