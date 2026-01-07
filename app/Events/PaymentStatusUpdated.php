<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $orderNumber;
    public string $paymentStatus;
    public string $orderStatus;
    public float $total;

    public function __construct(Order $order)
    {
        $this->orderNumber = $order->order_number;
        $this->paymentStatus = $order->payment_status;
        $this->orderStatus = $order->order_status;
        $this->total = (float) $order->total_amount;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('payments.' . $this->orderNumber);
    }

    public function broadcastAs(): string
    {
        return 'payment.status';
    }

    public function broadcastWith(): array
    {
        return [
            'order_number' => $this->orderNumber,
            'payment_status' => $this->paymentStatus,
            'order_status' => $this->orderStatus,
            'total' => $this->total,
        ];
    }
}
