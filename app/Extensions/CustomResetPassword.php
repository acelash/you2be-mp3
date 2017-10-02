<?php
/**
 * Created by PhpStorm.
 * User: gouan
 * Date: 31.01.2017
 * Time: 21:22
 */

namespace App\Extensions;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(trans("auth.reset_password_subject"))
            ->greeting(trans("auth.reset_password_greeting"))
            ->line(trans("auth.reset_password_line_1"))
            ->action(trans("auth.reset_password_btn"), env("APP_URL") .'password/reset/'. $this->token) //url('password/reset', $this->token)
            ->line(trans("auth.reset_password_ignore"));
    }
}
