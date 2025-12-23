<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all cart items to use the current product price
        $items = \App\Models\CartItem::with('product')->get();
        
        foreach ($items as $item) {
            if ($item->product) {
                // If product exists, update price to current product price
                $item->price = $item->product->price;
                $item->subtotal = $item->quantity * $item->product->price;
                $item->save();
            } else {
                 $item->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
