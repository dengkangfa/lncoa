<?php

namespace App\Notifications;

use App\Applicat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\FileManager\UploadManager;
use Illuminate\Notifications\Messages\MailMessage;

class reviewResults extends Notification
{
    use Queueable;

    public $manager;

    public $applicat;

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
        $subject = $mechanismName . $this->applicat->type->name.'审核通过 - lncoa';
        $url = url('/applicat-manage/details/' . $this->applicat->id);
        $message = '您于'.$this->applicat->created_at.'申请的'
                    .'<'.$mechanismName.'-'
                    .$this->applicat->type->name
                    .'>'.'成功通过了审核!';
        $actionText = '查看';
        return (new MailMessage)
                            ->subject($subject)
                            ->markdown('mail.review.results',
                            ['name' => $notifiable->name,
                             'url' => $url,
                             'message' => $message,
                             'applicat' => $this->applicat,
                             'opinions' => $this->applicat->opinions,
                             'actionText' => $actionText
                           ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // $user = $this->applicat->user;
        // return [
        //     'type' => 'system',
        //     ''
        // ];
    }
}
