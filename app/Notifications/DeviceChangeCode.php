<?php

namespace AttendCheck\Notifications;

use AttendCheck\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DeviceChangeCode extends Notification
{
    use Queueable;

    /**
     * Instance of \AttendCheck\User
     * @var \AttendCheck\User
     */
    private $user;
    private $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
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
        return (new MailMessage)
                    ->greeting('เรียนคุณ ' . $this->user->fullname())
                    ->line('คุณได้รับอีเมลฉบับนี้เนื่องจากคุณได้ขอเปลี่ยนอุปกรณ์ใหม่')
                    ->line('รหัสในการเปลี่ยนอุปกรณ์ของคุณคือ ' . $this->token)
                    ->line('กรุณาใส่รหัสนี้ที่ปุ่ม กรอกรหัสเปลี่ยนอุปกรณ์ ในแอพลิเคชั่นของท่าน');
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
