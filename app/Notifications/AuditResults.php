<?php

namespace App\Notifications;

use App\Applicat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\FileManager\UploadManager;
use Illuminate\Notifications\Messages\MailMessage;

class AuditResults extends Notification
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
        // $path = 'avatars/' . $notifiable->id;
        // $img = \QrCode::format('png')->size(500)->generate("/", $path);
        //
        // // $path = \Storage::putFile($path, $img);
        // // \Log::info($path);
        //
        // return (new MailMessage)->view('mail.audit.results',['img' => $path,'name' => 'dkf']);
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
