<?php

namespace App\Notifications;

use App\Applicat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PendReview extends Notification implements ShouldQueue
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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = $this->applicat->type->name.'申请待审核 - lncoa';
        $message = '您所管理的' . $this->applicat->type->name .'收到了一条来自' . '<' .
              $this->applicat->mechanism->name . '>' . $this->applicat->user->name . '的申请';
        $url = '/audit/details/'.$this->applicat->id;
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting('尊敬的' . $notifiable->name)
                    ->line($message)
                    ->action('查看', url($url));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // return $this->applicat->toArray();
        $user = $this->applicat->user;
        return [
            'applicat_id' => $this->applicat->id,
            'avatar'      => $user->avatar,
            'name'        => $user->name,
            'reason'      => $this->applicat->reason,
        ];
    }
}
