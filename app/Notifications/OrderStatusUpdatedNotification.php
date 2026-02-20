<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusUpdatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Order $order
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
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
            'order_id' => $this->order->id,
            'user_id' => $this->order->user_id,
            'subtotal' => $this->order->subtotal,
            'order_number' => $this->order->order_number,
            'status' => $this->order->status,
            'title' => $this->getTitle(),
            'message' => $this->getMessage()
        ];
    }

    protected function getTitle()
    {
        // processing,confirmed,shipped,completed,cancelled
        return match ($this->order->status) {
            'processing' => 'Order Placed',
            'confirmed' => 'Payment Confirmed',
            'shipped' => 'Order Shipped',
            'completed' => 'Order Delivered',
            'cancelled' => 'Order Cancelled',
            default => 'Order updated',
        };
    }

    protected function getMessage()
    {
        return "Your order #{$this->order->order_number} is now {$this->order->status}.";
    }
}
