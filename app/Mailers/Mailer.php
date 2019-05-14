<?php namespace App\Mailers;


use Illuminate\Mail\Mailer as Mail;

abstract class Mailer
{
    private $mail;

    /**
     * @param Mail $mail
     */
    function __construct( Mail $mail )
    {
        $this->mail = $mail;
    }

    /**
     * Send email
     * 
     * @param $email
     * @param $subject
     * @param $view
     * @param array $data
     */
    public function sendTo($email, $subject, $view, $data = [] , $from = null)
    {
        $this->mail->send( $view, $data, function($message) use($email, $subject, $from)
        {
            if ( $from )
                if ( is_array($from) )
                    $message->from( $from['email'], $from['name'] );
                else
                    $message->from( $from );

            $message->to($email)->subject($subject);
        });
    }

    /**
     * Queue an email to be sent
     * 
     * @param $email
     * @param $subject
     * @param $view
     * @param array $data
     */
    public function sendToQueued($email, $subject, $view, $data = [])
    {
        $this->mail->queue($view, $data, function($message) use($email, $subject)
        {
            $message->to($email)->subject($subject);
        });
    }

    /**
     * Send an email later
     * 
     * @param $email
     * @param $subject
     * @param $view
     * @param array $data
     */
    public function sendToLater($email, $subject, $view, $data = [], $later = 1, $from = null)
    {
        $this->mail->later($later ,  $view, $data, function($message) use($email, $subject, $from)
        {
            if ( $from )
                if ( is_array($from) )
                    $message->from( $from['email'], $from['name'] );
                else
                    $message->from( $from );
                
            $message->to($email)->subject($subject);
        });
    }
}