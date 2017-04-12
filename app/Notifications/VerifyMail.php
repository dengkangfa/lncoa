<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyMail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $url = route('confirm_email', $notifiable->activation_token);
        return (new MailMessage)
                    ->subject('邮箱激活 - lncoa')
                    ->line('感谢您在 lncoa 网站进行注册.')
                    ->line('请点击下面的激活按钮完成注册')
                    ->action('激活', $url)
                    ->line('如果这不是您本人的操作，请忽略此邮件!')
                    ->line('感谢您使用我们的应用程序！');
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
          // 'invoice_id' => $this->invoice->id,
          // 'amount' => $this->invoice->amount,
        ];
    }
}
