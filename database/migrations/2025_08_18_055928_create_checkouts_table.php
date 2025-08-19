<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // the user who checked out
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // item bought
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_method', ['cash', 'qr']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkouts');
    }
};
