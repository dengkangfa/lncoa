<?php

namespace App\Notifications;

use App\User;
use App\Applicat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AppraisalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $applicat;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Applicat $applicat)
    {
        $this->applicat = $applicat;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mechanismName = $this->applicat->mechanism->name;
        $user = User::find($this->applicat->appraisal->user_id);
        $subject = $mechanismName . $this->applicat->type->name.'收到评价 - lncoa';
        $message = '您申请的项目<'.$mechanismName
                    . $this->applicat->type->name
                    . '>收到了一条来着'
                    . $user->name
                    . '的评价.';
        return (new MailMessage)
                    ->subject($subject)
                    ->line($message)
                    ->action('查看', url('/applicat-manage'))
                    ->line('感谢您使用我们的应用程序!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
