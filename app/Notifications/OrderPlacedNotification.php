<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification
{
    use Queueable;
    public $order;
    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [

            'mail',

            'database',
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)

            ->subject('Order Placed Successfully')

            ->greeting(
                'Hello ' .
                $this->order->customer_name
            )

            ->line(
                'Your order has been placed successfully.'
            )

            ->line(
                'Order Total: ₹' .
                $this->order->total_amount
            )

            ->line(
                'Payment Status: ' .
                $this->order->payment_status
            )

            ->line(
                'Thank you for shopping with us!'
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [

            'order_id' =>
                $this->order->id,

            'customer_name' =>
                $this->order->customer_name,

            'total' =>
                $this->order->total_amount,

            'status' =>
                $this->order->status,
        ];
    }
}
