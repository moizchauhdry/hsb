<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkOrderInvoiceNotification extends Notification
{
    use Queueable;
    public $work_order;

    /**
     * Create a new notification instance.
     */
    public function __construct($work_order)
    {
        $this->work_order = $work_order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route("work-order.pdf", $this->work_order->id);

        return (new MailMessage)
            ->subject('Work Order Invoice')
            ->greeting('Dear Customer,')
            ->line('The updated invoice is attached below. Please click the link to open it.')
            ->action('Invoice',  $url)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
