<?php namespace App\Mailers;

use App\shabesh\Agency\Agency;
use App\shabesh\PasswordReset\PasswordReset;
use App\shabesh\User\User;
use App\shabesh\Password\Password;

class UserMailer extends Mailer
{

    /**
     * @param User $user
     */

    public function sendEmailRegistrationActivationEmail(  $data  )
    {
        $subject = "confirm your reset password";
        $view = 'emaile.resetpass';
        $user = compact( 'data' );
        $this->sendTo( $data["email"], $subject, $view, $user );
    }

}